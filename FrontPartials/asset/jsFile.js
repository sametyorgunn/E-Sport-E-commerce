$('#add-to-cart').on('click', function (event) {
    event.preventDefault();
    const size = $('#size').val();
    const color = $('#color').val();
    const quantity = $('#quantity').val();
    const prodID = $('#prodID').val();

    $.ajax({
        url: 'ajax-handler.php',
        method: 'POST',
        data: {
            action: 'addToCart',
            size: size,
            color: color,
            quantity: quantity,
            prodID: prodID
        },
        success: function (response) {
            debugger
            response = JSON.parse(response);
            toastr.success(response.message);
            let currentCount = parseInt($("#basketCount").text());
            $("#basketCount").text(currentCount + 1);
        },
        error: function (xhr) {
            debugger
            toastr.error(xhr.responseJSON.message);
        }
    });
});

$('#cart-link').on('click', function (event) {
    event.preventDefault();
    window.location.href = '/urun/basket.php';
});

function updateQuantity(id, quantity, prodId) {
    $.ajax({
        url: 'ajax-handler.php',
        method: 'POST',
        data: {
            action: 'updateToCart',
            quantity: quantity,
            basketId: id,
            prodId: prodId
        },
        success: function (response) {
            response = JSON.parse(response);
            debugger
            $('#totalPrice' + response.basketId).text(response.price + '₺');
            $('#totalPriceGeneral').text(response.totalPriceGeneral + '₺');
            toastr.success(response.message);
        },
        error: function (xhr) {
            try {
                console.log(xhr.responseText);
                response = JSON.parse(xhr.responseText);
                toastr.error(response.message);
            } catch (e) {
                console.error("Geçersiz JSON Yanıtı:", xhr.responseText);
                toastr.error("Sunucudan beklenmeyen bir yanıt alındı.");
            }
        }
    });    
}

$(document).ready(function () {
    $("#submitRegister").click(function (e) {
        e.preventDefault(); 
        var name = $("#Name").val(); 
        var surname = $("#Surname").val(); 
        var email = $("#Email").val(); 
        var username = $("#UserName").val(); 
        var password = $("#Password").val(); 
        var repassword = $("#rePassword").val(); 

        $.ajax({
            url: "ajax-handler.php", 
            type: "POST",
            data:{
                action:"registerUser",
                Name:name,
                Surname:surname,
                Email:email,
                UserName:username,
                Password:password,
                rePassword:repassword
            },
            success: function (response) {
                    toastr.success(response.message);
                    setTimeout(function () {
                        window.location.href = "/Urun/Login"; 
                    }, 2000);
            },
            error: function (xhr, status, error) {
                response = JSON.parse(xhr.responseText);
                toastr.error(response.message);
            },
        });
    });
});

    $('#scrollToProducts').click(function(){
        document.getElementById('products').scrollIntoView({ 
            behavior: 'smooth' 
        });
    })
   

  $("#loginBtn").click(function (e) {
    e.preventDefault(); 
    var UserName = $("#LoginUsername").val(); 
    var Password = $("#LoginPassword").val(); 

    $.ajax({
        url: "ajax-handler.php", 
        type: "POST",
        data:{
            action:"LoginUser",
            LoginUsername:UserName,
            LoginPassword:Password
        },
        success: function (response) {
            response = JSON.parse(response);
            toastr.success(response.message);
            setTimeout(function () {
                window.location.href = "/urun/profile"; 
            }, 2000);
        },
        error: function (xhr, status, error) {
            response = JSON.parse(xhr.responseText);
            toastr.error(response.message);
            window.location.href = "/urun/login"; 
        },
    });
});

function deleteProduct(id){
    var basketID = id;
    $.ajax({
        url: "ajax-handler.php", 
        type: "POST",
        data:{
            action:"deleteBasket",
            Id:id
        },
        success: function (response) {
            // response = JSON.parse(response);
            // toastr.success(response.message);
            var item = document.getElementById(id);
            item.remove();
            
        },
        error: function (xhr, status, error) {
            response = JSON.parse(xhr.responseText);
            toastr.error(response.message);
            window.location.href = "/urun/login"; 
        },
    });
}


$(document).on('click', '.addtocart-btn', function(e) {
    e.preventDefault();
    var productId = $(this).data('product-id');
    debugger
    $.ajax({
        url: 'ajax-handler.php',
        method: 'POST',
        data: { 
            action:"AddToCartFastly",
            productId: productId },
        success: function(response) {
                response = JSON.parse(response);
                toastr.info(response.message);
                setTimeout(function () {
                    window.location.href = "/urun/productDetail?id=" + productId; 
                }, 1000);             
        },
        error: function(xhr, status, error) {
            response = JSON.parse(xhr.responseText);
            toastr.error(response.message);
        }
    });
});

