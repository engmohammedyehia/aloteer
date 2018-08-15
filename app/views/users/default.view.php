<div class="container">
    <a href="/users/create" class="button"><i class="fa fa-plus"></i> <?= $text_new_item ?></a>
    <table class="data">
        <thead>
            <tr>
                <th><?= $text_table_username ?></th>
                <th><?= $text_table_employee ?></th>
                <th><?= $text_table_group ?></th>
                <th><?= $text_table_branch ?></th>
                <th><?= $text_table_email ?></th>
                <th><?= $text_table_subscription_date ?></th>
                <th><?= $text_table_last_login ?></th>
                <th><?= $text_table_control ?></th>
            </tr>
        </thead>
        <tbody>
        <?php if(false !== $users): foreach ($users as $user): ?>
            <tr>
                <td><?= $user->Username ?></td>
                <td><?= $user->FirstName ?> <?= $user->LastName ?></td>
                <td><?= $user->GroupName ?></td>
                <td><?= $user->BranchName ?></td>
                <td><?= $user->Email ?></td>
                <td><?= $user->SubscriptionDate ?></td>
                <td><?= $user->LastLogin ?></td>
                <td class="controls_td">
                    <a href="javascript:;" class="open_controls"><i class="fa fa-caret-square-o-left"></i></a>
                    <div class="controls_container">
                        <?php if(array_key_exists('/users/edit', $__privilegesKeys)): ?>
                            <a href="/users/edit/<?= $user->UserId ?>"><i class="fa fa-edit"></i> <?= $text_table_control_edit ?></a>
                        <?php endif; ?>
                        <?php if(array_key_exists('/users/resetpassword', $__privilegesKeys)): ?>
                            <a href="/users/resetpassword/<?= $user->UserId ?>"><i class="fa fa-key"></i> <?= $text_table_control_reset ?></a>
                        <?php endif; ?>
                        <?php if(array_key_exists('/users/suspend', $__privilegesKeys)): ?>
                            <?php if ($user->Status == 1): ?>
                                <a href="/users/suspend/<?= $user->UserId ?>"><i class="fa fa-pause-circle-o"></i> <?= $text_table_control_suspend ?></a>
                            <?php endif; ?>
                        <?php endif; ?>
                        <?php if(array_key_exists('/users/activate', $__privilegesKeys)): ?>
                            <?php if ($user->Status == 0): ?>
                                <a href="/users/activate/<?= $user->UserId ?>"><i class="fa fa-play-circle-o"></i> <?= $text_table_control_activate ?></a>
                            <?php endif; ?>
                        <?php endif; ?>
                        <?php if(array_key_exists('/users/delete', $__privilegesKeys)): ?>
                            <a href="/users/delete/<?= $user->UserId ?>" onclick="if(!confirm('<?= $text_table_control_delete_confirm ?>')) return false;"><i class="fa fa-trash"></i>  <?= $text_table_control_delete ?></a>
                        <?php endif; ?>
                    </div>
                </td>
            </tr>
        <?php endforeach; endif; ?>
        </tbody>
    </table>
</div>