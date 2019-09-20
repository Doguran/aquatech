<div id="phone_form">
    <form method="post" id="phoneForm">
        <div class="error-message-phone alert alert-danger concealed"></div>
        Имя
        <input type="text" class="form-control form-control-sm"
               name="name" id="user_name"/>
        Номер телефона
        <input type="text" class="form-control form-control-sm"
               name="phone" id="user_phone"/>
        <input type="text" class="form-control d-none" name="url" id="InputUrl"
               placeholder="url">
        <br/>
        <input type="submit"
               class="btn btn-primary" id="call-btn" value="Отправить"/>
    </form>
</div>