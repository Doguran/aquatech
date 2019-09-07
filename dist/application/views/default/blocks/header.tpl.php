<div class="ploader"></div>
<header>


    <div class="container">
        <div class="row my-3">
            <div class="col-md-3 d-flex justify-content-center justify-content-md-start">
                <div class="logo-container">
                    <a href="/"><img src="<?php echo TEMPLATE_PATH ?>img/logo.jpg"  height="100" alt=""></a>
                    <div class="buyline">Розничный магазин <?php echo $_SERVER['HTTP_HOST']; ?></div>
                </div>

            </div>
            <div class="col-md-6">
                <ul class="nav justify-content-md-end justify-content-center">

                    <li class="nav-item">
                        <a class="nav-link" href="/article/show/page/1/">Новости</a>
                    </li>
                    <?php if (isset($_SESSION["user"])) : ?>
                        <li class="nav-item"><a href="/cabinet/"
                                                class="nav-link"
                                                title="Личный кабинет">Кабинет
                            </a></li>
                        <li class="nav-item"><a
                                    href="/auth/logout/?url=<?php echo urlencode($_SERVER['REQUEST_URI']) ?>"
                                    class="nav-link" title="Выход">Выход</a>
                        </li>
                    <?php else : ?>
                        <li class="nav-item"><a title="Вход" href="#"
                                                class="nav-link"
                                                id="auth_modal">Вход</a>
                        </li>
                    <?php endif ?>

                </ul>
            </div>
            <div class="col-md-3 my-1 text-center mobil">
                <div class="row">
                    <div class="col d-flex justify-content-center py-2">
                        <div class="align-self-center px-2">
                            <span class="text-nowrap"><?php echo $_SESSION["contact"]["phone1"]; ?></span><br>
                            <span class="d-none d-sm-inline"><?php echo $_SESSION["contact"]["email"]; ?></span>
                        </div>
                    </div>
                    <div class="col d-flex justify-content-center py-2">
                        <div class="align-self-center  px-2"><a href="/cart/" class="btn btn-primary" role="button">корзина (<span class="cart-counter"><?php echo QUANTITY; ?></span>)</a></div>
                    </div>
                </div>


            </div>
        </div>
    </div>
    <?php if(ADMIN) : ?>
    <div class="container">
        <nav class="nav">
            <a class="nav-link" href="/adminorders/">Заказы</a>
            <a class="nav-link" href="/exel/">Добавить из файла excel</a>
            <a class="nav-link" href="/admindetail/add/">Добавить товар</a>
            <a class="nav-link" href="/adminedittext/contacttext/">Контакты</a>
            <a class="nav-link" href="/valute/">Валюта</a>
        </nav>
    </div>
    <?php endif; ?>
</header>


