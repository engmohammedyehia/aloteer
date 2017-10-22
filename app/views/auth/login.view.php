<div class="action_view login">
    <?php $messages = $this->messenger->getMessages(); if(!empty($messages)): foreach ($messages as $message): ?>
        <p class="message t<?= $message[1] ?>"><?= $message[0] ?><a href="" class="closeBtn"><i class="fa fa-times"></i></a></p>
    <?php endforeach;endif; ?>
    <div class="login_box login_page special_animate">
        <form autocomplete="off" id="loginfrm" method="post" enctype="application/x-www-form-urlencoded">
            <div class="border"></div>
            <h1><?= $login_header ?></h1>
            <p id="loginerror" class="error"></p>
            <p class="overlay"></p>
            <p class="spinner"><i class="fa fa-refresh fa-spin fa-3x fa-fw"></i></p>
            <img src="/img/login-icon.png" width="120">
            <div class="input_wrapper username">
                <input required type="text" name="ucname" id="ucname" maxlength="50" placeholder="<?= $login_ucname ?>">
            </div>
            <div class="input_wrapper password">
                <input required type="password" id="ucpwd" name="ucpwd" maxlength="100" placeholder="<?= $login_ucpwd ?>">
            </div>
            <input type="submit" name="submit" value="<?= $login_button ?>">
        </form>
        <div class="center">
            <img id="loader" src="/img/gears.gif" alt=""/>
            <p id="loaderText"></p>
        </div>
    </div>
    <div class="intro animate">
        <h3><?= $text_welcome ?></h3>
        <h1><?= $text_intro_title ?></h1>
        <img src="/img/logo.png" alt="<?= $text_intro_title ?>">
        <p><?= $text_intro ?></p>
    </div>
</div>