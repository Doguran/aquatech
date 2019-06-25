<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="<?php echo TEMPLATE_PATH ?>css/main.css">
    <script src="<?php echo TEMPLATE_PATH ?>js/main.js"></script>
    <script type="text/javascript" src="<?php echo HTTP_PATH ?>ckeditor/ckeditor.js"></script>
    <title>Hello, world!</title>
</head>
<body>
<?php include("blocks/header.tpl.php"); ?>
<main role="main">

    <div class="container my-5">

        <form method="post" id="addform" enctype='multipart/form-data'>


            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a href="#tabs-1" class="nav-link active"
                       role="tab" data-toggle="tab"
                       aria-controls="tabs-1"
                       aria-selected="true">Основное</a>
                </li>
                <li class="nav-item">
                    <a href="#tabs-2" class="nav-link"
                       role="tab" data-toggle="tab" data-toggle="tab"
                       aria-controls="tabs-2"
                       aria-selected="false">SEO</a>
                </li>
            </ul>

            <div class="tab-content">
                <div id="tabs-1" class="tab-pane fade show active" role="tabpanel" aria-labelledby="tabs-1-tab">

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="name">Название товара</label>
                            <input class="form-control" type="text" value="<?php echo $this->name; ?>" name="name" id="name"  placeholder="Название">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="sku">Артикул</label>
                            <input  class="form-control" type="text" value="<?php echo $this->sku; ?>" name="sku" id="sku" placeholder="Артикул">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="price">Цена</label>
                            <input  class="form-control" type="text" value="<?php echo $this->price; ?>" name="price" id="price" placeholder="Цена">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="old_price">Старая цена</label>
                            <input  class="form-control" type="text" value="<?php echo $this->old_price; ?>" name="old_price" id="old_price" aria-describedby="oldHelpBlock" placeholder="Старая цена">
                            <small id="oldHelpBlock" class="form-text text-muted">
                                Указывается, если товар в акции.
                            </small>
                        </div>
                        <div class="form-group col-md-6">

                            <div class="custom-control custom-radio">
                                <?php $checked = $this->valuta == "R" ? " checked" : ""; ?>
                                <input type="radio" id="Radio1" name="valuta" value="R"  class="custom-control-input"<?php echo $checked ?>>
                                <label class="custom-control-label" for="Radio1">Рубль</label>
                            </div>
                            <div class="custom-control custom-radio">
                                <?php $checked = $this->valuta == "E" ? " checked" : ""; ?>
                                <input type="radio" id="Radio2" name="valuta" value="E" class="custom-control-input"<?php echo $checked ?>>
                                <label class="custom-control-label" for="Radio2">Евро</label>
                            </div>
                            <div class="custom-control custom-radio">
                                <?php $checked = $this->valuta == "D" ? " checked" : ""; ?>
                                <input type="radio" id="Radio3" name="valuta" value="D" class="custom-control-input"<?php echo $checked ?>>
                                <label class="custom-control-label" for="Radio3">Доллар</label>
                            </div>

                        </div>
                        <div class="form-group col-md-6">
                            <img src="/images/product/<?php echo $this->thumb_img; ?>" alt="">
                            <label for="photo">Изображение товара</label>
                            <input  class="form-control" type="file"  name="photo" id="photo">

                        </div>
                        <div class="form-group col-md-6 my-3">
                            <?php foreach($this->parametrs AS $key=>$val) : ?>
                                <div class="row mb-1">
                                    <div class="col-md-6"><?php echo $val["param_name"]; ?></div>
                                    <div class="col-md-6"><input class="form-control" type="text" value="<?php echo $val["param_value"]; ?>" name="parametrs[<?php echo $val["id"]; ?>]"></div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="complete">Комплектация (через запятую)</label>
                            <input  class="form-control" type="text" value="<?php echo $this->complete; ?>" name="complete" id="complete" placeholder="Комплектация">
                            <?php if (isset($this->categories)) : ?>

                                <label for="complete" class="mt-3">Категория</label>
                                <select name="new_cat_id" id="select_cat" class="select-cat form-control">
                                    <?php foreach ($this->categories AS $val) : ?>
                                        <?php $sel = ($this->cat_id == $val["id"]) ? " selected" : ""; ?>
                                        <option  value="<?php echo $val["id"]; ?>"<?php echo $sel; ?>><?php echo $val["name"]; ?></option>
                                    <?php endforeach; ; ?>
                                </select>
                            <?php endif; ?>
                        </div>



                    </div>

                    <br>
                    <!--                            <div id="editor">--><?php //echo $this->description; ?><!--</div>-->
                    <!--                            <input name="description" type="hidden">-->
                    <textarea id="editor1" class="form-control" name="description"><?php echo $this->description ?></textarea><br />
                    <script type='text/javascript'>
                        CKEDITOR.replace( 'editor1', {toolbar : 'MyToolbar'} );
                    </script>



                </div>
                <div id="tabs-2" class="tab-pane fade" role="tabpanel" aria-labelledby="tabs-2-tab">
                    <br />
                    Url: <br /><input class="form-control" type="text" value="<?php echo $this->url; ?>" name="url"><br />
                    Title: <br /><input class="form-control" type="text" value="<?php echo $this->title; ?>" name="title"><br />
                    Keywords: <br /><textarea class="form-control" name="keywords"><?php echo $this->keywords; ?></textarea><br />
                    Description: <br /><textarea class="form-control" name="seo_desc"><?php echo $this->seo_desc; ?></textarea><br /><br />
                    <input type="hidden" value="<?php echo $this->cat_id; ?>" name='cat_id'>
                    <input type="hidden" value="<?php echo $this->id; ?>" name='product_id'>
                    <input type="hidden" name="thumb_img" value="<?php echo $this->thumb_img ?>"/>
                    <input type="hidden" name="full_img" value="<?php echo $this->full_img ?>"/>
                </div>
            </div>



            <div class="contact-message my-3 text-danger"></div>
            <input class="btn btn-primary btn-add my-3" type="submit" value="Отправить">


        </form>

    </div>


</main>
<?php include("blocks/footer.tpl.php"); ?>

<div class="container my-5">

    <form method="post" id="addform" enctype='multipart/form-data'>


        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a href="#tabs-1" class="nav-link active"
                   role="tab" data-toggle="tab"
                   aria-controls="tabs-1"
                   aria-selected="true">Основное</a>
            </li>
            <li class="nav-item">
                <a href="#tabs-2" class="nav-link"
                   role="tab" data-toggle="tab" data-toggle="tab"
                   aria-controls="tabs-2"
                   aria-selected="false">SEO</a>
            </li>
        </ul>

        <div class="tab-content">
            <div id="tabs-1" class="tab-pane fade show active" role="tabpanel" aria-labelledby="tabs-1-tab">

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="name">Название товара</label>
                        <input class="form-control" type="text" value="<?php echo $this->name; ?>" name="name" id="name"  placeholder="Название">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="sku">Артикул</label>
                        <input  class="form-control" type="text" value="<?php echo $this->sku; ?>" name="sku" id="sku" placeholder="Артикул">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="price">Цена</label>
                        <input  class="form-control" type="text" value="<?php echo $this->price; ?>" name="price" id="price" placeholder="Цена">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="old_price">Старая цена</label>
                        <input  class="form-control" type="text" value="<?php echo $this->old_price; ?>" name="old_price" id="old_price" aria-describedby="oldHelpBlock" placeholder="Старая цена">
                        <small id="oldHelpBlock" class="form-text text-muted">
                            Указывается, если товар в акции.
                        </small>
                    </div>
                    <div class="form-group col-md-6">

                        <div class="custom-control custom-radio">
                            <?php $checked = $this->valuta == "R" ? " checked" : ""; ?>
                            <input type="radio" id="Radio1" name="valuta" value="R"  class="custom-control-input"<?php echo $checked ?>>
                            <label class="custom-control-label" for="Radio1">Рубль</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <?php $checked = $this->valuta == "E" ? " checked" : ""; ?>
                            <input type="radio" id="Radio2" name="valuta" value="E" class="custom-control-input"<?php echo $checked ?>>
                            <label class="custom-control-label" for="Radio2">Евро</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <?php $checked = $this->valuta == "D" ? " checked" : ""; ?>
                            <input type="radio" id="Radio3" name="valuta" value="D" class="custom-control-input"<?php echo $checked ?>>
                            <label class="custom-control-label" for="Radio3">Доллар</label>
                        </div>

                    </div>
                    <div class="form-group col-md-6">
                        <img src="/images/product/<?php echo $this->thumb_img; ?>" alt="">
                        <label for="photo">Изображение товара</label>
                        <input  class="form-control" type="file"  name="photo" id="photo">

                    </div>
                    <div class="form-group col-md-6 my-3">
                        <?php foreach($this->parametrs AS $key=>$val) : ?>
                            <div class="row mb-1">
                                <div class="col-md-6"><?php echo $val["param_name"]; ?></div>
                                <div class="col-md-6"><input class="form-control" type="text" value="<?php echo $val["param_value"]; ?>" name="parametrs[<?php echo $val["id"]; ?>]"></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="complete">Комплектация (через запятую)</label>
                        <input  class="form-control" type="text" value="<?php echo $this->complete; ?>" name="complete" id="complete" placeholder="Комплектация">
                        <?php if (isset($this->categories)) : ?>

                            <label for="complete" class="mt-3">Категория</label>
                            <select name="new_cat_id" id="select_cat" class="select-cat form-control">
                                <?php foreach ($this->categories AS $val) : ?>
                                    <?php $sel = ($this->cat_id == $val["id"]) ? " selected" : ""; ?>
                                    <option  value="<?php echo $val["id"]; ?>"<?php echo $sel; ?>><?php echo $val["name"]; ?></option>
                                <?php endforeach; ; ?>
                            </select>
                        <?php endif; ?>
                    </div>



                </div>

                <br>
                <!--                            <div id="editor">--><?php //echo $this->description; ?><!--</div>-->
                <!--                            <input name="description" type="hidden">-->
                <textarea id="editor1" class="form-control" name="description"><?php echo $this->description ?></textarea><br />
                <script type='text/javascript'>
                    CKEDITOR.replace( 'editor1', {toolbar : 'MyToolbar'} );
                </script>



            </div>
            <div id="tabs-2" class="tab-pane fade" role="tabpanel" aria-labelledby="tabs-2-tab">
                <br />
                Url: <br /><input class="form-control" type="text" value="<?php echo $this->url; ?>" name="url"><br />
                Title: <br /><input class="form-control" type="text" value="<?php echo $this->title; ?>" name="title"><br />
                Keywords: <br /><textarea class="form-control" name="keywords"><?php echo $this->keywords; ?></textarea><br />
                Description: <br /><textarea class="form-control" name="seo_desc"><?php echo $this->seo_desc; ?></textarea><br /><br />
                <input type="hidden" value="<?php echo $this->cat_id; ?>" name='cat_id'>
                <input type="hidden" value="<?php echo $this->id; ?>" name='product_id'>
                <input type="hidden" name="thumb_img" value="<?php echo $this->thumb_img ?>"/>
                <input type="hidden" name="full_img" value="<?php echo $this->full_img ?>"/>
            </div>
        </div>



        <div class="contact-message my-3 text-danger"></div>
        <input class="btn btn-primary btn-add my-3" type="submit" value="Отправить">


    </form>

</div>
</body>
</html>