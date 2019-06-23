$(document).ready(function() {



    var myModal = new ModalApp.ModalProcess({ id: 'myModal', title: 'Авторизация', size: 'modal-sm' });
    myModal.init();

    $('#auth_modal, .auth_modal').click(function(e){
        e.preventDefault();
        $.get('/auth/modal/', function(data) {
            var data = JSON.parse(data);
            myModal.changeFooter('');
            myModal.changeBody(data['body']);
            myModal.showModal();
        });
    });


    $('#myModal').on('shown.bs.modal', function () {
        modalAuth.init();
    });





    var modalAuth = {
        message: null,
        init: function () {
            $('#authForm').submit(function (e) {

                e.preventDefault();
                if (modalAuth.validate()) {


                    $('.contact-message').html("");
                    $.ajax({
                        url: '/auth/',
                        data: $('#authForm').serialize() + '&action=send',
                        type: 'post',
                        cache: false,
                        async: true,
                        dataType: 'json',
                        beforeSend: function () {

                            $('#auth_submit').hide();
                            $('#ajax-loader').show()

                        },
                        complete: function () {
                            $('#auth_submit').show();
                            $('#ajax-loader').hide()
                        },
                        success: function (data) {
                            if (data["success"]) {
                                $('.success-message-modal').fadeIn(0).html('секундочку...')
                                location.reload()
                            } else {
                                $('.error-message-modal').fadeIn().html(data["msg"])
                            }
                        },
                        error: modalAuth.error
                    })
                } else {
                    $('.error-message-modal').fadeIn().html(modalAuth.message)
                }
            })
        },
        error: function (xhr) {
            alert(xhr.statusText)
        },
        validate: function () {
            modalAuth.message = '';
            var valid = true;
            var email = $('#auth_email').val();

            if (!email) {
                valid = false
            } else {
                if (!modalAuth.validateEmail(email)) {
                    valid = false
                }
            } if ($.trim($('#auth_pass').val()).length < 6) {
                valid = false
            }
            if (!valid) {
                modalAuth.message = 'Неверный логин или пароль'
            }
            return valid
        },
        validateEmail: function (email) {
            var at = email.lastIndexOf("@");
            if (at < 1 || (at + 1) === email.length) return false;
            if (/(\.{2,})/.test(email)) return false;
            var local = email.substring(0, at);
            var domain = email.substring(at + 1);
            if (local.length < 1 || local.length > 64 || domain.length < 4 || domain.length > 255) return false;
            if (/(^\.|\.$)/.test(local) || /(^\.|\.$)/.test(domain)) return false;
            if (!/^"(.+)"$/.test(local)) {
                if (!/^[-a-zA-Z0-9!#$%*\/?|^{}`~&'+=_\.]*$/.test(local)) return false
            }
            if (!/^[-a-zA-Z0-9\.]*$/.test(domain) || domain.indexOf(".") === -1) return false;
            return true
        }
    };
    // modalAuth.init()






}); //конец ready(function()