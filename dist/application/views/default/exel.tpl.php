<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="<?php echo TEMPLATE_PATH ?>css/main.css">
    <title>Hello, world!</title>
</head>
<body>
<?php include("blocks/header.tpl.php"); ?>
<main role="main">

    <div class="container mt-5">
        <form action="/exel/" method="post" enctype="multipart/form-data">
            <input type="file" name="file">
            <div class="row">
                <div class="col">
                    <input type="submit" value="Оправить">
                </div>
            </div>
        </form>

    </div>





</main>
<?php include("blocks/footer.tpl.php"); ?>
<script src="<?php echo TEMPLATE_PATH ?>js/main.js"></script>
<script src="<?php echo TEMPLATE_PATH ?>js/order-modal.js"></script>
<script src="<?php echo TEMPLATE_PATH ?>js/edit-pass-modal.js"></script>
</body>
</html>