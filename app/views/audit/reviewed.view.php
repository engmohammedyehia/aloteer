<table class="data">
    <thead>
    <tr>
        <th>#</th>
        <th><?= $text_table_transaction_title ?></th>
        <th><?= $text_table_transaction_created ?></th>
        <th><?= $text_table_transaction_branch ?></th>
        <th><?= $text_table_transaction_client ?></th>
        <th><?= $text_table_transaction_user ?></th>
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
            </tr>
            <?php
        }
    }
    ?>
    </tbody>
</table>