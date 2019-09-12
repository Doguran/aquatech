<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="<?php echo TEMPLATE_PATH ?>img/favicon.png">
    <link rel="stylesheet" href="<?php echo TEMPLATE_PATH ?>css/fancybox.css">
    <link rel="stylesheet" href="<?php echo TEMPLATE_PATH ?>css/tablesaw.css">
    <link rel="stylesheet" href="<?php echo TEMPLATE_PATH ?>css/main.css">
    <title><?php echo $this->cat_name ?> | Aqua Tecnica</title>
</head>
<body>
<?php include("blocks/header.tpl.php"); ?>
<main role="main">



    <div class="container mb-3">

                <ul class="nav nav-pills">
                   <?php echo $this->categories ?>
                </ul>

    </div>



    <div class="container">
        <div class="alert text-center bg-info text-white" role="alert">
            <?php echo $this->cat_name ?>
        </div>
    </div>
    <?php echo $this->table; ?>

    <div class="container mb-3">

        <ul class="nav nav-pills">
            <?php echo $this->categories ?>
        </ul>

    </div>
</main>
<?php include("blocks/footer.tpl.php"); ?>
<script src="<?php echo TEMPLATE_PATH ?>js/main.js"></script>
<script src="<?php echo TEMPLATE_PATH ?>js/fancybox.js"></script>
<script src="<?php echo TEMPLATE_PATH ?>js/tablesaw.js"></script>
<script>
    TablesawConfig = {
        i18n: {
            modeStack: 'Stack',
            modeSwipe: 'Swipe',
            modeToggle: 'Toggle',
            modeSwitchColumnsAbbreviated: 'Cols',
            modeSwitchColumns: 'Columns',
            columnToggleButton: 'Columns',
            columnToggleError: 'No eligible columns.',
            sort: 'Сортировка',
            swipePreviousColumn: 'Previous column',
            swipeNextColumn: 'Next column'
        }
    };
</script>
</body>
</html>