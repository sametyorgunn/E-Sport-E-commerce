<?php
// Shopier'den gelen veriler
$status = $_POST["status"];
$invoiceId = $_POST["platform_order_id"];
$transactionId = $_POST["payment_id"];
$installment = $_POST["installment"];
$signature = $_POST["signature"];
$url = 'https://siteadresi.com/';
$siparisonay = $url . "siparis?siparisno=$invoiceId";
$siparisiptal = $url . "siparis?siparisno=none";
$shopier_data = $_POST;

// Gelen veriyi doğrulama
$received_signature = $shopier_data['signature'];
unset($shopier_data['signature']);

// İmza oluşturma
ksort($shopier_data);
$calculated_signature = hash_hmac('sha256', http_build_query($shopier_data), 'YOUR_API_SECRET');

if ($calculated_signature === $received_signature) {
    // Ödeme başarılı
    $order_id = $shopier_data['order_id'];
    $status = $shopier_data['status']; // Payment Status
    if ($status === 'success') {
        // İşlemi tamamlayın (veritabanına kaydedin, e-posta gönderin, vb.)
        echo "Ödeme başarılı!";
    } else {
        echo "Ödeme başarısız!";
    }
} else {
    // Güvenlik doğrulaması başarısız
    echo "Hatalı ödeme doğrulaması!";
}
?>
