<?php include "FrontPartials/Header.php"; ?>
<?php 
    try {
        $query = $conn->prepare("
        SELECT 
        basket.*, 
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
            basket.UserId = :userId;");

        $query->execute(['userId' => 2]);
        $basket = $query->fetchAll(PDO::FETCH_ASSOC); 

    } catch (PDOException $e) {
        echo "Sorgu hatası: " . $e->getMessage();
    }
?>
<div role="main" class="main shop pb-4">
<div class="container">
    <div class="row pb-4 mb-5">
        <div class="col-lg-8 mb-5 mb-lg-0">
            <form method="post" action="">
                <div class="table-responsive">
                    <table class="shop_table cart">
                        <thead>
                            <tr class="text-color-dark">
                                <th class="product-thumbnail" width="15%">
                                    &nbsp;
                                </th>
                                <th class="product-name text-uppercase" width="30%">
                                    Ürün
                                </th>
                                <th class="product-price text-uppercase" width="15%">
                                    Fiyat
                                </th>
                                <th class="product-quantity text-uppercase" width="20%">
                                    Adet
                                </th>
                                <th class="product-subtotal text-uppercase text-end" width="20%">
                                    Toplam
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($basket as $item): ?>
                            <tr class="cart_table_item">
                                <td class="product-thumbnail">
                                    <div class="product-thumbnail-wrapper">
                                        <a href="#" class="product-thumbnail-remove" title="Remove Product">
                                            <i class="fas fa-times"></i>
                                        </a>
                                        <a href="shop-product-sidebar-right.html" class="product-thumbnail-image" title="Photo Camera">
                                            <img width="90" height="90" alt="" class="img-fluid" src="<?php echo $item['ProductImage']; ?>">
                                        </a>
                                    </div>
                                </td>
                                <td class="product-name">
                                    <a href="shop-product-sidebar-right.html" class="font-weight-semi-bold text-color-dark text-color-hover-primary text-decoration-none"><?php echo $item['ProductName']; ?></a>
                                </td>
                                <td class="product-price">
                                    <span class="amount font-weight-medium text-color-grey"><?php echo $item['ProductSalePrice']; ?>₺</span>
                                </td>
                                <td class="product-quantity">
                                    <div class="quantity float-none m-0">
                                        <input type="button" onclick="updateQuantity(<?php echo $item['Id']; ?>, -1)" class="minus text-color-hover-light bg-color-hover-primary border-color-hover-primary" value="-">
                                        <input type="text" onchange="updateQuantity(<?php echo $item['Id']; ?>, this.value)" class="input-text qty text" title="Qty" value="<?php echo $item['Quantity']; ?>" name="quantity" min="1" step="1">
                                        <input type="button" onclick="updateQuantity(<?php echo $item['Id']; ?>, 1)" class="plus text-color-hover-light bg-color-hover-primary border-color-hover-primary" value="+">
                                    </div>
                                </td>
                                <td class="product-subtotal text-end">
                                    <span class="amount text-color-dark font-weight-bold text-4"><?php echo $item['ProductSalePrice'] * $item['Quantity']; ?>₺</span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
        <div class="col-lg-4 position-relative">
            <div class="card border-width-3 border-radius-0 border-color-hover-dark" data-plugin-sticky data-plugin-options="{'minWidth': 991, 'containerSelector': '.row', 'padding': {'top': 85}}">
                <div class="card-body">
                    <h4 class="font-weight-bold text-uppercase text-4 mb-3">Cart Totals</h4>
                    <table class="shop_table cart-totals mb-4">
                        <tbody>
                            <tr class="cart-subtotal">
                                <td class="border-top-0">
                                    <strong class="text-color-dark">Subtotal</strong>
                                </td>
                                <td class="border-top-0 text-end">
                                    <strong><span class="amount font-weight-medium">$431</span></strong>
                                </td>
                            </tr>
                            <tr class="shipping">
                                <td colspan="2">
                                    <strong class="d-block text-color-dark mb-2">Shipping</strong>

                                    <div class="d-flex flex-column">
                                        <label class="d-flex align-items-center text-color-grey mb-0" for="shipping_method1">
                                            <input id="shipping_method1" type="radio" class="me-2" name="shipping_method" value="free" checked />
                                            Free Shipping
                                        </label>
                                        <label class="d-flex align-items-center text-color-grey mb-0" for="shipping_method2">
                                            <input id="shipping_method2" type="radio" class="me-2" name="shipping_method" value="local-pickup" />
                                            Local Pickup
                                        </label>
                                        <label class="d-flex align-items-center text-color-grey mb-0" for="shipping_method3">
                                            <input id="shipping_method3" type="radio" class="me-2" name="shipping_method" value="flat-rate" />
                                            Flat Rate: $5.00
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            <tr class="total">
                                <td>
                                    <strong class="text-color-dark text-3-5">Total</strong>
                                </td>
                                <td class="text-end">
                                    <strong class="text-color-dark"><span class="amount text-color-dark text-5">$431</span></strong>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <a href="shop-checkout.html" class="btn btn-dark btn-modern w-100 text-uppercase bg-color-hover-primary border-color-hover-primary border-radius-0 text-3 py-3">Proceed to Checkout <i class="fas fa-arrow-right ms-2"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>

</div>
<?php include "FrontPartials/Footer.php"; ?>