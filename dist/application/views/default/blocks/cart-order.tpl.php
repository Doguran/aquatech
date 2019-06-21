<div class="row alert alert-success">
    <div class="col">
        <?php echo $this->welcome; ?> Номер заказа: <?php echo $this->order_id; ?>
    </div>
</div>
<?php echo $this->pass_mail_text; ?>
<?php foreach ($this->basketArr AS $val) : ?>
<?php $val["name"] = isset($val["product_name"]) ? $val["product_name"] : $val["name"] ?>
<?php $val["sku"] = isset($val["product_sku"]) ? $val["product_sku"] : $val["sku"] ?>
<div class="row align-items-center text-center py-2 border-bottom">
    <div class="col-12 col-md-6 text-md-left py-0">
        <?php echo $val["name"]; ?><br><small class="text-muted"> Арт.: <?php echo $val["sku"]; ?></small>
    </div>

    <div class="col-6 col-md-3 py-0"> <?php echo $val["quantity"]; ?> шт.</div>
    <div class="col-6 col-md-3 py-0"><?php echo $val["price"]*$val["quantity"] ?> руб.</div>
</div>
<?php endforeach; ?>

<div class="row align-items-center text-center py-3">

    <div class="col-6 col-md-9 text-md-right">Способ доставки - <?php echo $this->delivery_method; ?></div>
    <div class="col-6 col-md-3"><?php echo $this->courier_price; ?> р.</div>

</div>

<div class="row align-items-center text-center lead py-3 alert alert-primary">

    <div class="col-6 col-md-8 text-md-right">Итого к оплате:</div>
    <div class="col-6 col-md-4"><?php echo $this->summa; ?> р.</div>

</div>
<div class="align-items-center pb-5">

    <p>Способ оплаты - <?php echo $this->payment_method; ?></p>
    <p><?php echo $this->contact; ?></p>
    <p>Примечание: <?php echo $this->note; ?></p>
    <p>Детали заказа были отправлены Вам на email.</p>


</div>
