<a class="button" href="/transactions/create"><i class="fa fa-plus"></i> <?= $text_add ?></a>
<table class="data">
    <thead>
    <tr>
        <th><?= $text_table_transaction_title ?></th>
        <th><?= $text_table_transaction_created ?></th>
        <th><?= $text_table_transaction_branch ?></th>
        <th><?= $text_table_transaction_client ?></th>
        <th><?= $text_table_transaction_user ?></th>
        <th><?= $text_table_transaction_updated ?></th>
        <th><?= $text_table_transaction_updated_by ?></th>
        <th><?= $text_table_transaction_status ?></th>
        <th class="controls_td"><?= $text_table_control ?></th>
    </tr>
    </thead>
    <tbody>
    <?php
    if(false !== $transactions) {
        foreach ($transactions as $transaction) {
            ?>
            <tr>
                <td><?= $transaction->TransactionTitle ?></td>
                <td><?= $transaction->Created ?></td>
                <td><?= $transaction->BranchName ?></td>
                <td><?= $transaction->ClientName ?></td>
                <td><?= $transaction->UserName ?></td>
                <td><?= (new \DateTime($transaction->Updated))->format('Y-m-d') ?></td>
                <td><?= $transaction->UpdatingUser ?></td>
                <td><?= ${'text_status_' . $transaction->StatusType} ?> - <?= $transaction->StatusUser ?></td>
                <td class="controls_td">
                    <a href="javascript:;" class="open_controls"><i class="fa fa-caret-square-o-left"></i></a>
                    <div class="controls_container">
                        <a href="/transactions/view/<?= $transaction->TransactionId ?>"><i class="fa fa-eye"></i> <?= $text_table_control_view ?></a>
                        <a href="/transactions/edit/<?= $transaction->TransactionId ?>"><i class="fa fa-edit"></i> <?= $text_table_control_edit ?></a>
                        <a href="/transactions/delete/<?= $transaction->TransactionId ?>" onclick="if(!confirm('<?= $text_table_control_delete_confirm ?>')) return false;"><i class="fa fa-trash"></i> <?= $text_table_control_delete ?></a>
                        <a href="/transactions/timeline/<?= $transaction->TransactionId ?>"><i class="fa fa-bar-chart"></i> <?= $text_table_control_timeline ?></a>
                        <a href="/filearchive/default/<?= $transaction->TransactionId ?>"><i class="fa fa-archive"></i> <?= $text_table_control_archive ?></a>
                        <?php if ((int) $transaction->StatusType === \PHPMVC\Models\TransactionStatusModel::STATUS_TRANSACTION_CREATED): ?>
                        <a href="/statuses/create/<?= $transaction->TransactionId ?>/<?= $transaction->StatusType ?>"><i class="fa fa-thumbs-up"></i> <?= ${'text_table_control_status_create_' . $transaction->StatusType} ?></a>
                        <?php endif; ?>
                        <a href="/transactions/assignaudition/<?= $transaction->TransactionId ?>"><i class="fa fa-search"></i> <?= $text_table_control_assign_for_audition ?></a>
                    </div>
                </td>
            </tr>
            <?php
        }
    }
    ?>
    </tbody>
</table>