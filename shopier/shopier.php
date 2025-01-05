<?php
// Shopier API bilgileri
$api_key = 'd46724cc9f0f27e5dc41609871fec9ff';
$api_secret = 'ef95da16f702b5d44cc6d1f4a9f09daa';

// Sipariş bilgileri
$order_id = uniqid(); // Benzersiz bir sipariş numarası oluşturun
$payment_amount = 100.00; // Ödeme tutarı
$callback_url = 'https://yourdomain.com/callback.php'; // Ödeme sonrası dönüş URL'si

// Shopier verileri
$data = [
    'API_key' => $api_key,
    'order_id' => $order_id,
    'payment_amount' => $payment_amount,
    'callback_url' => $callback_url,
];

// Verileri şifreleme (Güvenlik için imza oluşturma)
ksort($data);
$signature = hash_hmac('sha256', http_build_query($data), $api_secret);
$data['signature'] = $signature;

// Kullanıcıyı ödeme sayfasına yönlendirme
$shopier_url = 'https://www.shopier.com/ShowProduct';
header('Location: ' . $shopier_url . '?' . http_build_query($data));
exit;
?>
