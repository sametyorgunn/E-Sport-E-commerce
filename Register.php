<?php include "FrontPartials/Header.php"; ?>
<div role="main" class="main shop py-4">
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <h2 class="font-weight-bold text-5 mb-0">Kayıt Ol</h2>
                <form id="registerForm" method="post">
                    <div class="row">
                        <div class="form-group col">
                            <label class="form-label text-color-dark text-3">İsim <span class="text-color-danger">*</span></label>
                            <input type="text" id="Name" name="Name" class="form-control form-control-lg text-4" required>
                        </div>
                        <div class="form-group col">
                            <label class="form-label text-color-dark text-3">Soyisim <span class="text-color-danger">*</span></label>
                            <input type="text" id="Surname" name="Surname" class="form-control form-control-lg text-4" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col">
                            <label class="form-label text-color-dark text-3">Mail <span class="text-color-danger">*</span></label>
                            <input type="email" id="Email" name="Email" class="form-control form-control-lg text-4" required>
                        </div>
                        <div class="form-group col">
                            <label class="form-label text-color-dark text-3">Kullanıcı Adı <span class="text-color-danger">*</span></label>
                            <input type="text" id="UserName" name="UserName" class="form-control form-control-lg text-4" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col">
                            <label class="form-label text-color-dark text-3">Şifre <span class="text-color-danger">*</span></label>
                            <input type="password" id="Password" name="Password" class="form-control form-control-lg text-4" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col">
                            <label class="form-label text-color-dark text-3">Şifre Tekrar <span class="text-color-danger">*</span></label>
                            <input type="password" id="rePassword" name="rePassword" class="form-control form-control-lg text-4" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col">
                            <button type="button" id="submitRegister" class="btn btn-dark btn-modern w-100 text-uppercase rounded-0 font-weight-bold text-3 py-3">Kayıt Ol</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include "FrontPartials/Footer.php"; ?>
