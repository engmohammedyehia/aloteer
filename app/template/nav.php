<?php
    // TODO:: fix this
    $__privileges = implode(',', $this->session->u->privileges);
    $__privilegesKeys = array_flip($this->session->u->privileges);
?>
<nav class="main_navigation <?= (isset($_COOKIE['menu_opened']) && $_COOKIE['menu_opened'] == 'true') ? 'opened no_animation' : '' ?>">
    <div class="employee_info">
        <div class="profile_picture">
            <img width="80" height="80" src="<?= $this->session->u->profile->Image === null ? '/img/user.png' : '/uploads/images/' . $this->session->u->profile->Image ?>" alt="User Profile Picture">
        </div>
        <span class="name"><?= $this->session->u->profile->FirstName ?> <?= $this->session->u->profile->LastName ?></span>
        <span class="privilege"><?= $this->session->u->GroupName ?></span>
    </div>
    <ul class="app_navigation">
        <li class="<?= $this->matchUrl('/') === true ? ' selected' : '' ?>"><a href="/"><i class="fa fa-dashboard"></i> <?= $text_general_statistics ?></a></li>

        <?php if((bool) preg_match('/bankaccounts/', $__privileges)): ?>
        <li class="<?= $this->matchUrl(['/bankaccounts', '/bankaccounts/default', '/bankaccounts/create', '/bankaccounts/edit']) === true ? ' selected' : '' ?>"><a href="/bankaccounts"><i class="fa fa-dollar"></i> <?= $text_bank_accounts ?></a></li>
        <?php endif; ?>

        <?php if((bool) preg_match('/bankbranches/', $__privileges)): ?>
        <li class="<?= $this->matchUrl(['/bankbranches', '/bankbranches/default', '/bankbranches/create', '/bankbranches/edit']) === true ? ' selected' : '' ?>"><a href="/bankbranches"><i class="fa fa-globe"></i> <?= $text_bank_branches ?></a></li>
        <?php endif; ?>

        <?php if((bool) preg_match('/clients/', $__privileges)): ?>
        <li class="submenu<?= $this->matchUrl(['/clients', '/clients/default', '/clients/create', '/clients/edit', '/clients/view']) === true ? ' selected' : '' ?>">
            <a href="javascript:;"><i class="fa fa-users"></i> <?= $text_clients ?></a>
            <ul>
                <?php if(array_key_exists('/clients', $__privilegesKeys) || array_key_exists('/clients/default', $__privilegesKeys)): ?>
                <li><a class="<?= $this->matchUrl(['/clients', '/clients/default', '/clients/edit', '/clients/view']) === true ? ' selected' : '' ?>" href="/clients/default"><?= $text_clients_show ?></a></li>
                <?php endif; ?>
                <?php if(array_key_exists('/clients/create', $__privilegesKeys)): ?>
                <li><a class="<?= $this->matchUrl('/clients/create') === true ? ' selected' : '' ?>" href="/clients/create"><?= $text_clients_add ?></a></li>
                <?php endif; ?>
                <?php if(array_key_exists('/reports/clients', $__privilegesKeys)): ?>
                <li><a class="<?= $this->matchUrl('/reports/clients') === true ? ' selected' : '' ?>" href="/reports/clients"><?= $text_clients_reports ?></a></li>
                <?php endif; ?>
            </ul>
        </li>
        <?php endif; ?>

        <?php if((bool) preg_match('/transactiontypes/', $__privileges)): ?>
        <li class="<?= $this->matchUrl(['/transactiontypes','/transactiontypes/default','/transactiontypes/create','/transactiontypes/edit']) === true ? ' selected' : '' ?>"><a href="/transactiontypes"><i class="fa fa-server"></i> <?= $text_transactions_types ?></a></li>
        <?php endif; ?>

        <?php if((bool) preg_match('/transactionconditions/', $__privileges)): ?>
        <li class="<?= $this->matchUrl(['/transactionconditions','/transactionconditions/default','/transactionconditions/create','/transactionconditions/edit']) === true ? ' selected' : '' ?>"><a href="/transactionconditions"><i class="fa fa-check-square"></i> <?= $text_transactions_conditions ?></a></li>
        <?php endif; ?>

        <?php if((bool) preg_match('/projects/', $__privileges)): ?>
        <li class="<?= $this->matchUrl(['/projects', '/projects/default', '/projects/create', '/projects/edit']) === true ? ' selected' : '' ?>"><a href="/projects"><i class="fa fa-tasks"></i> <?= $text_projects ?></a></li>
        <?php endif; ?>

        <?php if((bool) preg_match('/transactions/', $__privileges)): ?>
        <li class="submenu<?= $this->matchUrl(['/transactions', '/transactions/default', '/transactions/create', '/transactions/edit', '/transactions/view', '/transactions/timeline', '/transactions/cover']) === true ? ' selected' : '' ?>">
            <a href="javascript:;"><i class="fa fa-briefcase"></i> <?= $text_transactions ?></a>
            <ul>
                <?php if(array_key_exists('/transactions/default', $__privilegesKeys)): ?>
                <li><a class="<?= $this->matchUrl('/transactions/default') === true ? ' selected' : '' ?>" href="/transactions/default"><?= $text_transactions_show ?></a></li>
                <?php endif; ?>
                <?php if(array_key_exists('/transactions/create', $__privilegesKeys)): ?>
                <li><a class="<?= $this->matchUrl('/transactions/create') === true ? ' selected' : '' ?>" href="/transactions/create"><?= $text_transactions_add ?></a></li>
                <?php endif; ?>
                <?php if(array_key_exists('/transactions/canceled', $__privilegesKeys)): ?>
                <li><a class="<?= $this->matchUrl('/transactions/canceled') === true ? ' selected' : '' ?>" href="/transactions/canceled"><?= $text_transactions_canceled ?></a></li>
                <?php endif; ?>
                <?php if(array_key_exists('/reports/transactions', $__privilegesKeys)): ?>
                <li><a class="<?= $this->matchUrl('/reports/transactions') === true ? ' selected' : '' ?>" href="/reports/transactions"><?= $text_transactions_reports ?></a></li>
                <?php endif; ?>
            </ul>
        </li>
        <?php endif; ?>

        <?php if((bool) preg_match('/audit/', $__privileges)): ?>
        <li class="submenu<?= $this->matchUrl(['/audit', '/audit/default', '/audit/assign', '/audit/review', '/audit/reviewed']) === true ? ' selected' : '' ?>">
            <a href="javascript:;"><i class="fa fa-star-half-empty"></i> <?= $text_audit ?></a>
            <ul>
                <?php if(array_key_exists('/audit/default', $__privilegesKeys)): ?>
                <li><a class="<?= $this->matchUrl('/audit/default') === true ? ' selected' : '' ?>" href="/audit/default"><?= $text_audit_order ?></a></li>
                <?php endif; ?>
                <?php if(array_key_exists('/audit/review', $__privilegesKeys)): ?>
                <li><a class="<?= $this->matchUrl('/audit/review') === true ? ' selected' : '' ?>" href="/audit/review"><?= $text_transactions_review ?></a></li>
                <?php endif; ?>
                <?php if(array_key_exists('/audit/reviewed', $__privilegesKeys)): ?>
                <li><a class="<?= $this->matchUrl('/audit/reviewed') === true ? ' selected' : '' ?>" href="/audit/reviewed"><?= $text_transactions_reviewed ?></a></li>
                <?php endif; ?>
            </ul>
        </li>
        <?php endif; ?>

        <?php if((bool) preg_match('/auditroutes/', $__privileges)): ?>
        <li class="<?= $this->matchUrl(['/auditroutes','/auditroutes/default', '/auditroutes/create', '/auditroutes/edit']) === true ? ' selected' : '' ?>"><a href="/auditroutes"><i class="fa fa-arrows-alt"></i> <?= $text_audit_routes ?></a></li>
        <?php endif; ?>

        <?php if((bool) preg_match('/cheques/', $__privileges)): ?>
        <li class="submenu<?= $this->matchUrl(['/cheques/default', '/cheques/printing', '/cheques/printed', '/cheques/handedover', '/cheques/handoverinvoice', '/cheques/edit', '/cheques/view', '/cheques/cleared', '/cheques/canceled', '/cheques/obsoleted']) === true ? ' selected' : '' ?>">
            <a href="javascript:;"><i class="fa fa-vcard"></i> <?= $text_cheques ?></a>
            <ul>
                <?php if(array_key_exists('/cheques/default', $__privilegesKeys)): ?>
                <li><a class="<?= $this->matchUrl('/cheques/default') === true ? ' selected' : '' ?>" href="/cheques/default"><?= $text_cheques_orders ?></a></li>
                <?php endif; ?>
                <?php if(array_key_exists('/cheques/printing', $__privilegesKeys)): ?>
                <li><a class="<?= $this->matchUrl('/cheques/printing') === true ? ' selected' : '' ?>" href="/cheques/printing"><?= $text_cheques_print ?></a></li>
                <?php endif; ?>
                <?php if(array_key_exists('/cheques/printed', $__privilegesKeys)): ?>
                <li><a class="<?= $this->matchUrl('/cheques/printed') === true ? ' selected' : '' ?>" href="/cheques/printed"><?= $text_cheques_show ?></a></li>
                <?php endif; ?>
                <?php if(array_key_exists('/cheques/handedover', $__privilegesKeys)): ?>
                <li><a class="<?= $this->matchUrl('/cheques/handedover') === true ? ' selected' : '' ?>" href="/cheques/handedover"><?= $text_cheques_handed_over ?></a></li>
                <?php endif; ?>
                <?php if(array_key_exists('/cheques/cleared', $__privilegesKeys)): ?>
                <li><a class="<?= $this->matchUrl('/cheques/cleared') === true ? ' selected' : '' ?>" href="/cheques/cleared"><?= $text_cheques_cleared ?></a></li>
                <?php endif; ?>
                <?php if(array_key_exists('/cheques/canceled', $__privilegesKeys)): ?>
                <li><a class="<?= $this->matchUrl('/cheques/canceled') === true ? ' selected' : '' ?>" href="/cheques/canceled"><?= $text_cheques_canceled ?></a></li>
                <?php endif; ?>
                <?php if(array_key_exists('/cheques/obsoleted', $__privilegesKeys)): ?>
                <li><a class="<?= $this->matchUrl('/cheques/obsoleted') === true ? ' selected' : '' ?>" href="/cheques/obsoleted"><?= $text_cheques_obsoleted ?></a></li>
                <?php endif; ?>
            </ul>
        </li>
        <?php endif; ?>

        <?php if((bool) preg_match('/branches/', $__privileges)): ?>
        <li class="<?= $this->matchUrl(['/branches','/branches/default','/branches/create','/branches/edit']) === true ? ' selected' : '' ?>"><a href="/branches"><i class="fa fa-building"></i> <?= $text_branches ?></a></li>
        <?php endif; ?>

        <?php if((bool) preg_match('/users/', $__privileges) || (bool) preg_match('/usersgroups/', $__privileges) || (bool) preg_match('/privileges/', $__privileges)): ?>
        <li class="submenu<?= $this->matchUrl(['/users', '/users/default', '/users/create', '/users/edit', '/usersgroups', '/usersgroups/default', '/usersgroups/create', '/usersgroups/edit', '/privileges', '/privileges/default', '/privileges/create', '/privileges/edit']) === true ? ' selected' : '' ?>">
            <a href="javascript:;"><i class="fa fa-user"></i> <?= $text_users ?></a>
            <ul>
                <?php if((bool) preg_match('/users/', $__privileges)): ?>
                <li><a class="<?= $this->matchUrl(['/users', '/users/default', '/users/create', '/users/edit']) === true ? ' selected' : '' ?>" href="/users"><?= $text_users_list ?></a></li>
                <?php endif; ?>
                <?php if((bool) preg_match('/usersgroups/', $__privileges)): ?>
                <li><a class="<?= $this->matchUrl(['/usersgroups', '/usersgroups/default', '/usersgroups/create', '/usersgroups/edit']) === true ? ' selected' : '' ?>" href="/usersgroups"><?= $text_users_groups ?></a></li>
                <?php endif; ?>
                <?php if((bool) preg_match('/privileges/', $__privileges)): ?>
                <li><a class="<?= $this->matchUrl(['/privileges', '/privileges/default', '/privileges/create', '/privileges/edit']) === true ? ' selected' : '' ?>" href="/privileges"><?= $text_users_privileges ?></a></li>
                <?php endif; ?>
            </ul>
        </li>
        <?php endif; ?>

        <li class="submenu<?= $this->matchUrl(['/mail', '/mail/default', '/mail/new', '/mail/edit', '/users/view', '/users/forward', '/users/reply']) === true ? ' selected' : '' ?>">
            <a href="javascript:;"><i class="fa fa-envelope-open"></i> <?= $text_mail ?></a>
            <ul>
                <li><a class="<?= $this->matchUrl(['/mail', '/mail/default']) === true ? ' selected' : '' ?>" href="/mail/default"><i class="fa fa-inbox"></i> <?= $text_mail_inbox ?></a></li>
                <li><a class="<?= $this->matchUrl('/mail/sent') === true ? ' selected' : '' ?>" href="/mail/sent"><i class="fa fa-send"></i> <?= $text_mail_sent ?></a></li>
            </ul>
        </li>
        <li><a class="<?= $this->matchUrl('/reports') === true ? ' selected' : '' ?>" href="/reports"><i class="fa fa-bar-chart"></i> <?= $text_reports ?></a></li>
        <li><a class="<?= $this->matchUrl('/notifications') === true ? ' selected' : '' ?>" href="/notifications"><i class="fa fa-bell"></i> <?= $text_notifications ?></a></li>
    </ul>
    <div class="user_tools">
        <ul>
            <li><a href="/calendar"><i class="fa fa-calendar"></i></a></li>
            <li><a href="/users/settings"><i class="fa fa-gears"></i></a></li>
            <li><a class="weatherinfo" href="javascript:;"><span class="degree"></span><span class="icon"></span></a></li>
            <li><a href="/auth/logout"><i class="fa fa-sign-out"></i></a></li>
        </ul>
    </div>
</nav>
<div class="action_view <?= (isset($_COOKIE['menu_opened']) && $_COOKIE['menu_opened'] == 'true') ? 'collapsed no_animation' : '' ?>">
<?php $messages = $this->messenger->getMessages(); if(!empty($messages)): foreach ($messages as $message): ?>
<p class="message t<?= $message[1] ?>"><?= $message[0] ?><a href="" class="closeBtn"><i class="fa fa-times"></i></a></p>
<?php endforeach;endif; ?>