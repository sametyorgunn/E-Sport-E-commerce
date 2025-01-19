<?php include "FrontPartials/Header.php"; ?>
<?php
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Geçersiz ürün ID'si");
}

$urunId = (int)$_GET['id'];

try {
    $urun = $conn->prepare("SELECT 
    p.Id AS ProductId,
    p.Name,
    p.Description,
    p.Price,
    p.SalePrice,
    p.image,
    v.VariationName,
    GROUP_CONCAT(vo.OptionValue SEPARATOR ', ') AS VariationOptions,
    ps.OptionCombination,
    ps.StockQuantity
FROM 
    product p
LEFT JOIN 
    variation v ON p.Id = v.ProductId
LEFT JOIN 
    variationoption vo ON v.Id = vo.VariationId
LEFT JOIN 
    productstock ps ON p.Id = ps.ProductId
WHERE 
    p.Id = :id
GROUP BY 
    ps.Id, p.Id, p.Name, p.Description, p.Price, p.SalePrice, v.VariationName, ps.OptionCombination, ps.StockQuantity, p.image;
");
    $urun->execute(['id' => $urunId]);
    $urun = $urun->fetch(PDO::FETCH_ASSOC);

    if (!$urun) {
        die("Ürün bulunamadı.");
    }
} catch (PDOException $e) {
    die("Sorgu hatası: " . $e->getMessage());
}


try {
    $urunQuery = $conn->prepare("
        SELECT 
            v.VariationName,
            vo.OptionValue
        FROM 
            variation v
        LEFT JOIN 
            variationoption vo ON v.Id = vo.VariationId
        WHERE 
            v.ProductId = :id;
    ");

    $urunQuery->execute(['id' => $urunId]);
    $varyasyonlar = $urunQuery->fetchAll(PDO::FETCH_ASSOC);

    $renkler = [];
    $bedenler = [];
    foreach ($varyasyonlar as $varyasyon) {
        if ($varyasyon['VariationName'] === 'Renk') {
            $renkler[] = $varyasyon['OptionValue'];
        } elseif ($varyasyon['VariationName'] === 'Beden') {
            $bedenler[] = $varyasyon['OptionValue'];
        }
    }

} catch (PDOException $e) {
    die("Sorgu hatası: " . $e->getMessage());
}

$urunResimleri = $conn->prepare("SELECT * FROM productimage WHERE ProductId = :id");
$urunResimleri->execute(['id' => $urunId]);
$urunResimleri = $urunResimleri->fetchAll(PDO::FETCH_ASSOC);
?>

<script>
   document.addEventListener('DOMContentLoaded', function () {
    const colorSelect = document.getElementById('color');
    colorSelect.addEventListener('change', function () {
        debugger
        const selectedColor = this.value || 'default';
        const images = document.querySelectorAll('.product-image');
        const owlItems = document.querySelectorAll('.owl-item');

        // Tüm resimleri gizle ve `active` sınıfını kaldır
        // images.forEach(image => {
        //     image.style.display = 'none';
        // });

        owlItems.forEach(item => {
            item.classList.remove('active');
        });

        // Seçilen renk için uygun resmi göster ve `active` sınıfını ekle
        const matchingImage = document.querySelector(`.product-image[data-color="${selectedColor}"]`);
        if (matchingImage) {
            matchingImage.style.display = 'block';

            // `owl-item` içinde ilgili resmi bul ve `active` sınıfını ekle
            const matchingOwlItem = matchingImage.closest('.owl-item');
            if (matchingOwlItem) {
                matchingOwlItem.classList.add('active');
            }
        }
    });
});

</script>

<div id="response"></div>
<div role="main" class="main shop pt-4">
<div class="container">
    <div class="row">
        <div class="col">
            <ul class="breadcrumb breadcrumb-style-2 d-block text-4 mb-4">
                <li><a href="#" class="text-color-default text-color-hover-primary text-decoration-none">Ürün</a></li>
                <li><a href="#" class="text-color-default text-color-hover-primary text-decoration-none">Ürün Detay</a></li>
                <li><?php echo $urun['Name']; ?></li>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-md-5 mb-5 mb-md-0">
            <div class="thumb-gallery-wrapper">
                <div class="thumb-gallery-detail owl-carousel owl-theme manual nav-inside nav-style-1 nav-dark mb-3">
                    
                <?php foreach ($urunResimleri as $index => $resim): ?>
                            <img 
                                class="product-image" 
                                data-color="<?php echo !empty($resim['VariationOption']) ? htmlspecialchars($resim['VariationOption']) : 'default'; ?>" 
                                src="<?php echo htmlspecialchars($resim['Image']); ?>" >
                        <?php endforeach; ?>

                </div>
                <div class="thumb-gallery-thumbs owl-carousel owl-theme manual thumb-gallery-thumbs">
                    <?php foreach ($urunResimleri as $resim): ?>
                        <div class="cur-pointer">
                            <img alt="" class="img-fluid" src="<?php echo $resim['Image']; ?>">
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="summary entry-summary position-relative">
                <div class="position-absolute top-0 right-0">
                    <div class="products-navigation d-flex">
                    </div>
				</div>
                <h1 class="mb-0 font-weight-bold text-7"><?php echo $urun['Name']; ?></h1>
                <p class="price mb-3">
                    <span class="sale text-color-dark"><?php echo $urun['SalePrice']; ?>₺</span>
                    <span class="amount text-color-danger"><?php echo $urun['Price']; ?>₺</span>
                </p>
                <p class="text-3-5 mb-3"><?php echo $urun['Description']; ?></p>

                <!-- <form enctype="multipart/form-data" method="post" class="cart" action="shop-cart.html"> -->
                    <table class="table table-borderless" style="max-width: 300px;">
                        <tbody>
                            <tr>
                                <td class="align-middle text-2 px-0 py-2">BEDEN:</td>
                                <td class="px-0 py-2">
                                    <div class="custom-select-1">
                                        <select name="size" id="size" class="form-control form-select text-1 h-auto py-2">
                                            <option value="">Lütfen Beden Seçiniz</option>
                                            <?php foreach ($bedenler as $beden): ?>
                                                <option value="<?php echo $beden; ?>"><?php echo $beden; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="align-middle text-2 px-0 py-2">RENK:</td>
                                <td class="px-0 py-2">
                                    <div class="custom-select-1">
                                    <select name="color" id="color" class="form-control form-select text-1 h-auto py-2">
                                        <option value="">Lütfen Renk Seçiniz</option>
                                        <?php foreach ($renkler as $renk): ?>
                                            <option value="<?php echo htmlspecialchars($renk); ?>"><?php echo htmlspecialchars($renk); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <hr>
                    <div class="quantity quantity-lg">
                        <input type="button" class="minus text-color-hover-light bg-color-hover-primary border-color-hover-primary" value="-">
                        <input type="text" id="quantity" class="input-text qty text" title="Qty" value="1" name="quantity" min="1" step="1">
                        <input type="text" id="prodID" value="<?php echo $urunId; ?>" hidden>
                        <input type="button" class="plus text-color-hover-light bg-color-hover-primary border-color-hover-primary" value="+">
                    </div>
                    <button id="add-to-cart" type="" class="btn btn-dark btn-modern text-uppercase bg-color-hover-primary border-color-hover-primary">Sepete Ekle</button>
                    <hr>
                <!-- </form>             -->
            </div>
        </div>
    </div>
</div>
</div>
<?php include "FrontPartials/Footer.php"; ?>