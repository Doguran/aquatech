<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="<?php echo TEMPLATE_PATH ?>css/fancybox.css">
    <link rel="stylesheet" href="<?php echo TEMPLATE_PATH ?>css/jquery-ui.css">
    <link rel="stylesheet" href="<?php echo TEMPLATE_PATH ?>css/main.css">
    <title>Hello, world!</title>
</head>
<body>
<?php include("blocks/header.tpl.php"); ?>
<main role="main">


    <?php if(isset($this->cart) AND is_array($this->cart) AND !empty($this->cart)) :?>
    <div class="container cart-table">
        <h4 class="featurette-heading my-5 text-center">Ваша корзина</h4>


        <?php foreach($this->cart as $val) : ?>
            <div class="row align-items-center text-center border-bottom" id="product_<?php echo $val["id"] ?>">
                <div class="col-6 col-md-2"><img
                                src='<?php echo HTTP_PATH ?>images/product/<?php echo $val["thumb_img"] ?>'
                                class="img-fluid"></div>
                <div class="col-6 col-md-4"><?php echo $val["name"] ?></div>
                <div class="col-4 col-md-2">
                    <div class="text-center">
                        <input class="spinner" name="value" value="<?php echo $val["quantity"] ?>" type="text" id="inp_<?php echo $val["id"] ?>">
                    </div>
                </div>
                <div class="col-4 col-md-2" id="price_<?php echo $val["id"] ?>"><?php echo $val["price"] ?> р.</div>
                <div class="col-4 col-md-2 delete_from_cart" id="del_<?php echo $val["id"] ?>"><i class="fas fa-times-circle"></i></div>
            </div>
        <?php endforeach; ?>

        <div class="row align-items-center text-center lead py-5">

            <div class="col-4 offset-md-6 col-md-2">Итого к оплате:</div>
            <div class="col-5 col-md-2" id="itog_price"><?php echo $this->cartAll["priceAll"] ?> р.</div>

        </div>
    </div>

    <div class="container-fluid order-form">
        <div class="container">
            <h4 class="featurette-heading mb-5 text-center">Оформление доставки</h4>
            <?php if(!isset($_SESSION["user"])) :?>
                <div class="mb-3 mb-lg-5 text-center"><a href="#" class="pseudo-link auth_modal">Уже покупали у нас?</a></div>
            <?php endif; ?>
            <form id="zakazform" method="post" action="">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="mt-3 mb-lg-3 mt-lg-0">

                            <input class="form-control form-control-lg" id="inputName" name="name" placeholder="Имя*" type="text" value="<?php echo $this->customerData["name"] ?>">
                        </div>
                    </div>
                    <div class="col-lg-6 example">
                        Пример: Иванов Сергей Александрович
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="mt-3 mb-lg-3 mt-lg-0">

                            <input class="form-control form-control-lg" id="inputEmail" name="email" placeholder="Email*" type="text" value="<?php echo $this->customerData["email"] ?>">
                        </div>
                    </div>
                    <div class="col-lg-6 example">
                        Пример: test@mail.ru
                    </div>

                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="mt-3 mb-lg-3 mt-lg-0">

                            <input class="form-control form-control-lg" id="inputTel" name="phone" placeholder="Телефон" type="text" value="<?php echo $this->customerData["phone"] ?>">
                        </div>
                    </div>
                    <div class="col-lg-6 example">
                        Пример: 8 937 999 99 99
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="mt-3 mb-lg-3 mt-lg-0">
                            <input type="text" name="url" id="zakaz-urlform" class="concealed" value=""/>
                            <input class="form-control form-control-lg" id="inputAddress" name="address" placeholder="Адрес доставки" type="text" value="<?php echo $this->customerData["address"] ?>">
                        </div>
                    </div>
                    <div class="col-lg-6 example">
                        Пример: г. Москва, пр. Мира, ул. Петра Великого д.19, кв 51.
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="mt-3 mb-lg-3 mt-lg-0">

                            <textarea class="form-control form-control-lg" id="inputText" name="note" rows="3" placeholder="Сообщение"></textarea>
                        </div>
                    </div>
                    <div class="col-lg-6 example">
                        Пример: Позвоните, пожалуйста, после 10 вечера, до этого времени я на работе
                    </div>
                </div>
                <div class="row my-3">
                    <div class="col col-lg-6">
                        <div class="row">
                            <div class="col-md-6">Выберите способ доставки:*</div>
                            <div class="col-md-6">
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="courier" name="delivery" value="courier" class="custom-control-input">
                                    <label class="custom-control-label" for="courier">Курьером, <?php echo COURIER_PRISE ?> руб.</label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="self" name="delivery" value="self" class="custom-control-input">
                                    <label class="custom-control-label" for="self">Самовывоз, бесплатно </label>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6">Выберите способ оплаты:*</div>
                            <div class="col-md-6">
                                <div class="custom-control custom-radio">
                                    <input type="radio" name="payment" value="cash" id="cash" class="custom-control-input">
                                    <label class="custom-control-label" for="cash">Наличными курьеру</label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input type="radio" name="payment" value="bank" id="bank" class="custom-control-input">
                                    <label class="custom-control-label" for="bank">Банковский перевод </label>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="row mt-5">
                    <div class="col-md-4 mb-5">
                        <p id="p-contact-error" class="mb-0 text-danger">При отправке формы обнаружены следующие ошибки:</p>
                        <div id="contact-error"></div>
                        <button type="submit" id="send" class="btn btn-primary my-2">Оформить заказ</button>

                    </div>
                    <div class="col-md-8">
                        Нажимая на кнопку, вы даете согласие на обработку своих персональных данных.
                    </div>

                </div>
            </form>



        </div>

    </div>

    <?php else : ?>
        <div class="container">
            <h4 class="my-5">Корзина пуста</h4>
        </div>
    <?php endif; ?>


</main>
<?php include("blocks/footer.tpl.php"); ?>
<script src="<?php echo TEMPLATE_PATH ?>js/main.js"></script>
<script src="<?php echo TEMPLATE_PATH ?>js/fancybox.js"></script>
<script src="<?php echo TEMPLATE_PATH ?>js/jquery-ui.js"></script>
<script src="<?php echo TEMPLATE_PATH ?>js/zakaz.js"></script>
<script>
    $( function() {
        $( ".spinner" ).spinner({
            min: 1
        });

        $(".spinner").on("keyup spin", pereschet);

        function pereschet(){
            if(request){
                window.self = this
                request = 0;
                setTimeout(pereschet2, 1000);
            }else{
                return false;
            }
        }
        var request = 1;
        function pereschet2(){
            request = 1;

            $.ajax({
                url: "/cart/edit/",
                type: "POST",
                dataType: "json",
                data: {
                    id: $(self).attr("id"),
                    quantity: $(self).val()
                },
                success: function(data){
                    if(data["success"]){

                        $('#price_'+data["id"]).html(data["sum"]+" р.");
                        $('#itog_quantity').html(data["quantityAll"]+" шт.");
                        $('#itog_price').html(data["priceAll"]+" руб.");
                        $('.cart-counter').text(data["quantityAll"]);

                    }

                }
            });
        }

        $('.delete_from_cart').click(function() {

            $.ajax({
                url: "/cart/delete/",
                type: "POST",
                dataType: "json",
                data: {
                    id: $(this).attr('id'),
                },
                success: function(data){
                    if(data["success"]){

                        if(data["quantityAll"]==0){
                            location.href='/'
                        }else{
                            $('#product_'+data["id"]).hide();
                            $('#itog_quantity').html(data["quantityAll"]+" шт.");
                            $('#itog_price').html(data["priceAll"]+" руб.");
                            $('.cart-counter').text(data["quantityAll"]);
                        }
                    }
                }
            });
            return false;
        });



    } );
</script>


</body>
</html>