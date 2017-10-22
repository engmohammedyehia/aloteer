<div class="container">
    <a href="/bankaccounts/create" class="button"><i class="fa fa-plus"></i> <?= $text_new_item ?></a>
    <table class="data">
        <thead>
            <tr>
                <th><?= $text_table_bank_name ?></th>
                <th><?= $text_table_bank_account_number ?></th>
                <th><?= $text_table_bank_account_iban ?></th>
                <th><?= $text_table_control ?></th>
            </tr>
        </thead>
        <tbody>
        <?php if(false !== $accounts): foreach ($accounts as $account): ?>
            <tr>
                <td><?= ${'text_bank_' . $account->BankName} ?></td>
                <td><?= $account->BankAccountNumber ?></td>
                <td><?= $account->BankAccountIBAN ?></td>
                <td class="controls_td">
                    <a href="javascript:;" class="open_controls"><i class="fa fa-caret-square-o-left"></i></a>
                    <div class="controls_container">
                        <a href="/bankaccounts/edit/<?= $account->AccountId ?>"><i class="fa fa-edit"></i> <?= $text_table_control_edit ?></a>
                        <a href="/bankaccounts/delete/<?= $account->AccountId ?>" onclick="if(!confirm('<?= $text_table_control_delete_confirm ?>')) return false;"><i class="fa fa-trash"></i> <?= $text_table_control_delete ?></a>
                    </div>
                </td>
            </tr>
        <?php endforeach; endif; ?>
        </tbody>
    </table>
</div>