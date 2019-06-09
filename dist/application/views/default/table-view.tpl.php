<div class="container">
        <p class="w-100 text-center bg-info py-2 text-white"><?php echo $this->cat_name; ?></p>
</div>
<div class="container mb-5">
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
            <?php foreach ($this->contentArr as $val) : ?>
            <tr>
                <th scope="row" class="bg-light"><?php echo $val["name"] ?></th>
                <td><?php echo $val["sku"] ?></td>
                <td><?php echo $val["shot_desc"] ?></td>
                <td>
                    <a data-fancybox="gallery1" href="<?php echo HTTP_PATH ?>images/product/<?php echo $val["full_img"] ?>">
                        <img src="<?php echo HTTP_PATH ?>images/product/<?php echo $val["thumb_img"] ?>" class="img-fluid" alt="<?php echo $val["name"] ?>">
                    </a>
                </td>
                <td class="text-nowrap"><?php echo $val["price"] ?> &euro;</td>
                <td class="text-nowrap"><?php echo $val["price"] ?> р.</td>
                <td class="text-nowrap text-md-center"><a href="#"><i class="fas fa-shopping-cart"></i></a></td>
            </tr>
            <?php endforeach; ?>


            </tbody>
        </table>
    </div>
</div>