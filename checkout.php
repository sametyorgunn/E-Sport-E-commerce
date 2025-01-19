<?php include "FrontPartials/Header.php"; ?>
<?php 
 require_once 'Core.php';
 $core = new Core();
 $userID = $core ->SessionTimeAndLoginControl();
try {
    $query = $conn->prepare("
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
        basket.UserId = :userId and basket.Status=1;");

    $query->execute(['userId' => $userID]);
    $basket = $query->fetchAll(PDO::FETCH_ASSOC); 

    $generalTotalPrice = 0;
    foreach ($basket as $item) {
        $generalTotalPrice += $item["Quantity"] * (isset($item["ProductSalePrice"]) ? $item["ProductSalePrice"] : $item["ProductPrice"]);
    }
} 
catch (PDOException $e) {
    echo "Sorgu hatası: " . $e->getMessage();
}    

if (isset($_POST["submitOrder"])) {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $country = $_POST['country'];
    $city = $_POST['city'];
    $district = $_POST['district'];
    $Neighboord = $_POST['Neighboord'];
    $address1 = $_POST['address1'];
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
        exit;
    }

    try {
        $query = $conn->prepare("
            INSERT INTO orders 
            (CustomerName, CustomerSurname, Country, City, District, Neighborhood, Address, CustomerPhone, CustomerMail, OrderNote, OrderTime, TotalPrice,isPaymentSuccess,userId) 
            VALUES 
            (:firstName, :lastName, :country, :city, :district, :Neighboord, :address1, :phone, :email, :orderNotes, NOW(), :totalPriceGeneral,:isPaymentSuccess,:userId)
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
            'totalPriceGeneral' => $totalPriceGeneral,
            'isPaymentSuccess' => 0,
            'userId'=> $userID ?? null
        ]);
        if ($query->rowCount() > 0) {
            $lastInsertId = $conn->lastInsertId();
            $queryInsertOrderProduct = $conn->prepare("
            INSERT INTO orderdetail 
            (OrderId, ProductId,OptionCombination, Quantity,Price) 
            VALUES 
            (:OrderId, :ProductId, :OptionCombination,:Quantity,:Price)");
            foreach($basket as $val){
                $queryInsertOrderProduct->execute([
                    'OrderId' => $lastInsertId,
                    'ProductId' => $val['ProductId'],
                    'OptionCombination' => $val['VariationDetails'],
                    'Quantity' => $val['Quantity'],
                    'Price' => (isset($val["ProductSalePrice"]) ? $val["ProductSalePrice"] : $val["ProductPrice"])
                ]);
            }
           
            $sqlUpdateBasket = "UPDATE Basket SET Status = 0 WHERE userId = :userId";
            $stmtUpdateBasket = $conn->prepare($sqlUpdateBasket);
            $stmtUpdateBasket->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmtUpdateBasket->execute();

        }

        if ($query) {
            $_SESSION['orderId'] = $lastInsertId;
            header("Location: /urun/shopier/shopier.php");
            exit;
        } else {
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
}
?>

<div role="main" class="main shop pb-4">
<div class="container">
    <div class="row">
        <div class="col">
            <p>Kuponunuz varmı? <a href="#" class="text-color-dark text-color-hover-primary text-uppercase text-decoration-none font-weight-bold" data-bs-toggle="collapse" data-bs-target=".coupon-form-wrapper">Kodu giriniz</a></p>
        </div>
    </div>
    <div class="row coupon-form-wrapper collapse mb-5">
        <div class="col">
            <div class="card border-width-3 border-radius-0 border-color-hover-dark">
                <div class="card-body">
                    <form role="form" method="post" action="">
                        <div class="d-flex align-items-center">
                            <input type="text" class="form-control h-auto border-radius-0 line-height-1 py-3" name="couponCode" placeholder="Coupon Code" required />
                            <button type="submit" class="btn btn-light btn-modern text-color-dark bg-color-light-scale-2 text-color-hover-light bg-color-hover-primary text-uppercase text-3 font-weight-bold border-0 border-radius-0 ws-nowrap btn-px-4 py-3 ms-2">Uygula</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <form role="form" class="needs-validation" method="post" action="/urun/checkout"> 
        <div class="row">
            <div class="col-lg-7 mb-4 mb-lg-0">

                <h2 class="text-color-dark font-weight-bold text-5-5 mb-3">Şipariş Detayları</h2>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label class="form-label">Adınız <span class="text-color-danger">*</span></label>
                        <input type="text" class="form-control h-auto py-2" id="name" name="firstName" value="" required />
                    </div>
                    <div class="form-group col-md-6">
                        <label class="form-label">Soyadınız <span class="text-color-danger">*</span></label>
                        <input type="text" class="form-control h-auto py-2" id="lastName" name="lastName" value="" required />
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col">
                        <label class="form-label">Ülke <span class="text-color-danger">*</span></label>
                        <input type="text" class="form-control h-auto py-2" id="country" name="country" value="" required />
                    </div>
                </div>   
                <div class="row">
                    <div class="form-group col">
                        <label class="form-label">Şehir <span class="text-color-danger">*</span></label>
                        <input type="text" class="form-control h-auto py-2" id="city" name="city" value="" required />
                    </div>
                </div>   
                <div class="row">
                    <div class="form-group col">
                        <label class="form-label">İlçe <span class="text-color-danger">*</span></label>
                        <input type="text" class="form-control h-auto py-2" id="district" name="district" value="" required />
                    </div>
                </div>  
                <div class="row">
                    <div class="form-group col">
                        <label class="form-label">Mahalle <span class="text-color-danger">*</span></label>
                        <input type="text" class="form-control h-auto py-2" id="Neighboord" name="Neighboord" value="" required />
                    </div>
                </div>  
                <div class="row">
                    <div class="form-group col">
                        <label class="form-label">Adres <span class="text-color-danger">*</span></label>
                        <input type="text" class="form-control h-auto py-2" id="address" name="address1" value="" placeholder="adres bilginiz" required />
                    </div>
                </div>                          
                <div class="row">
                    <div class="form-group col">
                        <label class="form-label">Telefon <span class="text-color-danger">*</span></label>
                        <input type="tel" class="form-control h-auto py-2" id="phone" name="phone" value="" required />
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col">
                        <label class="form-label">Mail adresiniz <span class="text-color-danger">*</span></label>
                        <input type="email" class="form-control h-auto py-2" id="email" name="email" value="" required />
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col">
                        <label class="form-label">Sipariş Notu</label>
                        <textarea class="form-control h-auto py-2" id="orderNotes" name="orderNotes" rows="5" placeholder="Siparişiniz hakkında ek bilgi"></textarea>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 position-relative">
                <div class="card border-width-3 border-radius-0 border-color-hover-dark" data-plugin-sticky data-plugin-options="{'minWidth': 991, 'containerSelector': '.row', 'padding': {'top': 85}}">
                    <div class="card-body">
                        <h4 class="font-weight-bold text-uppercase text-4 mb-3">Sipariş Özeti</h4>
                        <table class="shop_table cart-totals mb-3">
                            <tbody>
                                <tr>
                                    <td colspan="2" class="border-top-0">
                                        <strong class="text-color-dark">Ürünler</strong>
                                    </td>
                                </tr>
                                <?php foreach($basket as $item){ ?>
                                <tr>
                                    <td>
                                        <strong class="d-block text-color-dark line-height-1 font-weight-semibold"><?php echo $item['ProductName']; ?> <span class="product-qty">x<?php echo $item['Quantity']; ?></span></strong>
                                        <span class="text-1"><?php echo $item['VariationDetails']; ?></span>
                                    </td>
                                    <td class="text-end align-top">
                                        <span class="amount font-weight-medium text-color-grey"><?php echo $item['Price']; ?> ₺</span>
                                    </td>
                                </tr>
                                <?php } ?>
                                <input type="hidden" id="totalPriceGeneral" name="totalPriceGeneral" value="<?php echo $generalTotalPrice; ?>">
                                <tr class="total">
                                    <td>
                                        <strong class="text-color-dark text-3-5">Toplam</strong>
                                    </td>
                                    <td class="text-end">
                                        <strong class="text-color-dark"><span class="amount text-color-dark text-5"><?php echo $generalTotalPrice; ?> ₺</span></strong>
                                    </td>
                                </tr>
                                <tr class="payment-methods">
                                </tr>
                                <tr>
                                   <td colspan="2">
                                        Kişisel verileriniz, siparişinizi işlemek, bu web sitesi boyunca deneyiminizi desteklemek ve gizlilik politikamızda açıklanan diğer amaçlar için kullanılacaktır. 
                                   </td>
                                </tr>
                            </tbody>
                        </table>
                        <button name="submitOrder" id="orderCreate" class="btn btn-dark btn-modern w-100 text-uppercase bg-color-hover-primary border-color-hover-primary border-radius-0 text-3 py-3">Şiparişi Tamamla <i class="fas fa-arrow-right ms-2"></i></button>
                    </div>
                </div>
            </div>
        </div>
     </form> 
</div>
</div>
<div id="payment-form-container"></div>
<?php include "FrontPartials/Footer.php"; ?>