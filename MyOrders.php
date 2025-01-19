<?php include "FrontPartials/Header.php";
require_once 'Core.php';
$core = new Core();
$userId = $core ->SessionTimeAndLoginControl();
if($userId == 0){
    header('location:/urun/login');
}
if(isset($userId)){
    $userId = intval($userId);
    $sql = "SELECT * FROM user WHERE Id = :userId";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    
    $stmt->execute();
    $user = $stmt->fetch();
}

if(isset($userId)){
    $sqlOrders = "SELECT 
    p.Name as ProductName,
    p.image as ProductImage,
    ord.TotalPrice as TotalPrice,
    od.OptionCombination as Variations,
    od.Quantity as Quantity,
    od.Price as Price,
    ord.isPaymentSuccess,
    ord.OrderTime

    FROM orders AS ord
    INNER JOIN orderdetail od ON ord.Id = od.OrderId
    INNER JOIN product p ON od.ProductId = p.Id
    WHERE userId = :userId";

    $stmtOrder = $conn->prepare($sqlOrders);
    $stmtOrder->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmtOrder->execute();
    $orders = $stmtOrder->fetchAll();
}
?>
<div class="container pt-3 pb-2">
    <div class="row pt-2">
          <?php include "FrontPartials/Profile/SideBar.php"?>
            <div class="col-lg-9 order-1 order-lg-2">
                <div class="tab-pane tab-pane-navigation active" id="formsStyleDefault">
                    <h4 class="mb-3">Siparişlerim</h4>
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <div class="table-responsive">
                                        <table class="table table-hover shop_table cart">
                                            <thead>
                                                <tr class="text-color-dark">
                                                    <th class="product-thumbnail" width="15%">Resim</th>
                                                    <th class="product-name text-uppercase" width="30%">Ürün</th>
                                                    <th class="product-price text-uppercase" width="15%">Fiyat</th>
                                                    <th class="product-quantity text-uppercase" width="20%">Adet</th>
                                                    <th class="product-quantity text-uppercase d-none d-md-table-cell" width="20%">Varyasyon</th>
                                                    <th class="product-quantity text-uppercase" width="20%">Sipariş Durumu</th>
                                                    <th class="product-quantity text-uppercase d-none d-md-table-cell" width="20%">Tarih</th>
                                                    <th class="product-subtotal text-uppercase text-end" width="20%">Toplam</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($orders as $order) { ?>
                                                    <tr class="cart_table_item">
                                                        <td class="product-thumbnail">
                                                            <div class="product-thumbnail-wrapper">
                                                                <a href="" class="product-thumbnail-image" title="">
                                                                    <img width="90" height="90" alt="" class="img-fluid" src="<?php echo $order["ProductImage"] ?>">
                                                                </a>
                                                            </div>
                                                        </td>
                                                        <td class="product-name">
                                                            <a href="#" class="font-weight-semi-bold text-color-dark text-color-hover-primary text-decoration-none"><?php echo $order["ProductName"] ?></a>
                                                        </td>
                                                        <td class="product-price">
                                                            <span class="amount font-weight-medium text-color-grey"><?php echo $order["Price"] ?>₺</span>
                                                        </td>
                                                        <td class="product-quantity">
                                                            <span class="amount font-weight-medium text-color-grey">x<?php echo $order["Quantity"] ?></span>
                                                        </td>
                                                        <td class="product-quantity d-none d-md-table-cell">
                                                            <span class="amount font-weight-medium text-color-grey"><?php echo $order["Variations"] ?></span>
                                                        </td>
                                                        <td class="product-quantity">
                                                            <?php if ($order["isPaymentSuccess"] == 0): ?>
                                                                <span class="badge badge-danger">Ödeme Yapılmadı</span>
                                                            <?php elseif ($order["isPaymentSuccess"] == 1): ?>
                                                                <span class="badge badge-success">Ödeme Yapıldı</span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td class="product-quantity d-none d-md-table-cell">
                                                            <span class="amount font-weight-medium text-color-grey"><?php echo $order["OrderTime"] ?></span>
                                                        </td>
                                                        <td class="product-subtotal text-center">
                                                            <span class="amount text-color-dark font-weight-bold text-4"><?php echo $order["TotalPrice"] ?>₺</span>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
           </div>
    </div>
</div>

<?php include "FrontPartials/Footer.php"; ?>