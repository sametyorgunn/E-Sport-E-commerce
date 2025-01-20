
<?php include "FrontPartials/Header.php"; 
try {
    $query = $conn->query("
    SELECT 
    p.Id,
    p.Name AS ProductName,
    p.Description,
    p.Price,
    p.saleprice,
	p.image,
    c.Name AS CategoryName,
    GROUP_CONCAT(CONCAT(v.VariationName, ': ', vo.OptionValue) SEPARATOR ', ') AS Variations
FROM 
    Product p
LEFT JOIN 
    Category c ON p.CategoryId = c.Id
LEFT JOIN 
    variation v ON p.Id = v.ProductId
LEFT JOIN 
    variationoption vo ON v.Id = vo.VariationId
GROUP BY 
    p.Id, p.Name, p.Description, p.Price, c.Name;");

    $products = $query->fetchAll(PDO::FETCH_ASSOC); 
} catch (PDOException $e) {
    echo "Sorgu hatası: " . $e->getMessage();
}

if (isset($_GET["searchBtn"])) {
    $search = "%" . $_GET["q"] . "%";
    try {
        $stmt = $conn->prepare("
            SELECT 
                p.Id,
                p.Name AS ProductName,
                p.Description,
                p.Price,
                p.saleprice,
                p.image,
                c.Name AS CategoryName,
                GROUP_CONCAT(CONCAT(v.VariationName, ': ', vo.OptionValue) SEPARATOR ', ') AS Variations
            FROM 
                Product p
            LEFT JOIN 
                Category c ON p.CategoryId = c.Id
            LEFT JOIN 
                variation v ON p.Id = v.ProductId
            LEFT JOIN 
                variationoption vo ON v.Id = vo.VariationId
            WHERE 
                p.Name LIKE :search OR p.Description LIKE :search
            GROUP BY 
                p.Id, p.Name, p.Description, p.Price, c.Name
        ");

        $stmt->bindParam(':search', $search, PDO::PARAM_STR);
        $stmt->execute();

        $products = $stmt->fetchAll(PDO::FETCH_ASSOC); 
    } catch (PDOException $e) {
        echo "Sorgu hatası: " . $e->getMessage();
    }
}
?>
	<div role="main" class="main shop pt-4">
	<div class="owl-carousel-wrapper position-relative" style="height: 670px">
					<div class="owl-carousel-loader">
						<div class="bounce-loader">
							<div class="bounce1"></div>
							<div class="bounce2"></div>
							<div class="bounce3"></div>
						</div>
					</div>
					<div class="owl-carousel dots-inside dots-horizontal-center show-dots-hover nav-inside nav-inside-plus nav-dark nav-md nav-font-size-md show-nav-hover mb-0" data-plugin-options="{'responsive': {'0': {'items': 1}, '479': {'items': 1}, '768': {'items': 1}, '979': {'items': 1}, '1199': {'items': 1}}, 'loop': false, 'autoHeight': false, 'margin': 0, 'dots': true, 'dotsVerticalOffset': '-75px', 'nav': true, 'animateIn': 'fadeIn', 'animateOut': 'fadeOut', 'mouseDrag': false, 'touchDrag': false, 'pullDrag': false, 'autoplay': true, 'autoplayTimeout': 9000, 'autoplayHoverPause': true, 'rewind': true}">

						<div class="position-relative" data-dynamic-height="['670px','670px','670px','550px','500px']" style="background-image: url(assets/img/slider2.png); background-size: cover; background-position: center; height: 670px;">
							<div class="position-absolute top-0 right-0 w-50pct w-lg-auto">
								<img src="img/slides/slide-devices.jpg" class="w-100 appear-animation" data-appear-animation="fadeInLeftDownShorter" data-appear-animation-delay="500" alt="">
							</div>
							<div class="position-absolute top-50pct left-0 transform3dy-n50 w-50pct w-lg-auto">
								<img src="img/slides/slide-laptop.jpg" class="w-100 appear-animation" data-appear-animation="fadeInRightDownShorter" data-appear-animation-delay="1000" alt="">
							</div>
							<div class="position-absolute transform3dy-n50">
								<img src="img/slides/slide-parallax-porto-symbol.png" class="w-75 appear-animation" data-appear-animation="fadeInRightDownShorter" data-appear-animation-delay="1500" alt="" style="top: 20%; left: -20%;" data-plugin-options="{'forceAnimation': true}">
							</div>

							<div class="container position-relative z-index-1 h-100">
								<div class="d-flex flex-column align-items-center justify-content-center h-100">
									<h3 class="position-relative text-color-light text-5 line-height-5 font-weight-medium px-4 mb-2 appear-animation" data-appear-animation="fadeInDownShorter" data-plugin-options="{'minWindowWidth': 0}">
										<span class="position-absolute right-100pct top-50pct transform3dy-n50 opacity-3">
											<img src="img/slides/slide-title-border.png" class="w-auto appear-animation" data-appear-animation="fadeInLeftShorter" data-appear-animation-delay="250" data-plugin-options="{'minWindowWidth': 0}" alt="" />
										</span>
										Yeni Ürünleri <span class="position-relative"><span class="position-absolute left-50pct transform3dx-n50 top-0 mt-4"><img src="img/slides/slide-blue-line.png" class="w-auto appear-animation" data-appear-animation="fadeInLeftShorterPlus" data-appear-animation-delay="1000" data-plugin-options="{'minWindowWidth': 0}" alt="" /></span></span>
										<span class="position-absolute left-100pct top-50pct transform3dy-n50 opacity-3">
											<img src="img/slides/slide-title-border.png" class="w-auto appear-animation" data-appear-animation="fadeInRightShorter" data-appear-animation-delay="250" data-plugin-options="{'minWindowWidth': 0}" alt="" />
										</span>
									</h3>
									<h1 class="text-color-light font-weight-extra-bold text-12 mb-3 appear-animation" data-appear-animation="blurIn" data-appear-animation-delay="500" data-plugin-options="{'minWindowWidth': 0}">Kaçırma?</h1>
									<button id="scrollToProducts" class="btn btn-light"><b>Şimdi Alışverişe Başla</b></button>
								</div>
							</div>
						</div>

						<div class="position-relative overlay overlay-show overlay-op-8" data-dynamic-height="['670px','670px','670px','550px','500px']" style="background-image: url(assets/img/slider2.png); background-size: cover; background-position: center; height: 670px;">
							<div class="container position-relative z-index-3 h-100">
								<div class="row justify-content-center align-items-center h-100">
									<div class="col-lg-6">
										<div class="d-flex flex-column align-items-center">
											<h3 class="position-relative text-color-light text-5 line-height-5 font-weight-medium px-4 mb-2 appear-animation" data-appear-animation="fadeInDownShorter" data-plugin-options="{'minWindowWidth': 0}">
												<span class="position-absolute right-100pct top-50pct transform3dy-n50 opacity-3">
													<img src="img/slides/slide-title-border.png" class="w-auto appear-animation" data-appear-animation="fadeInLeftShorter" data-appear-animation-delay="250" data-plugin-options="{'minWindowWidth': 0}" alt="" />
												</span>
												E-sport 
												<span class="position-absolute left-100pct top-50pct transform3dy-n50 opacity-3">
													<img src="img/slides/slide-title-border.png" class="w-auto appear-animation" data-appear-animation="fadeInRightShorter" data-appear-animation-delay="250" data-plugin-options="{'minWindowWidth': 0}" alt="" />
												</span>
											</h3>
											<h2 class="text-color-light font-weight-extra-bold text-12 mb-3 appear-animation" data-appear-animation="blurIn" data-appear-animation-delay="500" data-plugin-options="{'minWindowWidth': 0}">En iyi tasarımlarla</h2>
										</div>
									</div>
								</div>
							</div>
						</div>						
					</div>
				</div>
				<!-- <section class="section section-height-3 bg-color-grey m-0 border-0">
				<div class="container py-4 text-center">
					<h4 class="mb-3 text-4 text-uppercase">Related <strong class="font-weight-extra-bold">Projects</strong></h4>
					<div class="row text-center">
					Sizin için hazırladık!
					BBL Shop üzerinde, birçok ihtiyacınızı özel tasarımlı ürünlerle birlikte bulabilirsiniz.
					</div>
				</div>
			</section> -->
			<br>
			<section class="section section-height-3 bg-color-grey m-0 border-0">
				<div class="container py-4">
					<div class="row">
					<div class="col-lg-12">
							<div class="masonry-loader masonry-loader-showing" id="products">
								<div class="row products product-thumb-info-list" data-plugin-masonry data-plugin-options="{'layoutMode': 'fitRows'}">
								<?php foreach ($products as $product): ?>
                                    <div class="col-sm-6 col-lg-3">
										<div class="product mb-0" style="border-radius: 10px; overflow: hidden; border: 1px solid #ddd;">
											<div class="product-thumb-info border-0 mb-3">
												<div class="product-thumb-info-badges-wrapper">
                                                    <span class="badge badge-ecommerce badge-success">Yeni</span>
												</div>
												<div class="addtocart-btn-wrapper">
													<a href="" class="text-decoration-none addtocart-btn" data-product-id="<?php echo $product['Id']; ?>" title="Sepete Ekle">
														<i class="icons icon-bag"></i>
													</a>
												</div>
												<a href="/urun/productDetail.php?id=<?php echo $product['Id']; ?>" class="quick-view text-uppercase font-weight-semibold text-2">
													ürünü gör
												</a>
												<a href="/urun/productDetail.php?id=<?php echo $product['Id']; ?>">
													<div class="product-thumb-info-image">
														<img alt="" class="img-fluid" src="<?php echo $product['image'] ?>">
													</div>
												</a>
											</div>
											<div class="d-flex justify-content-between">
												<div>
													<a href="#" class="d-block text-uppercase text-decoration-none text-color-default text-color-hover-primary line-height-1 text-0 mb-1"><?php echo $product['CategoryName']; ?></a>
													<h3 class="text-3-5 font-weight-medium font-alternative text-transform-none line-height-3 mb-0"><a href="/urun/productDetail.php?id=<?php echo $product['Id']; ?>" class="text-color-dark text-color-hover-primary"><?php echo $product['ProductName']; ?></a></h3>
												</div>
												<!-- <a href="#" class="text-decoration-none text-color-default text-color-hover-dark text-4"><i class="far fa-heart"></i></a> -->
											</div>
											<div title="Rated 5 out of 5">
												<input type="text" class="d-none" value="5" title="" data-plugin-star-rating data-plugin-options="{'displayOnly': true, 'color': 'default', 'size':'xs'}">
											</div>
											<p class="price text-5 mb-3">
												<span class="sale text-color-dark font-weight-semi-bold"><?php echo $product['saleprice']; ?>₺</span>
												<span class="amount text-color-danger"><?php echo $product['Price']; ?>₺</span>
											</p>
										</div>
									</div>
                                <?php endforeach; ?>
								</div>
								<div class="row mt-4">
									<div class="col">
										<ul class="pagination float-end">
											<li class="page-item"><a class="page-link" href="#"><i class="fas fa-angle-left"></i></a></li>
											<li class="page-item active"><a class="page-link" href="#">1</a></li>
											<li class="page-item"><a class="page-link" href="#">2</a></li>
											<li class="page-item"><a class="page-link" href="#">3</a></li>
											<li class="page-item"><a class="page-link" href="#"><i class="fas fa-angle-right"></i></a></li>
										</ul>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>				
	<?php include "FrontPartials/Footer.php"; ?>