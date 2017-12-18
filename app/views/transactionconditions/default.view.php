<div class="container">
    <a href="/transactionconditions/create" class="button"><i class="fa fa-plus"></i> <?= $text_new_item ?></a>
    <table class="data">
        <thead>
            <tr>
                <th><?= $text_table_transaction_condition ?></th>
                <th class="center"><?= $text_table_transaction_type ?></th>
                <th class="center"><?= $text_table_required ?></th>
                <th><?= $text_table_control ?></th>
            </tr>
        </thead>
        <tbody>
        <?php if(false !== $conditions): foreach ($conditions as $condition): ?>
            <tr>
                <td><?= $condition->ConditionTitle ?></td>
                <td class="center"><?= $condition->TransactionType ?></td>
                <td class="center"><?= $condition->Required == 1 ? '<i class="fa fa-check"></i>' : '<i class="fa fa-times"></i>' ?></td>
                <td class="controls_td">
                    <a href="javascript:;" class="open_controls"><i class="fa fa-caret-square-o-left"></i></a>
                    <div class="controls_container">
                        <?php if(array_key_exists('/transactionconditions/edit', $__privilegesKeys)): ?>
                            <a href="/transactionconditions/edit/<?= $condition->ConditionId ?>"><i class="fa fa-edit"></i> <?= $text_table_control_edit ?></a>
                        <?php endif; ?>
                        <?php if(array_key_exists('/transactionconditions/delete', $__privilegesKeys)): ?>
                            <a href="/transactionconditions/delete/<?= $condition->ConditionId ?>" onclick="if(!confirm('<?= $text_table_control_delete_confirm ?>')) return false;"><i class="fa fa-trash"></i> <?= $text_table_control_delete ?></a>
                        <?php endif; ?>
                    </div>
                </td>
            </tr>
        <?php endforeach; endif; ?>
        </tbody>
    </table>
</div>