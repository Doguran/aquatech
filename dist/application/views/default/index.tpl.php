<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="<?php echo TEMPLATE_PATH ?>css/fancybox.css">
    <link rel="stylesheet" href="<?php echo TEMPLATE_PATH ?>css/tablesaw.css">
    <link rel="stylesheet" href="<?php echo TEMPLATE_PATH ?>css/main.css">
    <title>Hello, world!</title>
</head>
<body>
<?php include("blocks/header.tpl.php"); ?>
<main role="main">
    <div class="container">
        <div id="carouselExampleIndicators" class="carousel slide carousel-fade" data-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="<?php echo TEMPLATE_PATH ?>img/slider1.jpg" class="d-block mx-auto img-fluid" alt="...">
                </div>
                <div class="carousel-item">
                    <img src="<?php echo TEMPLATE_PATH ?>img/slider2.jpg" class="d-block mx-auto img-fluid" alt="...">
                </div>
                <div class="carousel-item">
                    <img src="<?php echo TEMPLATE_PATH ?>img/slider3.jpg" class="d-block mx-auto img-fluid" alt="...">
                </div>
            </div>
            <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    </div>
    <div class="container mt-5">
        <div class="alert alert-dark" role="alert">
            <form>
                <div class="form-row">
                    <div class="col-12 col-md-6 mb-3 mb-md-0 text-center text-md-left">
                        <p class="mt-md-1 my-0">Курсы валют</p>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-row">
                            <div class="col">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroup-usd">USD</span>
                                    </div>
                                    <input type="text" class="form-control" aria-label="USD" aria-describedby="inputGroup-usd">
                                </div>
                            </div>
                            <div class="col">
                                <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-evro">EVRO</span>
                                </div>
                                <input type="text" class="form-control" aria-label="EVRO" aria-describedby="inputGroup-evro">
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
            <?php echo $this->cat_name["name"] ?>
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