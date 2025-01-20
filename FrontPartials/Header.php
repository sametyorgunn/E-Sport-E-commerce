<?php
require 'Configuration/connection.php'; // Üst dizindeki dosyayı dahil et

session_start();
ob_start();

$userID = isset($_SESSION["userID"]);
  try {
	$query = $conn->prepare("
	 SELECT 
        bas.Id AS BasketId,
        bas.Quantity,
        bas.Price AS BasketPrice,
        bas.VariationDetails,
        p.Name AS ProductName,
        p.Price AS ProductPrice,
        p.SalePrice,
        p.image AS ProductImage
	FROM basket as bas
	INNER JOIN Product p ON bas.ProductId = p.Id
	WHERE bas.UserId = :userId;") ;

	$query->execute(['userId' => $userID]);
	$basket = $query->fetchAll(PDO::FETCH_ASSOC); 
	$rowCount = $query->rowCount();


} catch (PDOException $e) {
	echo "Sorgu hatası: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">	
		<title>e sport</title>	
		<meta name="keywords" content="HTML5 Template" />
		<meta name="description" content="Porto - Responsive HTML5 Template">
		<meta name="author" content="okler.net">
		<link rel="shortcut icon" href="assets/img/favicon.ico" type="image/x-icon" />
		<link rel="apple-touch-icon" href="img/apple-touch-icon.png">
		<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1.0, shrink-to-fit=no">
		<?php require "FrontPartials/_Css.php"; ?>
	</head>
	<body data-plugin-page-transition>
		<div class="body">
			<div class="notice-top-bar bg-primary" data-sticky-start-at="180">
				<button class="hamburguer-btn hamburguer-btn-light notice-top-bar-close m-0 active" data-set-active="false">
					<span class="close">
						<span></span>
						<span></span>
					</span>
				</button>
			</div>
            <header id="header" data-plugin-options="{'stickyEnabled': true, 'stickyEnableOnBoxed': true, 'stickyEnableOnMobile': false, 'stickyStartAt': 135, 'stickySetTop': '-135px', 'stickyChangeLogo': true}">
				<div class="header-body header-body-bottom-border-fixed box-shadow-none border-top-0">
					<div class="header-container container">
						<div class="header-row py-2">
							<div class="header-column w-100">
								<div class="header-row justify-content-between">
									<div class="header-logo z-index-2 col-lg-2 px-0">
										<a href="/urun">
											<!-- <img alt="Porto" width="100" height="48" data-sticky-width="82" data-sticky-height="40" data-sticky-top="84" src="assets/img/logo.png"> -->
											<img alt="Porto" width="100" height="100" data-sticky-width="82" data-sticky-height="40" data-sticky-top="84" src="assets/img/logo.png">
										</a>
									</div>
									<div class="header-nav-features header-nav-features-no-border col-lg-5 col-xl-6 px-0 ms-0">
										<div class="header-nav-feature ps-lg-5 pe-lg-4">
											<form role="search" action="/urun/index" method="get">
												<div class="search-with-select">
													<a href="#" class="mobile-search-toggle-btn me-2" data-porto-toggle-class="open">
														<i class="icons icon-magnifier text-color-dark text-color-hover-primary"></i>
													</a>
													<div class="search-form-wrapper input-group">
														<input class="form-control text-1" id="headerSearch" name="q" type="search" value="" placeholder="arama...">
														<div class="search-form-select-wrapper">
															<!-- <div class="custom-select-1">
																<select name="category" class="form-control form-select">
																	<option value="all" selected>Kategoriler</option>
																	<option value="fashion">Sweatshirt</option>
																	<option value="electronics">Tshirt</option>
																	<option value="homegarden">Çorap</option>
																</select>
															</div> -->
															<button class="btn" name="searchBtn" type="submit">
																<i class="icons icon-magnifier header-nav-top-icon text-color-dark"></i>
															</button>
														</div>
													</div>
												</div>
											</form>
										</div>
									</div>
									<div class="d-flex col-auto col-lg-2 pe-0 ps-0 ps-xl-3">
										<ul class="header-extra-info">
										<li class="ms-0 ms-xl-4">
											<div class="header-extra-info-icon">
												<a href="/urun/profile" class="text-decoration-none text-color-dark text-color-hover-primary text-2">
													<i class="icons icon-user"></i>
												</a>
											</div>
										</li>
										</ul>
										<!-- <div class="header-nav-features ps-0 ms-1">
											<div class="header-nav-feature header-nav-features-cart header-nav-features-cart-big d-inline-flex top-2 ms-2">
												<a href="/urun/basket.php" id="cart-link" class="header-nav-features-toggle">
													<img src="assets/img/icons/icon-cart-big.svg" height="30" alt="" class="header-nav-top-icon-img">
													<span class="cart-info">
														<span id="basketCount" class="cart-qty"><?php echo $rowCount ?></span>
													</span>
												</a>
											</div>
										</div> -->

										<div class="header-nav-features ps-0 ms-1">
											<div class="header-nav-feature header-nav-features-cart header-nav-features-cart-big d-inline-flex top-2 ms-2">
												<a href="#" class="header-nav-features-toggle">
												<img src="assets/img/icons/icon-cart-big.svg" height="30" alt="" class="header-nav-top-icon-img">
												<span class="cart-info">
														<span id="basketCount" class="cart-qty"><?php echo $rowCount ?></span>
													</span>
												</a>
												<div class="header-nav-features-dropdown" id="headerTopCartDropdown">
												<?php if ($rowCount > 0 && $basket != null){ ?>
												<ol class="mini-products-list">
													<?php foreach ($basket as $row): ?>
														<li class="item" id="<?php echo htmlspecialchars($row['BasketId']); ?>">
															<a href="#" title="<?php echo htmlspecialchars($row['ProductName']); ?>" class="product-image">
																<img src="<?php echo htmlspecialchars($row['ProductImage']); ?>" alt="<?php echo htmlspecialchars($row['ProductName']); ?>">
															</a>
															<div class="product-details">
																<p class="product-name">
																	<a href="#"><?php echo htmlspecialchars($row['ProductName']); ?></a>
																</p>
																<p class="qty-price">
																	<?php echo htmlspecialchars($row['Quantity']); ?>x 
																	<span class="price"><?php echo htmlspecialchars($row['BasketPrice']); ?>₺</span>
																</p>
																<a href="#" onclick="deleteProduct(<?php echo htmlspecialchars($row['BasketId']); ?>)" title="Ürünü Sil" class="btn-remove">
																	<i class="fas fa-times"></i>
																</a>
															</div>
														</li><br>
													<?php endforeach; ?>
												</ol>
												<?php } else{ ?>
													<li class="item">Sepetinizde ürün bulunmamaktadır.</li>
												<?php }; ?>

												<?php if($basket !=null){
													$total = 0;
													foreach ($basket as $row){ 
														$total += $row["Quantity"] * $row["BasketPrice"];
												 }} ?>
													<div class="totals">
														<span class="label">Toplam:</span>
														<span class="price-total"><span class="price"><?php  echo isset($total);  ?>₺</span></span>
													</div>
													<div class="actions">
														<a class="btn btn-dark" href="/Urun/Basket">Sepete Git</a>
														<!-- <a class="btn btn-primary" href="#">Checkout</a> -->
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="header-column justify-content-end">
								<div class="header-row">
								</div>
							</div>
						</div>
					</div>
				</div>
			</header>