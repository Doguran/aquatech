<!doctype html>
<html lang="en">
<head>
    <link rel="icon" href="<?php echo TEMPLATE_PATH ?>img/favicon.png">
    <meta charset="utf-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Добавление товара</title>
    <link href="<?php echo TEMPLATE_PATH ?>css/main.css" rel="stylesheet">

    <script src="<?php echo TEMPLATE_PATH ?>js/main.js"></script>
    <script>
        $( function() {
            var product = {
                message: null,
                insert: function () {
                    $('.btn-add').click(function (e) {
                        e.preventDefault();
                        if (product.validate()) {
                            var form = document.getElementById("addform");
                            var data = new FormData(form);
                            $.ajax({
                                url: '/admindetail/<?php echo $this->action; ?>/',
                                data: data,
                                type: 'post',
                                cache: false,
                                contentType: false,
                                processData: false,
                                async: true,
                                dataType: 'json',
                                beforeSend : function () {
                                    $('.btn-add').val('Ждите..');
                                },
                                complete : function () {
                                    $('.btn-add').val('Отправить');
                                },
                                success: function (data) {
                                    if(data["success"]){
                                        window.location.href = '/category/show/id/'+data["predok"]+'/#table'+data["cat"];
                                    }else{
                                        $('.contact-message').show().html(data["msg"]);
                                    }
                                },
                                error: product.error
                            });
                        }else{
                            product.showError()
                        }
                    });
                },
                error: function (xhr) {
                    alert(xhr.statusText);
                },
                validate: function () {
                    product.message = '';
                    if (!$.trim($('#name').val())) {
                        product.message += 'Не введено название товара<br> ';
                    }
                    if (!$.trim($('#sku').val())) {
                        product.message += 'Не введен артикул<br> ';
                    }
                    if (product.message.length > 0) {
                        return false;
                    }
                    else {
                        return true;
                    }
                },
                showError: function () {
                    $('.error').show().html(product.message);
                }
            };
            product.insert();


            var myModaMenul = new ModalApp.ModalProcess({ id: 'myMenuModal', title: 'Все категории'});
            myModaMenul.init();
            $('.viewmenu').click(function(e){
                e.preventDefault();
                var href = $(this).attr('href');
                $.ajax({
                    url: href,
                    type: 'get',
                    beforeSend: function () {
                        $('.ploader').fadeIn();
                    },
                    complete: function () {
                        $('.ploader').delay(0).fadeOut('slow');
                    },
                    success: function (data) {
                        myModaMenul.changeFooter('');
                        myModaMenul.changeBody(data);
                        myModaMenul.showModal();
                    }
                })
            });
            $('#myMenuModal').on('shown.bs.modal', function () {
                $('.but_title, .but_title_do').click(function() {
                    $(this).next('menu').slideToggle();
                });
            });



        });
    </script>

</head>
<body>
<?php include("blocks/header.tpl.php"); ?>
<div class="container">
    <div class="row">
        <div class="col">


            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/category/viewmenu/" class="viewmenu pseudo-link">Все категории</a></li>
                <li  class="breadcrumb-item active" aria-current="page">Добавление товара</li>
            </ol>
        </div>
    </div>
</div>
<div class="container">
    <div class="panel panel-default">
        <!--<div class="panel-heading">
          <h3>Добавление товара</h3>
          </div> -->
        <div class="panel-body">
            <form method="post" id="addform" enctype='multipart/form-data'>
                <ul class="nav nav-pills admin-tab" role="tablist">
                    <li class="active nav-item"><a href="#tabs-1"  class="nav-link active" role="tab" data-toggle="tab">Основное</a></li>
                    <li class="nav-item"><a href="#tabs-2"  class="nav-link" role="tab" data-toggle="tab">Описание</a></li>
                    <li class="nav-item"><a href="#tabs-3"  class="nav-link" role="tab" data-toggle="tab">SEO</a></li>
                </ul>
                <div class="tab-content">
                    <div id="tabs-1" class="tab-pane fade show active">
                        <br /><br />
                        <table class="table admin-add-detail">
                            <tr>
                                <td>Название: </td>
                                <td><input class="form-control" type="text" value="<?php echo $this->name; ?>" name="name" id="name"></td>
                            </tr>
                            <tr>
                                <td>Артикул: </td>
                                <td><input class="form-control" type="text" value="<?php echo $this->sku; ?>" name="sku" id="sku"></td>
                            </tr>
                            <tr>
                                <td>Цена: </td>
                                <td><input class="form-control" type="text" value="<?php echo $this->price; ?>" name="price"></td>
                            </tr>
                            <tr>
                                <td>Старая цена: <br /><small>указывется, если товар в акции</small></td>
                                <td><input class="form-control" type="text" value="<?php echo $this->old_price; ?>" name="old_price"></td>
                            </tr>
                            <tr>
                                <td>Валюта:</td>
                                <td>
                                    <div class="custom-control custom-radio">
                                        <?php $checked = $this->valuta == "R" ? " checked" : ""; ?>
                                        <input type="radio" name="valuta"  id="customRadio1" value="R" class="custom-control-input" data-toggle="radio"<?php echo $checked ?>>
                                        <label class="custom-control-label" for="customRadio1">Рубль</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <?php $checked = $this->valuta == "E" ? " checked" : ""; ?>
                                        <input type="radio" name="valuta"  id="customRadio2" value="E" class="custom-control-input" data-toggle="radio"<?php echo $checked ?>>
                                        <label class="custom-control-label" for="customRadio2">Евро</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <?php $checked = $this->valuta == "D" ? " checked" : ""; ?>
                                        <input type="radio" name="valuta"  id="customRadio3" value="D" class="custom-control-input" data-toggle="radio"<?php echo $checked ?>>
                                        <label class="custom-control-label" for="customRadio3">Доллар</label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td><img src="/images/product/<?php echo $this->thumb_img; ?>" alt=""></td>
                            </tr>
                            <tr>
                                <td>Изображение:</td>
                                <td><input type="file"  name="photo"></td>
                            </tr>
                            <tr>
                                <td>Категория:</td>
                                <td>
                                    <select class="custom-select" id="select-cat" name="new_cat_id">
                                        <?php echo $this->catOption; ?>
                                    </select>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div id="tabs-2" class="tab-pane fade">
                        <br /><br />
                        <textarea id="editor1" class="form-control" name="description"><?php echo $this->description; ?></textarea><br />

                        Комлектация (через запятую):
                        <input class="form-control input-sm w-50" type="text" value="<?php echo $this->complete; ?>" name="complete" id="complete"> <br />
                        <div class="clearfix"></div>

                        <br />
                    </div>
                    <div id="tabs-3" class="tab-pane fade">
                        <br />
                        Title: <br /><input class="form-control" type="text" value="<?php echo $this->title; ?>" name="title"><br />
                        Keywords: <br /><textarea class="form-control" name="keywords"><?php echo $this->keywords; ?></textarea><br />
                        Description: <br /><textarea class="form-control" name="seo_desc"><?php echo $this->seo_desc; ?></textarea><br /><br />
                        <input type="hidden" value="<?php echo $this->cat_id; ?>" name='cat_id'>
                        <input type="hidden" value="<?php echo $this->id; ?>" name='product_id'>
                        <input type="hidden" name="thumb_img" value="<?php echo $this->thumb_img ?>"/>
                        <input type="hidden" name="full_img" value="<?php echo $this->full_img ?>"/>
                    </div>


                </div>
                <div class="alert alert-danger error" role="alert"></div>
                <input class="btn btn-primary btn-add my-5" type="submit" value="Добавить">
            </form>
        </div>
    </div>
</div>
<?php include("blocks/footer.tpl.php"); ?>
</body>
</html>