<?php include "FrontPartials/Header.php";
require_once 'Core.php';
$core = new Core();
$userId = $core ->SessionTimeAndLoginControl();
if($userId == 0){
    header('location:/urun/login');
}

if(isset($_POST['updatebtn'])){

    $enteredPassword = $_POST['Password'] ?? null; 
    $newName = $_POST['Name'] ?? null;
    $newSurname = $_POST['Surname'] ?? null;
    $newEmail = $_POST['Email'] ?? null;
    $userName = $_POST['UserName'] ?? null;

if ($userId && $enteredPassword) {
    $sql = "SELECT Password FROM user WHERE Id = :userId";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->execute();

    $user = $stmt->fetch();

    if ($user && password_verify($enteredPassword, $user['Password'])) {
        $updateSql = "UPDATE user SET Name = :name, Surname = :surname, Email = :email, UserName = :username WHERE Id = :userId";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bindParam(':name', $newName, PDO::PARAM_STR);
        $updateStmt->bindParam(':surname', $newSurname, PDO::PARAM_STR);
        $updateStmt->bindParam(':email', $newEmail, PDO::PARAM_STR);
        $updateStmt->bindParam(':username', $userName, PDO::PARAM_STR);
        $updateStmt->bindParam(':userId', $userId, PDO::PARAM_INT);

        if ($updateStmt->execute()) { ?>
            <div id="message" class="alert alert-success">
                    Bilgileriniz başarıyla güncellendi.
            </div>
            <script>
                setTimeout(function() {
                    var message = document.getElementById('message');
                    if (message) {
                        message.style.display = 'none';
                    }
                }, 2000);
            </script>

         <?php } else {?>
                <div id="message" class="alert alert-danger">
                    Bilgileriniz güncellenemedi.
                </div>
        <?php }
    } else {?>
          <div id="message" class="alert alert-danger">
                    Bilgileriniz güncellenemedi.
          </div>
    <?php }
}
}



if(isset($userId)){
    $userId = intval($userId);
    $sql = "SELECT * FROM user WHERE Id = :userId";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    
    $stmt->execute();
    $user = $stmt->fetch();
}
?>
<div class="container pt-3 pb-2">
    <div class="row pt-2">
          <?php include "FrontPartials/Profile/SideBar.php"?>
            <div class="col-lg-9 order-1 order-lg-2">
                <div class="tab-pane tab-pane-navigation active" id="formsStyleDefault">
                    <h4 class="mb-3">Profilim</h4>
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <form class="contact-form" action="/urun/profile" method="POST" novalidate="novalidate">
                                        <div class="contact-form-success alert alert-success d-none mt-4">
                                            <strong>Success!</strong> Your message has been sent to us.
                                        </div>

                                        <div class="contact-form-error alert alert-danger d-none mt-4">
                                            <strong>Error!</strong> There was an error sending your message.
                                            <span class="mail-error-message text-1 d-block"></span>
                                        </div>

                                        <div class="row">
                                            <div class="form-group col-lg-6">
                                                <label class="form-label mb-1 text-2">isim</label>
                                                <input type="text" data-msg-required="" maxlength="100" value="<?php echo $user["Name"] ?>" class="form-control text-3 h-auto py-2" name="Name" required="">
                                            </div>
                                            <div class="form-group col-lg-6">
                                                <label class="form-label mb-1 text-2">Soyisim</label>
                                                <input type="text" data-msg-required="" value="<?php echo $user["Surname"] ?>" data-msg-email="Please enter a valid email address." maxlength="100" class="form-control text-3 h-auto py-2" name="Surname" required="">
                                            </div>
                                        </div>
                                        <div class="row">
                                              <div class="form-group col-lg-6">
                                                    <label class="form-label mb-1 text-2">Email</label>
                                                    <input type="email" data-msg-required="" maxlength="100" value="<?php echo $user["Email"] ?>" class="form-control text-3 h-auto py-2" name="Email" required="">
                                               </div>
                                               <div class="form-group col-lg-6">
                                                    <label class="form-label mb-1 text-2">Kullanıcı Adı</label>
                                                    <input type="text" data-msg-required="" maxlength="100" value="<?php echo $user["UserName"] ?>" class="form-control text-3 h-auto py-2" name="UserName" required="">
                                               </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col">
                                                <label class="form-label mb-1 text-2">Şifre</label>
                                                <input type="password" data-msg-required="" maxlength="100" class="form-control text-3 h-auto py-2" name="Password" required="">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col">
                                                <input type="submit" name="updatebtn" value="Güncelle" class="btn btn-info" data-loading-text="Loading...">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
           </div>
    </div>
</div>

<?php include "FrontPartials/Footer.php"; ?>