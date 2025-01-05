<?php
include "Configuration/Connection.php";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? $_POST['action'] : '';

    switch ($action) {
        case 'addToCart':
            $productID = $_POST['prodID'] ?? '';
            $urun = $conn->query("SELECT * FROM product WHERE Id = $productID")->fetch();
            if($urun['SalePrice'] != 0){
                $productPrice = $urun['SalePrice'];
            }else{
                $productPrice = $urun['Price'];
            }

            $size = $_POST['size'] ?? '';
            $color = $_POST['color'] ?? '';
            $quantity = $_POST['quantity'] ?? '';
            $prodID = $_POST['prodID'] ?? '';
            $variationDetails = $size . "-" . $color;
            $price = $quantity * $productPrice;
            if (!empty($size) && !empty($color) && !empty($quantity) && !empty($prodID)) {
                try {
                    $stmt = $conn->prepare("INSERT INTO basket (ProductId, Quantity, Price, VariationDetails, CreatedAt) VALUES (:productId, :quantity, :price, :variationDetails, NOW())");
                    $stmt->bindParam(':productId', $prodID);
                    $stmt->bindParam(':quantity', $quantity);
                    $stmt->bindParam(':price', $price);
                    $stmt->bindParam(':variationDetails', $variationDetails);
    
                    if ($stmt->execute()) {
                        echo "Ürün sepete başarıyla eklendi!";
                    } else {
                        http_response_code(400);        
                        header('Content-Type: application/json'); 
                        echo json_encode([
                            "status" => "error",
                            "message" => "Ürün sepete eklenemedi."
                        ]);
                    }
                } catch (PDOException $e) {
                    http_response_code(400);        
                    header('Content-Type: application/json'); 
                    echo json_encode([
                        "status" => "error",
                        "message" => $e->getMessage()
                    ]);
                }
                echo "ürün başarıyla eklendi";
            } else {
                http_response_code(400);        
                header('Content-Type: application/json'); 
                echo json_encode([
                    "status" => "error",
                    "message" => "Eksik bilgi gönderildi."
                ]);
            }
            break;

        default:
            http_response_code(400);        
            header('Content-Type: application/json'); 
            echo json_encode([
                "status" => "error",
                "message" => "Geçersiz işlem."
            ]);
    }
} else {
    http_response_code(400);        
    header('Content-Type: application/json'); 
    echo json_encode([
        "status" => "error",
        "message" => "Geçersiz istek yöntemi."
    ]);
}
?>
