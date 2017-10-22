<nav class="main_navigation <?= (isset($_COOKIE['menu_opened']) && $_COOKIE['menu_opened'] == 'true') ? 'opened no_animation' : '' ?>">
    <div class="employee_info">
        <div class="profile_picture">
            <img src="/img/user.png" alt="User Profile Picture">
        </div>
        <span class="name"><?= $this->session->u->profile->FirstName ?> <?= $this->session->u->profile->LastName ?></span>
        <span class="privilege"><?= $this->session->u->GroupName ?></span>
    </div>
    <ul class="app_navigation">
        <li class="<?= $this->matchUrl('/') === true ? ' selected' : '' ?>"><a href="/"><i class="fa fa-dashboard"></i> <?= $text_general_statistics ?></a></li>
        <li class="<?= $this->matchUrl(['/bankaccounts', '/bankaccounts/default', '/bankaccounts/create', '/bankaccounts/edit']) === true ? ' selected' : '' ?>"><a href="/bankaccounts"><i class="fa fa-dollar"></i> <?= $text_bank_accounts ?></a></li>
        <li class="submenu<?= $this->matchUrl(['/clients', '/clients/default', '/clients/create', '/clients/edit', '/clients/view']) === true ? ' selected' : '' ?>">
            <a href="javascript:;"><i class="fa fa-users"></i> <?= $text_clients ?></a>
            <ul>
                <li><a class="<?= $this->matchUrl(['/clients', '/clients/default', '/clients/edit', '/clients/view']) === true ? ' selected' : '' ?>" href="/clients/default"><?= $text_clients_show ?></a></li>
                <li><a class="<?= $this->matchUrl('/clients/create') === true ? ' selected' : '' ?>" href="/clients/create"><?= $text_clients_add ?></a></li>
                <li><a class="<?= $this->matchUrl('/reports/clients') === true ? ' selected' : '' ?>" href="/reports/clients"><?= $text_clients_reports ?></a></li>
            </ul>
        </li>
        <li class="<?= $this->matchUrl(['/transactiontypes','/transactiontypes/default','/transactiontypes/create','/transactiontypes/edit']) === true ? ' selected' : '' ?>"><a href="/transactiontypes"><i class="fa fa-server"></i> <?= $text_transactions_types ?></a></li>
        <li class="<?= $this->matchUrl(['/transactionconditions','/transactionconditions/default','/transactionconditions/create','/transactionconditions/edit']) === true ? ' selected' : '' ?>"><a href="/transactionconditions"><i class="fa fa-check-square"></i> <?= $text_transactions_conditions ?></a></li>
        <li class="submenu<?= $this->matchUrl(['/transactions', '/transactions/default', '/transactions/create', '/transactions/edit', '/transactions/view', '/transactions/timeline', '/transactions/cover']) === true ? ' selected' : '' ?>">
            <a href="javascript:;"><i class="fa fa-briefcase"></i> <?= $text_transactions ?></a>
            <ul>
                <li><a class="<?= $this->matchUrl('/transactions/default') === true ? ' selected' : '' ?>" href="/transactions/default"><?= $text_transactions_show ?></a></li>
                <li><a class="<?= $this->matchUrl('/transactions/create') === true ? ' selected' : '' ?>" href="/transactions/create"><?= $text_transactions_add ?></a></li>
                <li><a class="<?= $this->matchUrl('/reports/transactions') === true ? ' selected' : '' ?>" href="/reports/transactions"><?= $text_transactions_reports ?></a></li>
            </ul>
        </li>
        <li class="submenu<?= $this->matchUrl(['/cheques', '/cheques/default', '/cheques/create', '/cheques/edit', '/cheques/view', '/cheques/timeline', '/cheques/cover']) === true ? ' selected' : '' ?>">
            <a href="javascript:;"><i class="fa fa-vcard"></i> <?= $text_cheques ?></a>
            <ul>
                <li><a class="<?= $this->matchUrl('/cheques/default') === true ? ' selected' : '' ?>" href="/cheques/default"><?= $text_cheques_show ?></a></li>
                <li><a class="<?= $this->matchUrl('/cheques/print') === true ? ' selected' : '' ?>" href="/cheques/print"><?= $text_cheques_print ?></a></li>
                <li><a class="<?= $this->matchUrl('/cheques/orders') === true ? ' selected' : '' ?>" href="/cheques/orders"><?= $text_cheques_orders ?></a></li>
            </ul>
        </li>
        <li class="<?= $this->matchUrl(['/branches','/branches/default','/branches/create','/branches/edit']) === true ? ' selected' : '' ?>"><a href="/branches"><i class="fa fa-building"></i> <?= $text_branches ?></a></li>
        <li class="submenu<?= $this->matchUrl(['/users', '/users/default', '/users/create', '/users/edit', '/usersgroups', '/usersgroups/default', '/usersgroups/create', '/usersgroups/edit', '/privileges', '/privileges/default', '/privileges/create', '/privileges/edit']) === true ? ' selected' : '' ?>">
            <a href="javascript:;"><i class="fa fa-user"></i> <?= $text_users ?></a>
            <ul>
                <li><a class="<?= $this->matchUrl(['/users', '/users/default', '/users/create', '/users/edit']) === true ? ' selected' : '' ?>" href="/users"><?= $text_users_list ?></a></li>
                <li><a class="<?= $this->matchUrl(['/usersgroups', '/usersgroups/default', '/usersgroups/create', '/usersgroups/edit']) === true ? ' selected' : '' ?>" href="/usersgroups"><?= $text_users_groups ?></a></li>
                <li><a class="<?= $this->matchUrl(['/privileges', '/privileges/default', '/privileges/create', '/privileges/edit']) === true ? ' selected' : '' ?>" href="/privileges"><?= $text_users_privileges ?></a></li>
            </ul>
        </li>
        <li><a class="<?= $this->matchUrl('/mail') === true ? ' selected' : '' ?>" href="/mail"><i class="fa fa-envelope-open"></i> <?= $text_mail ?></a></li>
        <li><a class="<?= $this->matchUrl('/reports') === true ? ' selected' : '' ?>" href="/reports"><i class="fa fa-bar-chart"></i> <?= $text_reports ?></a></li>
        <li><a class="<?= $this->matchUrl('/notifications') === true ? ' selected' : '' ?>" href="/notifications"><i class="fa fa-bell"></i> <?= $text_notifications ?></a></li>
    </ul>
    <div class="user_tools">
        <ul>
            <li><a href="/"><i class="fa fa-calendar"></i></a></li>
            <li><a href="/"><i class="fa fa-gears"></i></a></li>
            <li><a class="weatherinfo" href="javascript:;"><span class="degree"></span><span class="icon"></span></a></li>
            <li><a href="/auth/logout"><i class="fa fa-sign-out"></i></a></li>
        </ul>
    </div>
</nav>
<div class="action_view <?= (isset($_COOKIE['menu_opened']) && $_COOKIE['menu_opened'] == 'true') ? 'collapsed no_animation' : '' ?>">
<?php $messages = $this->messenger->getMessages(); if(!empty($messages)): foreach ($messages as $message): ?>
<p class="message t<?= $message[1] ?>"><?= $message[0] ?><a href="" class="closeBtn"><i class="fa fa-times"></i></a></p>
<?php endforeach;endif; ?>