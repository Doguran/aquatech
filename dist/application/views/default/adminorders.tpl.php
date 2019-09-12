<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="<?php echo TEMPLATE_PATH ?>img/favicon.png">
    <link rel="stylesheet" href="<?php echo TEMPLATE_PATH ?>css/main.css">
    <title>Заказы</title>
</head>
<body>
<?php include("blocks/header.tpl.php"); ?>
<main role="main">




    <div class="container">
        <?php if(isset($this->orders)) :?>
            <h4 class="my-5">История заказов</h4>
            <form action="/adminorders/deleteorders/" method="post">
                <table class="table table-striped mb-5">
                    <tr>
                        <th>Дата</th>
                        <th>Номер заказа</th>
                        <th class="d-none d-sm-table-cell">Покупатель</th>
                        <th>Стоимость</th>
                        <th></th>
                    </tr>
                    <?php foreach($this->orders as $val) :?>

                        <tr>
                            <td><?php echo "$val[date_d] $val[date_m] $val[date_y]"; ?></td>
                            <td class="order pseudo-link" id="ord_<?php echo $val["id"] ?>"><?php echo $val["id"] ?></td>
                            <td class="d-none d-sm-table-cell"><?php echo $val["name"] ?></td>
                            <td><?php echo $val["summa"] ?> руб.</td>
                            <td class="text-center pl-3">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" value="<?php echo $val["id"] ?>" name="delete_orders[]" id="checkbox<?php echo $val["id"] ?>">
                                    <label class="custom-control-label" for="checkbox<?php echo $val["id"] ?>">&nbsp;</label>
                                </div>
                            </td>

                        </tr>

                    <?php endforeach; ?>

                </table>
                <div class="row">
                    <div class="col">
                        <input type="submit" value="Отмеченные удалить" id="delsubmit" class="mb-5 float-right btn btn-primary" disabled>
                    </div>
                </div>
            </form>

        <?php else : ?>
            <h1 class="text-center my-5">Неоходима авторизация</h1>
        <?php endif; ?>
    </div>



</main>
<?php include("blocks/footer.tpl.php"); ?>
<script src="<?php echo TEMPLATE_PATH ?>js/main.js"></script>
<script>
    $(function () {

        function countChecked() {
            var n = $("input:checked").length;
            if (n<1){
                $('#delsubmit').attr('disabled', 'disabled');
            }else{
                $('#delsubmit').removeAttr('disabled');
            }
        }
        $(":checkbox").change(countChecked);

        var orderModal = new ModalApp.ModalProcess({ id: 'orderModal', title: 'Чек заказа' });
        orderModal.init();


        $('.order').click(function (e) {
            e.preventDefault();
            var id = $(this).attr('id').substr(4);

            $.ajax({
                url: "/adminorders/show/order/"+id+"/",
                type: 'get',
                cache: false,
                dataType: 'html',
                beforeSend: function () {
                    $('.ploader').fadeIn(0);
                },
                complete: function () {
                    $('.ploader').delay(0).fadeOut('slow');
                },
                success: function (data) {
                    orderModal.changeFooter('');
                    orderModal.changeBody(data);
                    orderModal.showModal();
                }

            });


        });

    });
</script>
</body>
</html>