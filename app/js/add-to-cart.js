
function addToCard(id, price){
        
             
       $.ajax({
          url: "/cart/addtocart/",
          type: "POST",
          dataType: "json",
          data: {
            id: id,
            price: price,
            quantity: 1
          },
          success: function(data){
            if(data["success"]){
                
                $('#addcart-btn'+id).hide();
                $('#incart-btn'+id).fadeIn(1000);
                $('.cart-counter').text(data["quantityAll"]);


                var myCartModal = new ModalApp.ModalProcess({ id: 'myCartModal', title: 'Уведомление' });
                myCartModal.init();

                    $.get('/cart/modal/', function(data) {
                        myCartModal.changeFooter('');
                        myCartModal.changeBody(data);
                        myCartModal.showModal();
                    });

                
            }
            
          }
        });
        
        
    }
