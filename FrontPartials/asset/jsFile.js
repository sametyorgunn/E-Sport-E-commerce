$('#add-to-cart').on('click', function (event) {
    event.preventDefault();
    const size = $('#size').val();
    const color = $('#color').val();
    const quantity = $('#quantity').val();
    const prodID = $('#prodID').val();

    $.ajax({
        url: 'ajax-handler.php', // İşlem yapılacak PHP dosyası
        method: 'POST',
        data: {
            action: 'addToCart',
            size: size,
            color: color,
            quantity: quantity,
            prodID: prodID
        },
        success: function (response) {
            toastr.success(response);
        },
        error: function (xhr) {
            toastr.error(xhr.responseJSON.message);
        }
    });
});

$('#cart-link').on('click', function (event) {
    debugger
    event.preventDefault();
    window.location.href = '/urun/basket.php';
});
