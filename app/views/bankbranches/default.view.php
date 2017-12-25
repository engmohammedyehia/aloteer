<div class="container">
    <a href="/bankbranches/create" class="button"><i class="fa fa-plus"></i> <?= $text_new_item ?></a>
    <table class="data">
        <thead>
            <tr>
                <th><?= $text_table_bank_name ?></th>
                <th><?= $text_table_bank_account_branch ?></th>
                <th><?= $text_table_control ?></th>
            </tr>
        </thead>
        <tbody>
        <?php if(false !== $branches): foreach ($branches as $branch): ?>
            <tr>
                <td><?= $branch->BankBranchName ?></td>
                <td><?= $branch->BranchName ?></td>
                <td class="controls_td">
                    <a href="javascript:;" class="open_controls"><i class="fa fa-caret-square-o-left"></i></a>
                    <div class="controls_container">
                    <?php if(array_key_exists('/bankbranches/edit', $__privilegesKeys)): ?>
                        <a href="/bankbranches/edit/<?= $branch->BankBranchId ?>"><i class="fa fa-edit"></i> <?= $text_table_control_edit ?></a>
                    <?php endif; ?>
                    <?php if(array_key_exists('/bankbranches/delete', $__privilegesKeys)): ?>
                        <a href="/bankbranches/delete/<?= $branch->BankBranchId ?>" onclick="if(!confirm('<?= $text_table_control_delete_confirm ?>')) return false;"><i class="fa fa-trash"></i> <?= $text_table_control_delete ?></a>
                    <?php endif; ?>
                    </div>
                </td>
            </tr>
        <?php endforeach; endif; ?>
        </tbody>
    </table>
</div>