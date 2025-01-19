<?php
class Shopier
{
    private $paymentUrl = 'https://www.shopier.com/ShowProduct/api_pay4.php';
    private $apiKey;
    private $apiSecret;
    private $moduleVersion;
    private $buyer = [];
    private $currency = 'TRY';

    public function __construct($apiKey, $apiSecret, $moduleVersion = '1.0.4')
    {
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
        $this->moduleVersion = $moduleVersion;
    }

    public function setBuyer(array $fields)
    {
        $this->validateAndLoadFields($this->buyerFields(), $fields, 'Buyer');
    }

    public function setOrderBilling(array $fields)
    {
        $this->validateAndLoadFields($this->orderBillingFields(), $fields, 'Order Billing');
    }

    public function setOrderShipping(array $fields)
    {
        $this->validateAndLoadFields($this->orderShippingFields(), $fields, 'Order Shipping');
    }

    private function validateAndLoadFields($requiredFields, $fields, $fieldType)
    {
        $missingFields = array_diff_key($requiredFields, $fields);

        if (!empty($missingFields)) {
            throw new Exception($fieldType . ' fields missing: ' . implode(', ', array_keys($missingFields)));
        }

        foreach ($requiredFields as $key => $required) {
            $this->buyer[$key] = $fields[$key];
        }
    }

    public function generateFormObject($orderId, $orderTotal, $callbackUrl)
    {
        $this->validateBuyerData();

        $args = [
            'API_key' => $this->apiKey,
            'website_index' => 1,
            'platform_order_id' => $this->buyer['id'],
            'product_name' => $this->buyer['product_name'],
            'product_type' => 0, // 1: downloadable, 0: physical, 2: default
            'buyer_name' => $this->buyer['first_name'],
            'buyer_surname' => $this->buyer['last_name'],
            'buyer_email' => $this->buyer['email'],
            'buyer_account_age' => 0,
            'buyer_id_nr' => $this->buyer['id'],
            'buyer_phone' => $this->buyer['phone'],
            'billing_address' => $this->buyer['billing_address'],
            'billing_city' => $this->buyer['billing_city'],
            'billing_country' => $this->buyer['billing_country'],
            'billing_postcode' => $this->buyer['billing_postcode'],
            'shipping_address' => $this->buyer['shipping_address'],
            'shipping_city' => $this->buyer['shipping_city'],
            'shipping_country' => $this->buyer['shipping_country'],
            'shipping_postcode' => $this->buyer['shipping_postcode'],
            'total_order_value' => $orderTotal,
            'currency' => $this->getCurrency(),
            'platform' => 0,
            'is_in_frame' => 0,
            'current_language' => $this->getLanguageCode(),
            'modul_version' => $this->moduleVersion,
            'random_nr' => rand(100000, 999999)
        ];

        $data = $args['random_nr'] . $args['platform_order_id'] . $args['total_order_value'] . $args['currency'];
        $args['signature'] = base64_encode(hash_hmac('sha256', $data, $this->apiSecret, true));
        $args['callback'] = $callbackUrl;

        return [
            'elements' => [
                [
                    'tag' => 'form',
                    'attributes' => [
                        'id' => 'shopier_form_special',
                        'method' => 'post',
                        'action' => $this->paymentUrl
                    ],
                    'children' => array_map(fn($key, $value) => [
                        'tag' => 'input',
                        'attributes' => [
                            'name' => $key,
                            'value' => $value,
                            'type' => 'hidden'
                        ]
                    ], array_keys($args), array_values($args))
                ]
            ]
        ];
    }

    public function generateForm($orderId, $orderTotal, $callbackUrl)
    {
        $formObject = $this->generateFormObject($orderId, $orderTotal, $callbackUrl);
        return $this->generateHtml($formObject['elements']);
    }

    public function run($orderId, $orderTotal, $callbackUrl)
    {
        $formHtml = $this->generateForm($orderId, $orderTotal, $callbackUrl);
        return <<<HTML
        <!doctype html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Ödeme Yönlendirme</title>
        </head>
        <body>
            $formHtml
            <script type="text/javascript">
                document.getElementById("shopier_form_special").submit();
            </script>
        </body>
        </html>
HTML;
    }

    private function validateBuyerData()
    {
        $missingBuyerFields = array_diff_key($this->buyerFields(), $this->buyer);
        if (!empty($missingBuyerFields)) {
            throw new Exception('Missing buyer fields: ' . implode(', ', array_keys($missingBuyerFields)));
        }
    }

    private function generateHtml(array $elements)
    {
        $html = '';
        foreach ($elements as $element) {
            $attributes = implode(' ', array_map(
                fn($key, $value) => $key . '="' . htmlspecialchars($value) . '"',
                array_keys($element['attributes']),
                $element['attributes']
            ));

            $html .= "<{$element['tag']} $attributes>";

            if (!empty($element['children'])) {
                $html .= $this->generateHtml($element['children']);
            }

            $html .= "</{$element['tag']}>";
        }
        return $html;
    }

    public function verifyShopierSignature($postData)
    {
        if (isset($postData['platform_order_id'], $postData['random_nr'], $postData['signature'])) {
            $expectedSignature = hash_hmac('sha256', $postData['random_nr'] . $postData['platform_order_id'], $this->apiSecret, true);
            return base64_decode($postData['signature']) === $expectedSignature;
        }
        return false;
    }

    private function buyerFields()
    {
        return ['id' => true, 'first_name' => true, 'last_name' => true, 'email' => true, 'phone' => true,'product_name' => true];
    }

    private function orderBillingFields()
    {
        return ['billing_address' => true, 'billing_city' => true, 'billing_country' => true, 'billing_postcode' => true];
    }

    private function orderShippingFields()
    {
        return ['shipping_address' => true, 'shipping_city' => true, 'shipping_country' => true, 'shipping_postcode' => true];
    }

    private function getCurrency()
    {
        $currencyMap = ['TRY' => 0, 'USD' => 1, 'EUR' => 2];
        return $currencyMap[strtoupper($this->currency)] ?? 0;
    }

    private function getLanguageCode()
    {
        return 0; // Türkçe
    }
}
