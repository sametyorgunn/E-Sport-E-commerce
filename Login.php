<?php include "FrontPartials/Header.php"; ?>
<div role="main" class="main shop py-4">
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-12 col-lg-5 mb-5 mb-lg-0">
                <h2 class="font-weight-bold text-5 mb-0">Giriş</h2>
                <!-- <form action="" id="frmSignIn" method="post" class="needs-validation"> -->
                    <div class="row">
                        <div class="form-group col">
                            <label class="form-label text-color-dark text-3">Mail adresi: <span class="text-color-danger">*</span></label>
                            <input type="text" value="" id="LoginUsername" class="form-control form-control-lg text-4" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col">
                            <label class="form-label text-color-dark text-3">Şifre: <span class="text-color-danger">*</span></label>
                            <input type="password" value="" id="LoginPassword" class="form-control form-control-lg text-4" required>
                        </div>
                    </div>
                    <div class="row justify-content-between">
                        <div class="form-group col-md-auto">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="rememberme">
                                <label class="form-label custom-control-label cur-pointer text-2" for="rememberme">Beni Hatırla</label>
                            </div>
                        </div>
                        <div class="form-group col-md-auto">
                            <a class="text-decoration-none text-color-dark text-color-hover-primary font-weight-semibold text-2" href="#">Şifremi Unuttum?</a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col">
                            <button type="submit" id="loginBtn" class="btn btn-dark btn-modern w-100 text-uppercase rounded-0 font-weight-bold text-3 py-3" data-loading-text="Loading...">Giriş</button>
                            <div class="divider">
                                <span class="bg-light px-4 position-absolute left-50pct top-50pct transform3dxy-n50">Hesabınız Yokmu?</span>
                            </div>
                            <a href="/Urun/Register" class="btn btn-dark-scale-2 btn-modern w-100 text-transform-none rounded-0 font-weight-bold align-items-center d-inline-flex justify-content-center text-3 py-3" data-loading-text="Loading..."> Kayıt Ol</a>
                        </div>
                    </div>
                <!-- </form> -->
            </div>
        </div>
    </div>
</div>
<?php include "FrontPartials/Footer.php"; ?>
