//= ../../node_modules/jquery/dist/jquery.js
//= ../../node_modules/popper.js/dist/umd/popper.js
//= ../../node_modules/bootstrap/dist/js/bootstrap.js
//= ../../node_modules/jquery.simplemarquee/lib/jquery.simplemarquee.js
//= control-modal-b4.js
//= auth-modal.js
//= add-to-cart.js

//Прелоадерg
$(window).on('load', function () {
    $('.ploader').delay(0).fadeOut('slow');
});
///////

$(document).ready(function(){
    $('.but_title, .but_title_do').click(function() {
        $(this).next('menu').slideToggle();
    });
    $('.simplemarquee').simplemarquee({
        speed: 40,
        cycles: 'Infinity'

    });
});



    $(window).scroll(function() {
        if($(this).scrollTop() >= 290) {
            $('.mobil').addClass('stickytop');
            $('.mobil>div.row>div').addClass('flex-fill').removeClass('w-100');
        }
        else{
            $('.mobil').removeClass('stickytop');
            $('.mobil>div.row>div').removeClass('flex-fill').addClass('w-100');
        }
    });

