<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="<?php echo TEMPLATE_PATH ?>css/fancybox.css">
    <link rel="stylesheet" href="<?php echo TEMPLATE_PATH ?>css/tablesaw.css">
    <link rel="stylesheet" href="<?php echo TEMPLATE_PATH ?>css/main.css">
    <title>Aqua Tecnica</title>
</head>
<body>
<?php include("blocks/header.tpl.php"); ?>
<main role="main">

    <div class="container mt-5">
        <div class="alert alert-dark" role="alert">
            <form>
                <div class="form-row">
                    <div class="col-12 col-md-6 mb-3 mb-md-0 text-center text-md-left">
                        <p class="mt-md-1 my-0">Курсы валюты</p>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-row">
                            <!--                            <div class="col">-->
                            <!--                                <div class="input-group">-->
                            <!--                                    <div class="input-group-prepend">-->
                            <!--                                        <span class="input-group-text" id="inputGroup-usd">USD</span>-->
                            <!--                                    </div>-->
                            <!--                                    <input type="text" class="form-control" aria-label="USD" aria-describedby="inputGroup-usd">-->
                            <!--                                </div>-->
                            <!--                            </div>-->
                            <div class="col">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroup-evro">EVRO</span>
                                    </div>
                                    <input type="text" class="form-control" aria-label="EVRO" value="<?php echo EVRO; ?>" aria-describedby="inputGroup-evro" disabled
                                    >
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

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