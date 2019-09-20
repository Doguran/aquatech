
$(document).ready(function() {



    var myModal = new ModalApp.ModalProcess({ id: 'myPhoneModal', title: 'Консультация специалиста', size: 'modal-sm' });
    myModal.init();

    $('.call').click(function(e){
        e.preventDefault();
        var text = $(this).text();
        $.ajax({
            url: '/txt/phonemodal/',
            type: 'get',
            beforeSend: function () {
                $( e.target ).text("Ждите...");
            },
            complete: function () {
                $( e.target ).text(text);
            },
            success: function (data) {
                var data = JSON.parse(data);
                myModal.changeFooter('');
                myModal.changeBody(data['body']);
                myModal.showModal();
            }
        })

    });


    $('#myPhoneModal').on('shown.bs.modal', function () {
        modalPhone.init();
    });




    var modalPhone = {
        message: null,
        init: function () {
            $('#user_name, #user_phone').focus(function(){
                $(this).removeClass("is-invalid");
            });
            $('#phoneForm').submit(function (e) {
                e.preventDefault();
                if (modalPhone.validate()) {


                    $('.contact-message').html("");
                    $.ajax({
                        url: '/txt/phonemodal/',
                        data: $('#phoneForm').serialize() + '&action=send',
                        type: 'post',
                        cache: false,
                        async: true,
                        dataType: 'json',
                        beforeSend: function () {
                            $('#call-btn').val("Отправляю...");
                        },
                        complete: function () {
                            $('#call-btn').val("Позвоните мне");
                        },
                        success: function (data) {
                            if (data["success"]) {
                                $('#phone_form').html(data["msg"]);
                            } else {
                                $('.error-message-phone').fadeIn().html(data["msg"])
                            }
                        },
                        error: modalPhone.error
                    })
                } else {
                    $('.error-message-phone').fadeIn().html(modalPhone.message)
                }
            })
        },
        error: function (xhr) {
            alert(xhr.statusText)
        },
        validate: function () {
            modalPhone.message = '';

            if (!$.trim($('#user_name').val())){
                modalPhone.message += 'Не введенно имя<br> ';
                $('#user_name').addClass("is-invalid");
            }
            if (!$.trim($('#user_phone').val())){
                modalPhone.message += 'Не введен номер<br> ';
                $('#user_phone').addClass("is-invalid");
            }
            if (modalPhone.message.length > 0) {
                return false;
            }
            else {
                return true;
            }
        }
    };
    // modalPhone.init()

    //выводим в глобальную область
    //window["modalPhone"] = modalPhone;






}); //конец ready(function()

