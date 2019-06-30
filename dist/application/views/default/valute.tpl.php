<!doctype html>
<html lang="en">
<head>
    <link rel="icon" href="<?php echo TEMPLATE_PATH ?>img/favicon.png">
    <meta charset="utf-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Добавление товара</title>
    <link href="<?php echo TEMPLATE_PATH ?>css/main.css" rel="stylesheet">





</head>
<body>
<?php include("blocks/header.tpl.php"); ?>
<div class="container">

    <h4 class="my-5">Курс валют</h4>

    <div class="row">
        <?php if(isset($_GET["ok"])): ?>
            <div class="col">
                <div class="alert alert-success" role="alert"><?php echo $_GET["ok"] ?><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button></div>
            </div>
        <?php endif ?>
        <?php if(isset($_GET["er"])): ?>
            <div class="col">
                <div class="alert alert-danger" role="alert"><?php echo $_GET["er"] ?><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button></div>
            </div>
        <?php endif ?>
    </div>
    <div class="row">

        <div class="col mb-5">
            <p>Текущий курс магазина:<br />
                Доллар - <strong><?php echo $this->shop_dollar ?></strong><br />
                Евро - <strong><?php echo $this->shop_evro ?></strong><br />
            </p>
            <p>Курс ЦБ на сегодня:<br />

            <form action="/valute/exchange/" method="post">
                Доллар:<br />
                <input type="text" name="dollar" class="form-control input-sm" style="width: 200px;" value="<?php echo $this->dollar ?>"/>

                Евро:<br />
                <input type="text" name="evro" class="form-control input-sm" style="width: 200px;" value="<?php echo $this->evro ?>"/><br />
                <input type="submit" value="Изменить курс " class="btn btn-primary">
            </form>
            </p>
        </div>
    </div>





</div>
<?php include("blocks/footer.tpl.php"); ?>
<script src="<?php echo TEMPLATE_PATH ?>js/main.js"></script>
</body>
</html>