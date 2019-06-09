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
            Прайс-лист "Portotecnica"
        </div>
        <p class="w-100 text-center bg-info">категория</p>
    </div>
    <div class="container">
        <div class="table-responsive">
        <table data-tablesaw-no-labels data-tablesaw-sortable data-tablesaw-sortable-switch data-tablesaw-mode="stack" class="table table-bordered table-sm exel tablesaw tablesaw-row-zebra tablesaw-stack">
            <thead>
            <tr class="bg-warning">
                <th rowspan="2" scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col>Наименование</th>
                <th rowspan="2" scope="col" data-tablesaw-sortable-col>Арт</th>
                <th rowspan="2" scope="col">Описание</th>
                <th rowspan="2" scope="col">Фото</th>
                <th colspan="2" scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-numeric>Цена</th>
                <th rowspan="2" scope="col"></th>
            </tr>
            <tr class="bg-warning">
                <th scope="col">EVRO</th>
                <th scope="col">РУБ.</th>
            </tr>
            </thead>
            <tbody>

            <tr>
                <th scope="row" class="bg-light">G 134-C I 1207A-M (6м шланг)</th>
                <td>IDAF 94294</td>
                <td>120бар, 380л/ч, 1600Вт, 220В, 2800об, 17кг</td>
                <td>
                    <a data-fancybox="gallery1" href="https://portotecnica.su/images/product/cat_aced89a724878eda.jpeg">
                    <img src="https://portotecnica.su/images/product/cat_aced89a724878eda.jpeg" class="img-fluid" alt="">
                    </a>
                </td>
                <td class="text-nowrap">14 &euro;</td>
                <td class="text-nowrap">284 р.</td>
                <td class="text-nowrap text-md-center"><a href="#"><i class="fas fa-shopping-cart"></i></a></td>
            </tr>
            <tr>
                <th scope="row" class="bg-light">UNIVERSE DS 2640 T 4</th>
                <td>IDAC 44018</td>
                <td>30-180бар, 390-780л/ч, 120-65гр, 5300Вт, 380В, 2800об, 95кг</td>
                <td>
                    <a data-fancybox="gallery2" href="https://portotecnica.su/images/product/cat_d55ac0fbecfa4f4e.jpeg">
                    <img src="https://portotecnica.su/images/product/cat_d55ac0fbecfa4f4e.jpeg" class="img-fluid" alt="">
                    </a>
                </td>
                <td class="text-nowrap">2 086,80 &euro;</td>
                <td class="text-nowrap">156 447,40 р.</td>
                <td class="text-nowrap text-md-center"><a href="#"><i class="fas fa-shopping-cart"></i></a></td>
            </tr>
            <tr>
                <th scope="row" class="bg-light">3</th>
                <td>Jacob</td>
                <td>Thornton</td>
                <td>
                    <a data-fancybox="gallery3" href="https://portotecnica.su/images/product/cat_dbb85f5cb961077e.jpeg">
                        <img src="https://portotecnica.su/images/product/cat_dbb85f5cb961077e.jpeg" class="img-fluid" alt="">
                    </a>
                </td>
                <td class="text-nowrap">45 &euro;</td>
                <td class="text-nowrap">484 р.</td>
                <td class="text-nowrap text-md-center"><a href="#"><i class="fas fa-shopping-cart"></i></a></td>
            </tr>
            <tr>
                <th scope="row" class="bg-light">4</th>
                <td>Larry the Bird</td>
                <td>@twitter</td>
                <td>
                    <a data-fancybox="gallery4" href="https://portotecnica.su/images/product/cat_fe9f2fc629e3f0db.jpeg">
                        <img src="https://portotecnica.su/images/product/cat_fe9f2fc629e3f0db.jpeg" class="img-fluid" alt="">
                    </a>
                </td>
                <td class="text-nowrap">5 &euro;</td>
                <td class="text-nowrap">4 р.</td>
                <td class="text-nowrap text-md-center"><a href="#"><i class="fas fa-shopping-cart"></i></a></td>
            </tr>
            </tbody>
        </table>
        </div>
    </div>
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