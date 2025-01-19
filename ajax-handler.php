<?php
include "Configuration/Connection.php";
ob_start();
session_start();

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
            $price = $productPrice;
            $userId = $_SESSION["userID"];

            $getBasket = $conn->prepare("
                SELECT 
                basket.*, 
                p.Id as ProductId,
                p.Name as ProductName, 
                p.Description as ProductDescription, 
                p.Image as ProductImage,
                p.Price as ProductPrice, 
                p.SalePrice as ProductSalePrice
                FROM 
                    basket
                INNER JOIN 
                    product p ON basket.ProductId = p.Id
                WHERE 
                    basket.UserId = :userId and basket.ProductId = :productId and basket.VariationDetails = :variationDetail and basket.Status=1;");
    
                $getBasket->execute([
                    'userId' => $userId,
                    'productId' => $productID,
                    'variationDetail' => $variationDetails
                ]);
            $getBasket = $getBasket->fetch(PDO::FETCH_ASSOC); 
            if($getBasket){
                $newQuantity = $getBasket['Quantity'] + $quantity;

                $updateBasket = $conn->prepare("
                    UPDATE basket 
                    SET Quantity = :quantity 
                    WHERE Id = :basketId;
                ");
            
                $updateBasket->execute([
                    'quantity' => $newQuantity,
                    'basketId' => $getBasket['Id']
                ]);
                echo json_encode([
                    "status" => "success",
                    "message" => "Sepet güncellendi."
                ]);
                exit;
            }
            

            if (!empty($size) && !empty($color) && !empty($quantity) && !empty($prodID)) {
                try {
                    $stmt = $conn->prepare("
                        INSERT INTO basket (ProductId, Quantity, Price, VariationDetails, UserId,Status, CreatedAt) 
                        VALUES (:productId, :quantity, :price, :variationDetails, :userid,:status, NOW())
                    ");
                    $status = 1;
                    $stmt->bindParam(':productId', $prodID);
                    $stmt->bindParam(':quantity', $quantity);
                    $stmt->bindParam(':price', $price);
                    $stmt->bindParam(':variationDetails', $variationDetails);
                    $stmt->bindParam(':userid', $userId);
                    $stmt->bindParam(':status', $status);
            
                    if ($stmt->execute()) {
                        echo json_encode([
                            "status" => "success",
                            "message" => "Ürün sepete eklendi."
                        ]);                    } else {
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
            } else {
                http_response_code(400);        
                header('Content-Type: application/json'); 
                echo json_encode([
                    "status" => "error",
                    "message" => "Eksik bilgi gönderildi."
                ]);
            }
            break;

        case 'updateToCart':
            $quantity = $_POST['quantity'] ?? '';
            $basketID = $_POST['basketId'] ?? '';
            $prodID = $_POST['prodId'] ?? '';
            
            $stmt = $conn->prepare("SELECT * FROM product WHERE Id = :prodID");
            $stmt->bindParam(':prodID', $prodID, PDO::PARAM_INT);
            $stmt->execute();
            $urun = $stmt->fetch();            
            if($urun['SalePrice'] != 0){
                $productPrice = $urun['SalePrice'];
            }else{
                $productPrice = $urun['Price'];
            }

            $basket = $conn->query("SELECT * FROM basket WHERE Id = $basketID")->fetch();
            $quantity = $basket['Quantity'] + $quantity;
            if ($quantity < 1) {
                http_response_code(400);
                header(header: 'Content-Type: application/json; charset=utf-8');
                echo json_encode([
                    "status" => "error",
                    "message" => "Adet değeri 1 den küçük olamaz"
                ], JSON_UNESCAPED_UNICODE);
                ob_end_flush(); 
                exit;
            }
            
            $price = $productPrice;

            $sql = "UPDATE basket SET Quantity = :quantity, Price = :price WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':quantity', $quantity);
            $stmt->bindParam(':price', $price);
            $stmt->bindParam(':id', $basketID);

            $userId = $_SESSION["userID"];
            if ($stmt->execute()) {
                $GeneralBasket = $conn->prepare("SELECT * FROM basket WHERE UserId = :userId AND Status = 1");
                $GeneralBasket->bindParam(':userId', $userId, PDO::PARAM_INT);
                $GeneralBasket->execute();
                $GeneralBasket = $GeneralBasket->fetchAll(PDO::FETCH_ASSOC);
                
                $generalTotalPrice = 0;
                foreach($GeneralBasket as $basket){
                    $generalTotalPrice += $basket['Price'] * $basket["Quantity"];
                }
                echo json_encode([
                    "status" => "success",
                    "message" => "Kayıt başarıyla güncellendi.",
                    "price" => $price * $quantity,
                    "basketId" => $basketID,
                    "totalPriceGeneral" => $generalTotalPrice
                ]);
            } else {
                http_response_code(400);        
                header('Content-Type: application/json'); 
                echo json_encode([
                    "status" => "error",
                    "message" => "Kayıt güncellenirken bir hata oluştu."
                ]);
            }

            break;

        case 'orderCreate':
                $firstName = $_POST['name'];
                $lastName = $_POST['lastName'];
                $country = $_POST['country'];
                $city = $_POST['city'];
                $district = $_POST['district'];
                $Neighboord = $_POST['Neighboord'];
                $address1 = $_POST['address'];
                $phone = $_POST['phone'];
                $email = $_POST['email'];
                $orderNotes = $_POST['orderNotes'];
                $totalPriceGeneral = $_POST['totalPriceGeneral'];
            
                if (empty($firstName) || empty($lastName) || empty($email) || empty($country) || empty($city) || empty($district) || empty($Neighboord) || empty($address1) || empty($phone) || empty($totalPriceGeneral)) {
                    http_response_code(400);        
                    header('Content-Type: application/json'); 
                    echo json_encode([
                        "status" => "error",
                        "message" => "Lütfen tüm gerekli alanları doldurun."
                    ]);
                }
            
                try {
                    $query = $conn->prepare("
                        INSERT INTO orders 
                        (CustomerName, CustomerSurname, Country, City, District, Neighborhood, Address, CustomerPhone, CustomerMail, OrderNote, OrderTime, TotalPrice) 
                        VALUES 
                        (:firstName, :lastName, :country, :city, :district, :Neighboord, :address1, :phone, :email, :orderNotes, NOW(), :totalPriceGeneral)
                    ");
            
                    $query->execute([
                        'firstName' => $firstName,
                        'lastName' => $lastName,
                        'country' => $country,
                        'city' => $city,
                        'district' => $district,
                        'Neighboord' => $Neighboord,
                        'address1' => $address1,
                        'phone' => $phone,
                        'email' => $email,
                        'orderNotes' => $orderNotes,
                        'totalPriceGeneral' => $totalPriceGeneral
                    ]);
            
                    if($query){
                         session_start();
                         $_SESSION['totalprice'] = $totalPriceGeneral;
                         header('Location: http://localhost/urun/shopier/shopier.php');
                         exit;
                   }else{
                    http_response_code(400);        
                    header('Content-Type: application/json'); 
                    echo json_encode([
                        "status" => "error",
                        "message" => "Sipariş oluşturulurken bir hata oluştu."
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
            break;

            case "registerUser":
                $firstName = $_POST['Name'] ?? '';
                $lastName = $_POST['Surname'] ?? '';
                $email = $_POST['Email'] ?? '';
                $userName = $_POST['UserName'] ?? '';
                $password = $_POST['Password'] ?? '';
                $rePassword = $_POST['rePassword'] ?? '';
            
                if (empty($firstName) || empty($lastName) || empty($email) || empty($userName) || empty($password) || empty($rePassword)) {
                    http_response_code(400);        
                    header('Content-Type: application/json'); 
                    echo json_encode([
                        "status" => "error",
                        "message" => "Eksik alanları doldurunuz!!"
                    ]); 
                    exit;
                }
                    $isExistUser = $conn->prepare("
                    SELECT 1 FROM user 
                    WHERE UserName = :username OR Email = :email 
                    LIMIT 1
                    ");
                    $isExistUser->execute([
                        'username' => $userName,
                        'email' => $email
                    ]);
                    
                    if ($isExistUser->rowCount() > 0) {
                        http_response_code(400);        
                        header('Content-Type: application/json'); 
                        echo json_encode([
                            "status" => "error",
                            "message" => "Bu kullanıcı adı veya e-posta zaten mevcut"
                        ]); 
                        exit;
            }
            
                
                if ($password !== $rePassword) {
                    http_response_code(400);        
                    header('Content-Type: application/json'); 
                    echo json_encode([
                        "status" => "error",
                        "message" => "şifreler uyuşmuyor"
                    ]);
                    exit;
                }
            
                try {
                    $query = $conn->prepare("
                        INSERT INTO user (Name, Surname, Email, UserName, Password, Status, CreatedDate) 
                        VALUES (:firstName, :lastName, :email, :userName, :password, :status, NOW())
                    ");
            
                    $query->execute([
                        ':firstName' => $firstName,
                        ':lastName' => $lastName,
                        ':email' => $email,
                        ':userName' => $userName,
                        ':password' => password_hash($password, PASSWORD_DEFAULT),
                        ':status' => 1
                    ]);
            
                    http_response_code(response_code: 200);        
                    header('Content-Type: application/json'); 
                    echo json_encode([
                        "status" => "success",
                        "message" => "Kayıt işlemi başarılı"
                    ]);
                    exit;
                } catch (PDOException $e) {
                        http_response_code(400);        
                        header('Content-Type: application/json'); 
                        echo json_encode([
                            "status" => "error",
                            "message" => "sistem hatası!!"
                        ]);
                    }
                exit;
            
            case "LoginUser":
                $usernamelg = trim($_POST['LoginUsername'] ?? '');
                $passwordlg = trim($_POST['LoginPassword'] ?? '');
                if (empty($usernamelg) || empty($passwordlg)) {
                    http_response_code(400);        
                    header('Content-Type: application/json'); 
                    echo json_encode([
                        "status" => "error",
                        "message" => "kullanıcı adı veya şifre boş olamaz!!"
                    ]);
                    exit;
                }

                $queryLogin = "SELECT * FROM user WHERE UserName = :username";
                $stmtLogin = $conn->prepare($queryLogin);
                $stmtLogin->bindParam(':username', $usernamelg, PDO::PARAM_STR);
                $stmtLogin->execute();
                $userLogin = $stmtLogin->fetch(PDO::FETCH_ASSOC);
                if (!$userLogin) {
                    http_response_code(400);        
                    header('Content-Type: application/json'); 
                    echo json_encode([
                        "status" => "error",
                        "message" => "kullanıcı adı veya şifre yanlış!!"
                    ]);
                    exit;
                }

                $loginIsSuccess = password_verify($passwordlg, $userLogin['Password']);
                if ($loginIsSuccess) {
                    $_SESSION["baslangic_zamani"] = time();
                    $_SESSION["sessionTime"] = 10000;
                    $_SESSION["userID"] = $userLogin["Id"];
                    echo json_encode([
                        "status" => "success",
                        "message" => "giriş başarılı"
                    ]);
                    exit;
                }  

            case "deleteBasket":
                $basketId = $_POST["Id"];
                $sqlUpdateBasket = "UPDATE Basket SET Status = 0 WHERE Id = :basketId";
                $stmtUpdateBasket = $conn->prepare($sqlUpdateBasket);
                $stmtUpdateBasket->bindParam(':basketId', $basketId, PDO::PARAM_INT);
                $stmtUpdateBasket->execute();

                if($query){
                    echo json_encode([
                        "status" => "success",
                        "message" => "ürün silindi"
                    ]);
                    exit;
                }

            case "AddToCartFastly":
                try{
                    $productId = $_POST["productId"];
                    $isExistVariationSql = "select * from variation
                    WHERE ProductId = :productId";
                    $isExistVariationObj = $conn->prepare($isExistVariationSql);
                    $isExistVariationObj->bindParam(':productId', $productId, PDO::PARAM_INT);
                    $isExistVariationObj->execute();
                    $isExistVariationObj = $isExistVariationObj->fetch(PDO::FETCH_ASSOC);
                    if($isExistVariationObj){
                        http_response_code(200);        
                        echo json_encode([
                            "status" => "success",
                            "message" => "Lütfen varyasyon seçiniz"
                        ]);
                        exit;
                    }
                }
                catch(PDOException $e){
                    http_response_code(400);        
                    echo json_encode([
                        "status" => "error",
                        "message" => "ürün sepete eklenemedi"
                    ]);
                    exit;
                }


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
