<?php include "Configuration/connection.php"; ?>
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
		<?php include "FrontPartials/_Css.php"; ?>
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
										<a href="index.html">
											<img alt="Porto" width="100" height="48" data-sticky-width="82" data-sticky-height="40" data-sticky-top="84" src="assets/img/logo-default-slim.png">
										</a>
									</div>
									<div class="header-nav-features header-nav-features-no-border col-lg-5 col-xl-6 px-0 ms-0">
										<div class="header-nav-feature ps-lg-5 pe-lg-4">
											<form role="search" action="page-search-results.html" method="get">
												<div class="search-with-select">
													<a href="#" class="mobile-search-toggle-btn me-2" data-porto-toggle-class="open">
														<i class="icons icon-magnifier text-color-dark text-color-hover-primary"></i>
													</a>
													<div class="search-form-wrapper input-group">
														<input class="form-control text-1" id="headerSearch" name="q" type="search" value="" placeholder="Search...">
														<div class="search-form-select-wrapper">
															<div class="custom-select-1">
																<select name="category" class="form-control form-select">
																	<option value="all" selected>All Categories</option>
																	<option value="fashion">Fashion</option>
																	<option value="electronics">Electronics</option>
																	<option value="homegarden">Home & Garden</option>
																	<option value="motors">Motors</option>
																	<option value="features">Features</option>
																</select>
															</div>
															<button class="btn" type="submit">
																<i class="icons icon-magnifier header-nav-top-icon text-color-dark"></i>
															</button>
														</div>
													</div>
												</div>
											</form>
										</div>
									</div>
									<ul class="header-extra-info col-lg-3 col-xl-2 ps-2 ps-xl-0 ms-lg-3 d-none d-lg-block">
										<li class="d-none d-sm-inline-flex ms-0">
											<div class="header-extra-info-icon ms-lg-4">
												<i class="icons icon-phone text-3 text-color-dark position-relative top-1"></i>
											</div>
											<div class="header-extra-info-text">
												<label class="text-1 font-weight-semibold text-color-default">CALL US NOW</label>
												<strong class="text-4"><a href="tel:+1234567890" class="text-color-hover-primary text-decoration-none">+123 4567 890</a></strong>
											</div>
										</li>
									</ul>
									<div class="d-flex col-auto col-lg-2 pe-0 ps-0 ps-xl-3">
										<ul class="header-extra-info">
											<li class="ms-0 ms-xl-4">
												<div class="header-extra-info-icon">
													<a href="#" class="text-decoration-none text-color-dark text-color-hover-primary text-2">
														<i class="icons icon-user"></i>
													</a>
												</div>
											</li>
											<li class="me-2 ms-3">
												<div class="header-extra-info-icon">
													<a href="#" class="text-decoration-none text-color-dark text-color-hover-primary text-2">
														<i class="icons icon-heart"></i>
													</a>
												</div>
											</li>
										</ul>
										<div class="header-nav-features ps-0 ms-1">
											<div class="header-nav-feature header-nav-features-cart header-nav-features-cart-big d-inline-flex top-2 ms-2">
												<a id="cart-link" class="header-nav-features-toggle">
													<img src="assets/img/icons/icon-cart-big.svg" height="30" alt="" class="header-nav-top-icon-img">
													<span class="cart-info">
														<span class="cart-qty">0</span>
													</span>
												</a>
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
					<div class="header-nav-bar header-nav-bar-top-border bg-light">
						<div class="header-container container">
							<div class="header-row">
								<div class="header-column">
									<div class="header-row justify-content-end">
										<div class="header-nav header-nav-line header-nav-top-line header-nav-top-line-with-border justify-content-start" data-sticky-header-style="{'minResolution': 991}" data-sticky-header-style-active="{'margin-left': '105px'}" data-sticky-header-style-deactive="{'margin-left': '0'}">
											<div class="header-nav-main header-nav-main-square header-nav-main-dropdown-no-borders header-nav-main-effect-3 header-nav-main-sub-effect-1 w-100">
												<nav class="collapse w-100">
												</nav>
											</div>
											<button class="btn header-btn-collapse-nav" data-bs-toggle="collapse" data-bs-target=".header-nav-main nav">
												<i class="fas fa-bars"></i>
											</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</header>