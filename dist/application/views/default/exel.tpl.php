<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="<?php echo TEMPLATE_PATH ?>css/main.css">
    <title>Excel</title>
</head>
<body>
<?php include("blocks/header.tpl.php"); ?>
<main role="main">

    <div class="container mt-5">



        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Только с расширением .xlsx</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="/exel/" method="post" enctype="multipart/form-data">
                            <input type="file" class="form-control-file" name="file">
                            <div class="row">
                                <div class="col">
                                    <button type="submit" class="btn btn-primary mt-4">Отправить</button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
        <?php if($this->sheets) :?>
        <div class="container mb-3">

            <div class="row">
                <div class="col ">
                    <u><?php echo $this->message; ?></u>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    Выбор листов из файла для парсинга (можно выбрать несколько):
                </div>
            </div>
        </div>
        <form action="/exel/insert/" method="post">
            <select name="id[]" class="form-control" size="<?php echo $this->selectSize; ?>" multiple>
                <?php foreach ($this->sheets AS $key=>$val) : ?>
                    <option value="<?php echo $key."|".Helper::getChpu($val); ?>"><?php echo $val; ?></option>
                <?php endforeach; ?>
            </select>
            <div class="row">
                <div class="col">
                    <button type="submit" class="btn btn-primary mt-4">Выгрузить товары на сайт из выбранных листов</button>
                </div>
            </div>
        </form>
        <?php endif;?>

    </div>
    <div class="container mt-5">
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
            Загрузить новый файл exel
        </button>
    </div>





</main>
<?php include("blocks/footer.tpl.php"); ?>
<script src="<?php echo TEMPLATE_PATH ?>js/main.js"></script>
<script src="<?php echo TEMPLATE_PATH ?>js/order-modal.js"></script>
<script src="<?php echo TEMPLATE_PATH ?>js/edit-pass-modal.js"></script>
</body>
</html>