<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="<?php echo TEMPLATE_PATH ?>css/main.css">
    <title>Aqua Tecnica</title>
</head>
<body>
<?php include("blocks/header.tpl.php"); ?>
<main role="main">




    <div class="container">
        <?php if(isset($this->orders)) :?>
            <h4 class="mt-5">Кабинет пользователя <?php echo $_SESSION["user"]["name"] ?></h4>
            <div class="row my-3">
                <div class="col text-center text-md-right">
                    <a class="btn btn-primary" id="edit_pass" href="#">Сменить пароль</a>
                    <a class="btn btn-primary" href="/auth/logout/">Выйти</a>
                </div>
            </div>
            <h4>История заказов</h4>
            <p>Кликая по строкам таблицы, Вы можете просматривать детали заказа.</p>
            <table class="table table-bordered mb-5">
                <tr>
                    <th>Дата</th>
                    <th align='center'>Номер заказа</th>
                    <th align='center'>Стоимость</th>
                </tr>
                <?php foreach($this->orders as $val) :?>

                    <tr class="order" id="ord_<?php echo $val["id"] ?>">
                        <td class="for-loader"><?php echo "$val[date_d] $val[date_m] $val[date_y]"; ?></td>
                        <td><?php echo $val["id"] ?></td>
                        <td><?php echo $val["summa"] ?> руб.</td>
                    </tr>

                <?php endforeach; ?>

            </table>
        <?php else : ?>
            <h1 class="text-center my-5">Неоходима авторизация</h1>
        <?php endif; ?>
    </div>



</main>
<?php include("blocks/footer.tpl.php"); ?>
<script src="<?php echo TEMPLATE_PATH ?>js/main.js"></script>
<script src="<?php echo TEMPLATE_PATH ?>js/order-modal.js"></script>
<script src="<?php echo TEMPLATE_PATH ?>js/edit-pass-modal.js"></script>
</body>
</html>