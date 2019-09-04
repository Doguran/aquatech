<!doctype html>
<html lang="en">
<head>
    <link rel="icon" href="<?php echo TEMPLATE_PATH ?>img/favicon.png">
    <meta charset="utf-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Новости</title>
    <link rel="stylesheet" href="<?php echo TEMPLATE_PATH ?>css/fancybox.css">
    <link href="<?php echo TEMPLATE_PATH ?>css/main.css" rel="stylesheet">






</head>
<body>
<?php include("blocks/header.tpl.php"); ?>
<div class="container articles-page">
    <?php if(isset($this->text)): ?>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/article/show/page/1/">Все публикации</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?php echo $this->h1 ?></li>
            </ol>
        </nav>
        <h1 class="text-center my-5"><?php echo $this->h1 ?></h1>
        <?php If (ADMIN) : ?>
            <div class="btn-toolbar mb-3 ml-auto">
<!--                <a href="/adminarticle/show/id/--><?php //echo $this->id ?><!--/" class="btn btn-sm btn-edit mr-4 edit"><i class="fas fa-edit"></i></a>-->
                <a href="/adminarticle/delete/id/<?php echo $this->id ?>/" class="btn btn-sm btn-delete mr-4 del" onclick="return confirm('Удалить?');"><i class="fas fa-times-circle"></i></a>
            </div>
        <?php endif ?>
        <div class="row mb-5">
            <div class="col">
                <?php echo $this->text ?>
            </div>
        </div>
    <?php endif ?>

    <?php if(isset($this->article_all_list)): ?>
        <h1 class="text-center my-5"><?php echo $this->h1 ?></h1>
        <?php If (ADMIN) : ?>
<!--            <div class="btn-toolbar mb-3 ml-auto">-->
<!--                <a href="/adminarticle/add/" class="btn btn-sm btn-edit"><i class="fas fa-plus"></i> Добавить статью</a>-->
<!--            </div>-->
        <?php endif ?>
        <div class="row">
            <?php foreach ($this->article_all_list as $val) : ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h4 class="card-title"><a href="<?php echo HTTP_PATH ?>article/show/id/<?php echo $val["id"] ?>/"><?php echo $val["h1"]; ?></a></h4>
                            <p class="card-text anons">
                            <span>
                                <?php echo  strip_tags($val["text"]);  ?>
                            </span>
                            </p>
                            <p class="card-text">
                                <a href="<?php echo HTTP_PATH ?>article/show/id/<?php echo $val["id"] ?>/" class="text-muted">Читать дальше...</a>
                            </p>
                            <?php If (ADMIN) : ?>
                                <div class="admin-link-table edit"><a
                                            href="/adminarticle/show/id/<?php echo $val["id"] ?>/"><i
                                                class="fas fa-edit"></i></a></div>
                                <div class="admin-link-table del"><a
                                            href="/adminarticle/delete/id/<?php echo $val["id"] ?>/"
                                            onclick="return confirm('Действительно удалить?');"><i
                                                class="fas fa-times"></i></a></div>
                            <?php endif ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

        </div>

        <div class="row">
            <div class="col-md-12">
                <?php echo $this->paginator ?>
            </div>
        </div>
    <?php endif ?>


</div>
<?php include("blocks/footer.tpl.php"); ?>
<script src="<?php echo TEMPLATE_PATH ?>js/main.js"></script>
<script src="<?php echo TEMPLATE_PATH ?>js/fancybox.js"></script>
<script>
    $(document).ready(function() {
        $('[rel^="prettyPhoto"]').fancybox();

    });
</script>

</body>
</html>