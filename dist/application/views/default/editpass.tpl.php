<form method="post" id="EditPassForm">
    <div id="edit-pass-cont">
    <div class='contact-message text-danger my-2'>Пароль должен быть не менее 6 символов.</div>
    Старый пароль
    <input name="old_pass" class="form-control form-control-sm" id="old_pass" type="password"/>
    Новый пароль
    <input name="new_pass" class="form-control form-control-sm" id="new_pass" type="password"/>
    И еще раз
    <input name="new_pass2" class="form-control form-control-sm" id="new_pass2" type="password"/>
    <br/>
    <input id="EditPass_submit" type="submit"
           class="btn btn-primary" value="Сменить"/>

    </div>
</form>
