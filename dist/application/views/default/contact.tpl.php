<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="<?php echo TEMPLATE_PATH ?>img/favicon.png">
    <link rel="stylesheet" href="<?php echo TEMPLATE_PATH ?>css/fancybox.css">
    <link rel="stylesheet" href="<?php echo TEMPLATE_PATH ?>css/jquery-ui.css">
    <link rel="stylesheet" href="<?php echo TEMPLATE_PATH ?>css/main.css">
    <script src="https://maps.googleapis.com/maps/api/js?key=
AIzaSyAhuMsVxskqJhgjXF7FZdX_QtIhAaOhLbY"></script>
    <title>Контакты</title>
</head>
<body class="contact-page">
<?php include("blocks/header.tpl.php"); ?>
<main role="main">

    <div class="container mb-5">
        <div class="row">
            <div class="col contact-details">
                <div class="contact-details-content px-3">

                    <h1>Контактная информация</h1>
                    <div class="row">
                        <div class="col">
                            <address>
                                <?php echo $_SESSION["contact"]["address"]; ?><br>
                                <span class="tel"><?php echo $_SESSION["contact"]["phone1"]; ?></span><br>
                                <span class="tel"><?php echo $_SESSION["contact"]["phone2"]; ?></span><br>
                                <span class="tel"></span><br>
                                <a href="mailto:<?php echo $_SESSION["contact"]["email"]; ?>"><?php echo $_SESSION["contact"]["email"]; ?></a>
                            </address>
                        </div>
                        <div class="col">
                            <h5>Время работы:</h5>
                            <p><?php echo $_SESSION["contact"]["mode"]; ?></p>
                        </div>
                    </div>
                </div>
            </div>

        </div>


    </div>
    <div id="map"></div>
    <script>
        // initMap() - функция инициализации карты
        function initMap() {
            // Координаты центра на карте. Широта: 56.2928515, Долгота: 43.7866641
            var centerLatLng = new google.maps.LatLng(<?php echo $_SESSION["contact"]["maps"]; ?>);
            // Обязательные опции с которыми будет проинициализированна карта
            var mapOptions = {
                center: centerLatLng, // Координаты центра мы берем из переменной centerLatLng
                scaleControl: false,
                scrollwheel: false,
                disableDoubleClickZoom: false,
                zoom: 17               // Зум по умолчанию. Возможные значения от 0 до 21
            };
            // Создаем карту внутри элемента #map
            var map = new google.maps.Map(document.getElementById("map"), mapOptions);
            // contentString - это переменная в которой хранится содержимое информационного окна.
            // Может содержать, как HTML-код, так и обычный текст.
            // Если используем HTML, то в этом случае у нас есть возможность стилизовать окно с помощью CSS.
            var contentString = '<div class="infowindow">' +
                '<h3>Lorem ipsum dolor</h3>' +
                '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iure, sed.</p>' +
                '</div>';
            // Создаем объект информационного окна и помещаем его в переменную infoWindow
            var infoWindow = new google.maps.InfoWindow({
                content: contentString
            });
            // Добавляем маркер
            var marker = new google.maps.Marker({
                position: centerLatLng,              // Координаты расположения маркера. В данном случае координаты нашего маркера совпадают с центром карты, но разумеется нам никто не мешает создать отдельную переменную и туда поместить другие координаты.
                map: map,                            // Карта на которую нужно добавить маркер
                title: "Текст всплывающей подсказки" // (Необязательно) Текст выводимый в момент наведения на маркер
            });
            // Отслеживаем клик по нашему маркеру
            google.maps.event.addListener(marker, "click", function () {
                // infoWindow.open - показывает информационное окно.
                // Параметр map - это переменная содержащие объект карты (объявлена на 8 строке)
                // Параметр marker - это переменная содержащие объект маркера (объявлена на 23 строке)
                infoWindow.open(map, marker);
            });
            // Отслеживаем клик в любом месте карты
            google.maps.event.addListener(map, "click", function () {
                // infoWindow.close - закрываем информационное окно.
                infoWindow.close();
            });
        }

        // Ждем полной загрузки страницы, после этого запускаем initMap()
        google.maps.event.addDomListener(window, "load", initMap);
    </script>
<?php include("blocks/footer.tpl.php"); ?>
<script src="<?php echo TEMPLATE_PATH ?>js/main.js"></script>



</body>
</html>