//= ../../node_modules/jquery/dist/jquery.js

//= ../../node_modules/popper.js/dist/umd/popper.js

//= ../../node_modules/bootstrap/dist/js/bootstrap.js

    $(window).scroll(function() {
        if($(this).scrollTop() >= 290) {
            $('.mobil').addClass('stickytop');
        }
        else{
            $('.mobil').removeClass('stickytop');
        }
    });

