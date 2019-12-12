<?php
    /**
     * @var $forget int
     */
?>
<div class="row">
    <div class="col">
        <h3 class="text-center">Призначення сайту</h3>
        <p class="lead">Основним призначенням сайту є створення офіційного інтерактивного веб-ресурсу для викладача в мережі Інтернет.</p>
        <h3 class="text-center">Мета створення сайту</h3>
        <p class="lead">Метою створення сайту є забезпечення обміну необхідною додатковою літературою між студентом та викладачем.</p>
        <h3 class="text-center">Основні завдання сайту</h3>
        <ul class="lead">
            <li>Надання можливості отримати своєчасно та вільно актуальні файли з предметів, що викладає викладач.</li>
            <li>Налагодження зворотного зв’язку із викладачем.</li>
        </ul>
    </div>
</div>
<div class="row justify-content-center">
    <div class="col-sm-8 col-md-6 col-lg-5 col">
        <?php if (!$forget) { ?>
            <div class="card mb-0">
                <div class="card-header bg-primary">
                    Авторизація
                </div>
                <form method="post" class="mb-0" action="/main/auth">
                    <div class="card-body mb-0">
                        <div class="form-group row">
                            <label for="login" class="col-auto mt-2">Логін</label>
                            <div class="col">
                                <input class="form-control" id="login" type="text" name="login" autofocus/>
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <label for="pwd" class="col-auto mt-2">Пароль</label>
                            <div class="col">
                                <input class="form-control" id="pwd" type="password" name="pwd"/>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row justify-content-center">
                            <button class="btn btn-primary form-control col-5" type="submit" id="btn_enter">Увійти</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="row mt-2">
                <div class="col text-center">
                    <a href="/main/forget">Забули пароль?</a><br/>
                    <a href="/main/registrate">Подати заявку на реєстрацію</a><br/>
                </div>
            </div>
        <?php } else { ?>
            <div class="card mb-0">
                <div class="card-header bg-primary">
                    Нагадування паролю
                </div>
                <form method="post" class="mb-0" action="/main/send_forget">
                    <div class="card-body mb-0">
                        <div class="form-group row mb-0">
                            <label for="email" class="col-auto mt-2">Email</label>
                            <div class="col">
                                <input class="form-control" id="email" type="email" name="email" autofocus/>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="form-group row justify-content-center">
                            <div class="col-auto">
                                <a href="/" class="btn btn-dark form-control">Повернутися</a>
                            </div>
                            <div class="col-auto">
                                <button class="btn btn-primary form-control" type="submit" id="btn_forget">Надіслати</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        <?php } ?>
    </div>
</div>
<script>
    $('#btn_enter').click(() => {
        var login = $('#login').val().trim();
        var pwd = $('#pwd').val().trim();
        if (login === "" || pwd === "") {
            showError("Потрібно ввести логін і пароль!");
            $('#login').val("");
            $('#pwd').val("");
            $('#login').focus();
            return false;
        }
        return true;
    });
    $('#btn_forget').click(() => {
        var email = $('#email').val().trim();
        if (email === "") {
            showError("Потрібно ввести адресу електронної пошти!");
            $('#email').val("");
            $('#email').focus();
            return false;
        }
        return true;
    });
</script>