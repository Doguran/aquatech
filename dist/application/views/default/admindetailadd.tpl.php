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
    <script type="text/javascript" src="<?php echo HTTP_PATH ?>ckeditor/ckeditor.js"></script>
    <script>
        $( function() {
            var product = {
                message: null,
                insert: function () {
                    $('.btn-add').click(function (e) {
                        e.preventDefault();
                        if (product.validate()) {
                            CKEDITOR.instances.editor1.updateElement();
                            CKEDITOR.instances.editor2.updateElement();
                            var form = document.getElementById("addform");
                            var data = new FormData(form);
                            $.ajax({
                                url: '/admindetail/insert/',
                                data: data,
                                type: 'post',
                                cache: false,
                                contentType: false,
                                processData: false,
                                async: true,
                                dataType: 'json',
                                beforeSend : function () {
                                    $('.ploader').fadeIn(0);
                                },
                                complete : function () {

                                },
                                success: function (data) {
                                    if(data["success"]){
                                        window.location.href = '<?php echo HTTP_PATH ?>product/show/id/'+data["id"]+'/';
                                    }else{
                                        $('.ploader').delay(0).fadeOut('slow');
                                        $('.error').show().html(data["msg"]);
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
            $("#select-cat").change(function () {
                var cat_id = $(this).val();
                $.ajax({
                    url: '/admindetail/getcatparam/',
                    data: 'cat_id=' + cat_id,
                    type: 'post',
                    dataType: 'json',
                    success: function (data) {
                        $('#param-block').html(data);
                    }
                })
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
                    <li class="nav-item"><a href="#tabs-4"  class="nav-link" role="tab" data-toggle="tab">Яндекс.Маркет</a></li>
                </ul>
                <div class="tab-content">
                    <div id="tabs-1" class="tab-pane fade show active">
                        <br /><br />
                        <table class="table admin-add-detail">
                            <tr>
                                <td>Название: </td>
                                <td><input class="form-control" type="text" value="" name="name" id="name"></td>
                            </tr>
                            <tr>
                                <td>Артикул: </td>
                                <td><input class="form-control" type="text" value="" name="sku" id="sku"></td>
                            </tr>
                            <tr>
                                <td>Цена: </td>
                                <td><input class="form-control" type="text" value="" name="price"></td>
                            </tr>
                            <tr>
                                <td>Старая цена: <br /><small>указывется, если товар в акции</small></td>
                                <td><input class="form-control" type="text" value="" name="old_price"></td>
                            </tr>
                            <tr>
                                <td>Валюта:</td>
                                <td>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" name="valuta"  id="customRadio1" value="R" class="custom-control-input" data-toggle="radio" checked>
                                        <label class="custom-control-label" for="customRadio1">Рубль</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" name="valuta"  id="customRadio2" value="E" class="custom-control-input" data-toggle="radio">
                                        <label class="custom-control-label" for="customRadio2">Евро</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" name="valuta"  id="customRadio3" value="D" class="custom-control-input" data-toggle="radio">
                                        <label class="custom-control-label" for="customRadio3">Доллар</label>
                                    </div>
                                </td>
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
                        <h3 class="mt-5">Промо-текст</h3><textarea id="editor2" class="form-control" name="promo"></textarea>
                        <h3>Описание</h3><textarea id="editor1" class="form-control" name="description"></textarea><br />
                        <script type='text/javascript'>
                            CKEDITOR.replace( 'editor2', {toolbar : 'MyToolbar'} );
                            CKEDITOR.replace( 'editor1', {toolbar : 'MyToolbar'} );
                        </script>
                        Комлектация (через запятую):
                        <input class="form-control input-sm w-50" type="text" value="" name="complete" id="complete"> <br />
                        <div class="clearfix"></div>
                        <div class="form-group col-md-6 my-3" id="param-block">
                        </div>
                        <br />
                    </div>
                    <div id="tabs-3" class="tab-pane fade">
                        <br />
                        Title: <br /><input class="form-control" type="text" value="" name="title"><br />
                        Keywords: <br /><textarea class="form-control" name="keywords"></textarea><br />
                        Description: <br /><textarea class="form-control" name="seo_desc"></textarea><br /><br />
                    </div>
                    <div id="tabs-4" class="tab-pane fade">
                        <br />
                        Модель: (если модель не указана, товар не будет отображаться в файле YML)<br /><input class="form-control" type="text" value="" name="model"><br />
                        Категория товара, в которой он должен быть размещен на Яндекс.Маркете. Допустимо указывать названия категорий только из товарного дерева категорий Яндекс.Маркета. <a href="http://help.yandex.ru/partnermarket/docs/market_categories.xls">Скачать дерево категорий</a> <br /><input class="form-control" type="text" value="" name="yandex_cat"><br />

                        <div class="custom-control custom-radio">
                            <input type="radio" name="garant"  id="garant1" value="false"  class="custom-control-input" data-toggle="radio" >
                            <label class="custom-control-label" for="garant1">товар не имеет официальной гарантии</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input type="radio" name="garant"  id="garant2" value="true"  class="custom-control-input" data-toggle="radio">
                            <label class="custom-control-label" for="garant2">товар имеет официальную гарантию</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input type="radio" name="garant"  id="garant3" value="null"  class="custom-control-input" data-toggle="radio" checked>
                            <label class="custom-control-label" for="garant3">не указывать гарантию</label>
                        </div>

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