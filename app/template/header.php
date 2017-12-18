<header class="main">
    <a href="javascript:;" data-menu-status="<?= (isset($_COOKIE['menu_opened']) && $_COOKIE['menu_opened'] == 'true') ? 'true' : 'false' ?>" class="menu_switch <?= (isset($_COOKIE['menu_opened']) && $_COOKIE['menu_opened'] == 'true') ? 'opened no_animation' : '' ?>"><i class="fa fa-bars"></i></a>
    <h1><?= $text_dashboard ?> > <?= $title ?></h1>
    <div class="user_menu_container">
        <a href="javascript:;" class="language_switch user">
            <span><?= $text_welcome ?> <?= $this->session->u->Username ?></span>
            <i class="material-icons">keyboard_arrow_down</i>
        </a>
        <ul class="user_menu user">
            <li><a href="/users/profile"><i class="fa fa-user"></i><?= $text_profile ?></a></li>
            <li><a href="/users/changepassword"><i class="fa fa-key"></i><?= $text_change_password ?></a></li>
            <li><a href="/users/settings"><i class="fa fa-gear"></i><?= $text_account_settings ?></a></li>
            <li><a href="/auth/logout"><i class="fa fa-sign-out"></i><?= $text_log_out ?></a></li>
        </ul>
        <a href="javascript:;" class="language_switch mail"><i class="fa fa-envelope"></i><span><?= $this->startup->mailTotal instanceof ArrayIterator ? count($this->startup->mailTotal) : 0 ?></span></a>
        <ul class="user_menu mail">
            <li>
            <?php if ($this->startup->mailTotal instanceof ArrayIterator) { foreach ($this->startup->mailTotal as $mailHeaderItem) { ?>
                <a href="/mail/view/<?= $mailHeaderItem->id ?>">
                    <img width="30" src="<?= $mailHeaderItem->ProfileImage === null ? '/img/user.png' : '/uploads/images/' . $mailHeaderItem->ProfileImage ?>">
                    <span><?= $mailHeaderItem->SenderName ?></span>
                    <span><?= $mailHeaderItem->title ?></span>
                </a>
            <?php } } else { ?>
                <a href="javascript:;">
                    <img width="30" src="/img/user.png" alt="">
                    <span><?= $text_system_user ?></span>
                    <span><?= $text_mail_no_body ?></span>
                </a>
            <?php } ?>
                <a href="/mail/default" class="readmore">
                    <?= $text_view_more ?>
                </a>
            </li>
        </ul>
        <a href="javascript:;" class="language_switch notifications"><i class="fa fa-bell"></i></i><span><?= $this->startup->notificationsTotal instanceof ArrayIterator ? count($this->startup->notificationsTotal) : 0 ?></span></a>
        <ul class="user_menu notifications">
            <li>
                <?php if ($this->startup->notificationsTotal instanceof ArrayIterator) { $this->language->load('notifications.notifications'); foreach ($this->startup->notificationsTotal as $notificationHeaderItem) { ?>
                <a href="/notifications/view/<?= $notificationHeaderItem->NotificationId ?>">
                    <i class="fa fa-exclamation"></i>
                    <span><?php $notificationContent = $this->language->swapKey($notificationHeaderItem->NotificationType, unserialize($notificationHeaderItem->Content)); echo $this->language->get($notificationHeaderItem->NotificationType) ?></span>
                </a>
                <?php } } else { ?>
                <a href="javascript:;">
                    <i class="fa fa-exclamation"></i>
                    <span><?= $text_notification_no_body ?></span>
                </a>
                <?php } ?>
                <a href="/notifications/default" class="readmore">
                    <?= $text_view_more ?>
                </a>
            </li>
        </ul>
    </div>
    <a href="/language" class="language_switch"><span><?= $_SESSION['lang'] == 'ar' ? 'En' : 'عربي' ?></span> <i class="fa fa-globe"></i></a>
</header>