$(document).ready(function() {



    var passModal = new ModalApp.ModalProcess({ id: 'passModal', title: 'Замена пароля', size: 'modal-sm' });
    passModal.init();

    $('#edit_pass').click(function(e){
        e.preventDefault();
        $.get('/auth/editpass/', function(data) {
            var data = JSON.parse(data);
            passModal.changeFooter('');
            passModal.changeBody(data['body']);
            passModal.showModal();
        });
    });


    $('#passModal').on('shown.bs.modal', function () {
        modalEditPass.init();
    });





    var modalEditPass = {
        message: null,
        init: function () {
            $('#EditPassForm').submit(function (e) {
                e.preventDefault();
                if (modalEditPass.validate()) {
                    $('.contact-message').html("");
                    $.ajax({
                        url: '/auth/editpass/',
                        data: $('#EditPassForm').serialize() + '&action=send',
                        type: 'post',
                        cache: false,
                        dataType: 'json',
                        beforeSend: function () {
                            $('#EditPass_submit').hide();
                            $('#ajax-loader-EditPass').show()
                        },
                        complete: function () {
                            $('#EditPass_submit').show();
                            $('#ajax-loader-EditPass').hide()
                        },
                        success: function (data) {
                            if (data["success"]) {
                                $('#edit-pass-cont').fadeIn().html(data["msg"])

                            } else {
                                $('.contact-message').fadeIn().html(data["msg"])
                            }
                        },
                        error: modalEditPass.error
                    })
                } else {
                    $('.contact-message').fadeIn().html(modalEditPass.message)
                }
            })
        },
        error: function (xhr) {
            alert(xhr.statusText)
        },
        validate: function () {
            modalEditPass.message = '';


            if ($.trim($('#old_pass').val()).length < 6){
                modalEditPass.message += 'Неверный пароль. ';
            }
            if ($.trim($('#new_pass').val()).length < 6){
                modalEditPass.message += 'Пароль менее 6 символов. ';
            }
            if ($.trim($('#new_pass').val()) != $.trim($('#new_pass2').val())){
                modalEditPass.message += 'Новые пароли не совпадают. ';
            }

            if (modalEditPass.message.length > 0) {
                return false;
            }
            else {
                return true;
            }

        }

    };






}); //конец ready(function()