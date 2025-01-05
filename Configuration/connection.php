<?php
$host = "127.0.0.1";
$user = "root";
$password = "yorgun.1292";
$dbname = "esport";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Hata: " . $e->getMessage();
}
?>
