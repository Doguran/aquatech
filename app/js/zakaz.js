jQuery(function ($) {
    
    var contact = {
        message: null,
        
        init_zakaz: function () {
        	$('#send').click(function (e) {
        		e.preventDefault();
                
                if (contact.validate()) {
                    //alert("ok!");
                    
						$.ajax({
							url: '/cart/takeorder/',
							data: $('#zakazform').serialize() + '&action=send',
							type: 'post',
							cache: false,
							dataType: 'json',
                            beforeSend : function () {
                                $('#send').text("Ждите...");
                            },
                            complete : function () {
                                $('#send').text("Оформить заказ");
                                
                            }, 
							success: function (data) {
								
                                if(data["success"]){
                                    window.scrollTo(0, 0);
                                    $('.cart-table').html(data["msg"]);
                                    $('.order-form').hide();
                                    $('.cart-counter').text("0"); 
                                    
                                }else{
                                    $('#p-contact-error').show();
                                    $('#contact-error').html(data["msg"]);
                                    
                                }
							},	
                            error: contact.error    
						});
                        
                    
                }else{
                    
                    contact.showError()
                        
                }
                
                
            });
        },
        
        error: function (xhr) {
			alert(xhr.statusText);
		},
        
        validate: function () {
			contact.message = '';
			if (!$.trim($('#inputName').val())) {
				contact.message += 'Не введено ФИО<br> ';
			}

			var email = $('#inputEmail').val();
			if (!email) {
				contact.message += 'Нет электропочты<br> ';
			}
			else {
				if (!contact.validateEmail(email)) {
					contact.message += 'Некорректный Email<br> ';
				}
			}

			if (!$.trim($('#inputAddress').val())){
				contact.message += 'Не введен адрес<br> ';
			}
            
            if (!$('#courier').prop('checked') && !$('#self').prop('checked')) {
				contact.message += 'Не выбран способ доставки<br> ';
			}
            
            if (!$('#cash').prop('checked') && !$('#bank').prop('checked')) {
				contact.message += 'Не выбран способ оплаты ';
			}

			if (contact.message.length > 0) {
				return false;
			}
			else {
				return true;
			}
		},
        
        validateEmail: function (email) {
			var at = email.lastIndexOf("@");

			// Make sure the at (@) sybmol exists and  
			// it is not the first or last character
			if (at < 1 || (at + 1) === email.length)
				return false;

			// Make sure there aren't multiple periods together
			if (/(\.{2,})/.test(email))
				return false;

			// Break up the local and domain portions
			var local = email.substring(0, at);
			var domain = email.substring(at + 1);

			// Check lengths
			if (local.length < 1 || local.length > 64 || domain.length < 4 || domain.length > 255)
				return false;

			// Make sure local and domain don't start with or end with a period
			if (/(^\.|\.$)/.test(local) || /(^\.|\.$)/.test(domain))
				return false;

			// Check for quoted-string addresses
			// Since almost anything is allowed in a quoted-string address,
			// we're just going to let them go through
			if (!/^"(.+)"$/.test(local)) {
				// It's a dot-string address...check for valid characters
				if (!/^[-a-zA-Z0-9!#$%*\/?|^{}`~&'+=_\.]*$/.test(local))
					return false;
			}

			// Make sure domain contains only valid characters and at least one period
			if (!/^[-a-zA-Z0-9\.]*$/.test(domain) || domain.indexOf(".") === -1)
				return false;	

			return true;
		},
        
        showError: function () {
            $('#p-contact-error').show();
			$('#contact-error').html(contact.message);
		}
        
    };
    
    contact.init_zakaz();
});