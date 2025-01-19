<?php
include __DIR__ . '/shopierApi.php';
include '../Configuration/connection.php';

session_start();
ob_start();
$orderId = $_SESSION["orderId"];
$order = $conn->prepare("SELECT * from orders
WHERE 
Id = :id");
$order->execute(['id' => $orderId]);
$order = $order->fetch(PDO::FETCH_ASSOC);

$orderDetail = $conn->prepare('select * from orderdetail as od
Inner Join product p on od.ProductId = p.Id
where OrderId =:id');
$orderDetail->execute(['id'=> $orderId]);
$orderDetails = $orderDetail->fetchAll(PDO::FETCH_ASSOC);

$productNames = ''; 

foreach ($orderDetails as $detail) {
    $productNames .= $detail['Name'] . ', ';
}

$shopierApiKey = 'd46724cc9f0f27e5dc41609871fec9ff';
$shopierApiSecret = 'ef95da16f702b5d44cc6d1f4a9f09daa';

$shopier = new Shopier($shopierApiKey, $shopierApiSecret);

$shopier->setBuyer([
    'id' => '123456', // Müşteri ID'si
    'first_name' => $order['CustomerName'],
    'last_name' => $order['CustomerSurname'],
    'email' => $order['CustomerMail'],
    'phone' => $order['CustomerPhone'],
    'product_name' => $productNames
]);

$shopier->setOrderBilling([
    'billing_address' => $order['Address'],
    'billing_city' => $order['City'],
    'billing_country' => $order['Country'],
    'billing_postcode' => '34000',
]);

$shopier->setOrderShipping([
    'shipping_address' => $order['Address'],
    'shipping_city' => $order['City'],
    'shipping_country' => $order['Country'],
    'shipping_postcode' => '34000',
]);

$orderID = uniqid(); 
$orderTotal = $order['TotalPrice']; 
$callbackUrl = 'http://localhost/urun'; 

try {
    echo $shopier->run($orderID, $orderTotal, $callbackUrl);
} catch (Exception $e) {
    echo 'Hata: ' . $e->getMessage();
}
