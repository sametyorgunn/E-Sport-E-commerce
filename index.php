
<?php include "FrontPartials/Header.php"; ?>

<?php
try {
    $query = $conn->query("
    SELECT 
    p.Id,
    p.Name AS ProductName,
    p.Description,
    p.Price,
    p.saleprice,
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
?>

		<div role="main" class="main shop pt-4">
				<div class="container">
					<div class="row">
						<div class="col-lg-12">
							<div class="masonry-loader masonry-loader-showing">
								<div class="row products product-thumb-info-list" data-plugin-masonry data-plugin-options="{'layoutMode': 'fitRows'}">
								<?php foreach ($products as $product): ?>
                                    <div class="col-sm-6 col-lg-4">
										<div class="product mb-0">
											<div class="product-thumb-info border-0 mb-3">
												<div class="product-thumb-info-badges-wrapper">
                                                    <span class="badge badge-ecommerce badge-success">NEW</span>
												</div>
												<div class="addtocart-btn-wrapper">
													<a href="shop-cart.html" class="text-decoration-none addtocart-btn" title="Add to Cart">
														<i class="icons icon-bag"></i>
													</a>
												</div>
												<a href="/urun/productDetail.php?id=<?php echo $product['Id']; ?>" class="quick-view text-uppercase font-weight-semibold text-2">
													ürünü gör
												</a>
												<a href="/urun/productDetail.php?id=<?php echo $product['Id']; ?>">
													<div class="product-thumb-info-image">
														<img alt="" class="img-fluid" src="https://static.ticimax.cloud/cdn-cgi/image/width=442,quality=85/37512/uploads/urunresimleri/buyuk/trendiz-erkek-antrasit-basic-sweatshir--49bc-.jpg">
													</div>
												</a>
											</div>
											<div class="d-flex justify-content-between">
												<div>
													<a href="#" class="d-block text-uppercase text-decoration-none text-color-default text-color-hover-primary line-height-1 text-0 mb-1"><?php echo $product['CategoryName']; ?></a>
													<h3 class="text-3-5 font-weight-medium font-alternative text-transform-none line-height-3 mb-0"><a href="shop-product-sidebar-right.html" class="text-color-dark text-color-hover-primary"><?php echo $product['ProductName']; ?></a></h3>
												</div>
												<a href="#" class="text-decoration-none text-color-default text-color-hover-dark text-4"><i class="far fa-heart"></i></a>
											</div>
											<div title="Rated 5 out of 5">
												<input type="text" class="d-none" value="5" title="" data-plugin-star-rating data-plugin-options="{'displayOnly': true, 'color': 'default', 'size':'xs'}">
											</div>
											<p class="price text-5 mb-3">
												<span class="sale text-color-dark font-weight-semi-bold"><?php echo $product['saleprice']; ?></span>
												<span class="amount"><?php echo $product['Price']; ?></span>
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
			</div>
	<?php include "FrontPartials/Footer.php"; ?>