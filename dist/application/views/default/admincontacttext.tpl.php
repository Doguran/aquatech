<!doctype html>
<html lang="en">
<head>
    <link rel="icon" href="<?php echo TEMPLATE_PATH ?>img/favicon.png">
    <meta charset="utf-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Редактирование</title>
    <link href="<?php echo TEMPLATE_PATH ?>css/main.css" rel="stylesheet">






</head>
<body>
<?php include("blocks/header.tpl.php"); ?>
<div class="container my-5">

    <?php if(isset($_GET["er"])): ?>
        <div class="col">
            <div class="alert alert-danger" role="alert"><?php echo $_GET["er"] ?></div>
        </div>
    <?php endif ?>

    <form action="/adminedittext/contacttext/" method="post" id="addform">




        <div class="tab-content">
            <div id="tabs-1" class="tab-pane fade show active" role="tabpanel" aria-labelledby="tabs-1-tab">

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="phone1">Телефон1</label>
                        <input class="form-control" type="text" value="<?php echo $this->contact['phone1']; ?>" name="phone1" id="phone1"  placeholder="Телефон1">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="phone2">Телефон2</label>
                        <input  class="form-control" type="text" value="<?php echo $this->contact['phone2']; ?>" name="phone2" id="phone2" placeholder="Телефон2">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="address">Адрес</label>
                        <input  class="form-control" type="text" value="<?php echo $this->contact['address']; ?>" name="address" id="address" placeholder="Адрес">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="email">E-mail</label>
                        <input  class="form-control" type="text" value="<?php echo $this->contact['email']; ?>" name="email" id="email"  placeholder="E-mail">

                    </div>
                    <div class="form-group col-md-6">
                        <label for="mode">Часы работы</label>
                        <input  class="form-control" type="text" value="<?php echo $this->contact['mode']; ?>" name="mode" id="mode"  placeholder="Часы работы">

                    </div>
                    <div class="form-group col-md-6">
                        <label for="maps">Координаты на карте: широта, долгота</label>
                        <input  class="form-control" type="text" value="<?php echo $this->contact['maps']; ?>" name="maps">

                    </div>

<!--                    <div class="form-group col-12">-->
<!--                        <label for="footer">Текст в футере</label>-->
<!--                        <textarea  class="form-control"  name="footer" id="footer" rows="4" placeholder="Текст в футере">--><?php //echo $this->contact['footer']; ?><!--</textarea>-->
<!---->
<!--                    </div>-->






                </div>





            </div>

        </div>



        <div class="contact-message my-3 text-danger"></div>
        <input class="btn btn-primary btn-add my-3" type="submit" value="Отправить">


    </form>

</div>
<?php include("blocks/footer.tpl.php"); ?>
<script src="<?php echo TEMPLATE_PATH ?>js/main.js"></script>


</body>
</html>