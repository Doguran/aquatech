$(document).ready(function() {



    var orderModal = new ModalApp.ModalProcess({ id: 'orderModal', title: 'Чек заказа' });
    orderModal.init();


    $('.order').click(function (e) {
        e.preventDefault();
        var id = $(this).attr('id').substr(4);
        var tr = $(this);
        $.ajax({
            url: "/cabinet/show/order/"+id+"/",
            type: 'get',
            cache: false,
            dataType: 'html',
            beforeSend: function () {
                $('.ploader').fadeIn(0);
            },
            complete: function () {
                $('.ploader').delay(0).fadeOut('slow');
            },
            success: function (data) {
                orderModal.changeFooter('Спасибо за покупку!');
                orderModal.changeBody(data);
                orderModal.showModal();
            }

        });


    });



}); //конец ready(function()