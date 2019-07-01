<div class="container" id="table<?php echo $this->cat_id ?>">
        <p class="w-100 text-center bg-info py-2 text-white"><?php echo $this->cat_name; ?></p>
</div>
<div class="container mb-5">
    <div class="table-responsive">
        <table data-tablesaw-no-labels data-tablesaw-sortable data-tablesaw-sortable-switch data-tablesaw-mode="stack" class="table table-bordered table-sm exel tablesaw tablesaw-row-zebra tablesaw-stack">
            <thead>
            <tr class="bg-warning">
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col>Наименование</th>
                <th scope="col" data-tablesaw-sortable-col>Арт</th>
                <th scope="col">Описание</th>
                <th scope="col">Фото</th>
                <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-numeric>Цена<br>(руб.)</th>
                <th scope="col"></th>
            </tr>

            </thead>
            <tbody>
            <?php foreach ($this->contentArr as $val) : ?>
            <tr>
                <th scope="row" class="bg-light"><?php echo $val["name"] ?>
                <?php if(ADMIN) : ?>
                    <div class="admin-link-table">
                        <a href="/admindetail/delete/id/<?php echo $val["id"] ?>/cat/<?php echo $this->cat_id ?>/" class="del" onclick="return confirm('Действительно удалить?');"><i class="fas fa-times-circle"></i></a>
                        <a href="/admindetail/show/id/<?php echo $val["id"] ?>/" class="edit"><i class="fas fa-edit"></i></a>
                    </div>
                <?php endif; ?>
                </th>
                <td><?php echo $val["sku"] ?></td>
                <td><?php echo $val["shot_desc"] ?></td>
                <td>
                    <a data-fancybox="gallery1" href="<?php echo HTTP_PATH ?>images/product/<?php echo $val["full_img"] ?>">
                        <img src="<?php echo HTTP_PATH ?>images/product/<?php echo $val["thumb_img"] ?>" class="img-fluid" alt="<?php echo $val["name"] ?>">
                    </a>
                </td>

                <?php if ($val["price"] == "0"
                          || $val["price"] == "") : ?>

                    <td class="text-nowrap"></td>
                    <td class="text-nowrap text-md-center">
                        <a class="btn btn-light askprice  text-secondary" id="ask_<?php echo $val["id"] ?>"
                           href="javascript:void(0)"><i class="fas fa-question-circle"></i></a>
                    </td>

                <?php else : ?>

                    <td class="text-nowrap"><?php echo $val["price"] ?></td>
                    <td class="text-nowrap text-md-center">

                        <?php if (!CartController::findtoCart($val["id"])) : //если товар не в корзине ?>
                            <a title="Добавить в корзину" class="text-success"
                               id="addcart-btn<?php echo $val["id"] ?>"
                               onclick="addToCard(<?php echo $val["id"] ?>,<?php echo $val["price"] ?>)"
                               href="javascript:void(0)"><i class="fas fa-shopping-cart"></i></a>
                            <div class="text-info concealed" title="Товар уже в корзине"
                                 onclick="location.href='/cart/'"
                                 id="incart-btn<?php echo $val["id"] ?>">
                                <i class="fas fa-check-circle"></i>
                            </div>
                        <?php else : //если товар уже в корзине ?>
                            <a class="text-info" title="Товар уже в корзине"
                               id="incart-btn<?php echo $val["id"] ?>"
                               href="/cart/"><i class="fas fa-check-circle"></i></a>
                        <?php endif; ?>
                    </td>
                <?php endif ?>



            </tr>
            <?php endforeach; ?>


            </tbody>
        </table>
    </div>
</div>