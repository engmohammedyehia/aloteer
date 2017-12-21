<table class="data">
    <thead>
    <tr>
        <th><?= $text_table_transaction_id ?></th>
        <th><?= $text_table_transaction_title ?></th>
        <th><?= $text_table_transaction_created ?></th>
        <th><?= $text_table_transaction_branch ?></th>
        <th><?= $text_table_transaction_client ?></th>
        <th><?= $text_table_transaction_user ?></th>
        <th class="controls_td"><?= $text_table_control ?></th>
    </tr>
    </thead>
    <tbody>
    <?php
    if(false !== $transactions) {
        foreach ($transactions as $transaction) {
            ?>
            <tr>
                <td><?= $transaction->TransactionId ?></td>
                <td><?= $transaction->TransactionTitle ?></td>
                <td><?= $transaction->Created ?></td>
                <td><?= $transaction->BranchName ?></td>
                <td><?= $transaction->ClientName ?></td>
                <td><?= $transaction->UserName ?></td>
                <td class="controls_td">
                    <a href="javascript:;" class="open_controls"><i class="fa fa-caret-square-o-left"></i></a>
                    <div class="controls_container">
                        <?php if(array_key_exists('/transactions/view', $__privilegesKeys)): ?>
                            <a href="/transactions/view/<?= $transaction->TransactionId ?>/?d=true"><i class="fa fa-eye"></i> <?= $text_table_control_view ?></a>
                        <?php endif; ?>
                    </div>
                </td>
            </tr>
            <?php
        }
    }
    ?>
    </tbody>
</table>