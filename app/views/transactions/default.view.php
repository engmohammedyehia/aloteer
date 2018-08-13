<?php if(array_key_exists('/transactions/create', $__privilegesKeys)): ?>
<a class="button" href="/transactions/create"><i class="fa fa-plus"></i> <?= $text_add ?></a>
<?php endif; ?>
<table class="data">
    <thead>
    <tr>
        <th><?= $text_table_transaction_id ?></th>
        <th><?= $text_table_transaction_title ?></th>
        <th><?= $text_table_transaction_created ?></th>
        <th><?= $text_table_transaction_branch ?></th>
        <th><?= $text_table_transaction_client ?></th>
        <th><?= $text_table_transaction_payment ?></th>
        <th><?= $text_table_transaction_cheque ?></th>
        <th><?= $text_table_transaction_user ?></th>
        <th><?= $text_table_transaction_updated ?></th>
        <th><?= $text_table_transaction_updated_by ?></th>
        <th><?= $text_table_transaction_status ?></th>
        <th><?= $text_table_transaction_satisfied ?></th>
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
                <td><?= $transaction->Payment ?></td>
                <td><?= $transaction->ChequeNumber ? $transaction->ChequeNumber : $text_table_transaction_cheque_no_issued ?></td>
                <td><?= $transaction->UserName ?></td>
                <td><?= (new \DateTime($transaction->Updated))->format('Y-m-d') ?></td>
                <td><?= $transaction->UpdatingUser ?></td>
                <td><?= ${'text_status_' . $transaction->StatusType} ?> <?= ((int) $transaction->StatusType !== \PHPMVC\Models\TransactionStatusModel::STATUS_TRANSACTION_CEO_REVIEW) ? $transaction->StatusUser : '' ?> <?= $transaction->StatusNote !== '' ? ' - ' . $transaction->StatusNote : '' ?></td>
                <td><?= $transaction->Audited == 1 ? '<i class="fa fa-check"></i>' : '<i class="fa fa-times"></i>' ?></td>
                <td class="controls_td">
                    <a href="javascript:;" class="open_controls"><i class="fa fa-caret-square-o-left"></i></a>
                    <div class="controls_container">
                        <?php if(array_key_exists('/transactions/correction', $__privilegesKeys) && (int) $transaction->StatusType === \PHPMVC\Models\TransactionStatusModel::STATUS_TRANSACTION_RETURNED): ?>
                            <a href="/transactions/correction/<?= $transaction->TransactionId ?>" onclick="if(!confirm('<?= $text_table_control_correction_confirm ?>')) return false;"><i class="fa fa-thumbs-up"></i> <?= $text_table_control_correction ?></a>
                        <?php endif; ?>

                        <?php if(array_key_exists('/transactions/view', $__privilegesKeys)): ?>
                        <a href="/transactions/view/<?= $transaction->TransactionId ?>"><i class="fa fa-eye"></i> <?= $text_table_control_view ?></a>
                        <?php endif; ?>

                        <?php if ((int) $transaction->StatusType !== \PHPMVC\Models\TransactionStatusModel::STATUS_TRANSACTION_CLOSED): ?>
                        <?php if(array_key_exists('/transactions/edit', $__privilegesKeys)): ?>
                        <a href="/transactions/edit/<?= $transaction->TransactionId ?>"><i class="fa fa-edit"></i> <?= $text_table_control_edit ?></a>
                        <?php endif; ?>
                        <?php if(array_key_exists('/transactions/delete', $__privilegesKeys)): ?>
                        <a href="/transactions/delete/<?= $transaction->TransactionId ?>" onclick="if(!confirm('<?= $text_table_control_delete_confirm ?>')) return false;"><i class="fa fa-trash"></i> <?= $text_table_control_delete ?></a>
                        <?php endif; ?>
                        <?php endif; ?>

                        <?php if(array_key_exists('/transactions/timeline', $__privilegesKeys)): ?>
                        <a href="/transactions/timeline/<?= $transaction->TransactionId ?>"><i class="fa fa-bar-chart"></i> <?= $text_table_control_timeline ?></a>
                        <?php endif; ?>

                        <?php if(array_key_exists('/filearchive/default', $__privilegesKeys)): ?>
                        <a href="/filearchive/default/<?= $transaction->TransactionId ?>"><i class="fa fa-archive"></i> <?= $text_table_control_archive ?></a>
                        <?php endif; ?>

                        <?php if(array_key_exists('/statuses/create', $__privilegesKeys)): ?>
                        <?php if ((int) $transaction->StatusType === \PHPMVC\Models\TransactionStatusModel::STATUS_TRANSACTION_CREATED): ?>
                            <a href="/statuses/create/<?= $transaction->TransactionId ?>/<?= $transaction->StatusType ?>"><i class="fa fa-thumbs-up"></i> <?= ${'text_table_control_status_create_' . $transaction->StatusType} ?></a>
                        <?php endif; ?>
                        <?php endif; ?>

                        <?php if(array_key_exists('/audit/assign', $__privilegesKeys)): ?>
                        <?php if ((int) $transaction->StatusType === \PHPMVC\Models\TransactionStatusModel::STATUS_TRANSACTION_APPROVED_BY_MANAGER): ?>
                            <a href="/audit/assign/<?= $transaction->TransactionId ?>"><i class="fa fa-server"></i> <?= $text_table_control_assign_for_audition ?></a>
                        <?php endif; ?>
                        <?php endif; ?>

                        <?php if(array_key_exists('/transactions/executiveconfirm', $__privilegesKeys)): ?>
                        <?php if ((int) $transaction->Audited === 1 && (int) $transaction->StatusType === \PHPMVC\Models\TransactionStatusModel::STATUS_TRANSACTION_REVIEWED): ?>
                            <a href="/transactions/executiveconfirm/<?= $transaction->TransactionId ?>" onclick="if(!confirm('<?= $text_table_control_executive_confirm_confirm ?>')) return false;"><i class="fa fa-check"></i> <?= $text_table_control_executive_confirm ?></a>
                        <?php endif; ?>
                        <?php endif; ?>

                        <?php if(array_key_exists('/cheques/order', $__privilegesKeys)): ?>
                            <?php if ((int) $transaction->ChequeOrdered !== 1 && (int) $transaction->Audited === 1 && (int) $transaction->StatusType === \PHPMVC\Models\TransactionStatusModel::STATUS_TRANSACTION_CEO_REVIEW): ?>
                                <a href="/cheques/order/<?= $transaction->TransactionId ?>"><i class="fa fa-money"></i> <?= $text_table_control_order_cheque ?></a>
                            <?php endif; ?>
                        <?php endif; ?>

                        <?php if(array_key_exists('/transactions/cover', $__privilegesKeys)): ?>
                        <?php if ((int) $transaction->ChequeOrdered === 1 && (int) $transaction->Audited === 1 && $transaction->CEOApproved !== null): ?>
                            <a href="/transactions/cover/<?= $transaction->TransactionId ?>"><i class="fa fa-file"></i> <?= $text_table_control_cover ?></a>
                        <?php endif; ?>
                        <?php endif; ?>

                        <?php if(array_key_exists('/transactions/chequeready', $__privilegesKeys)): ?>
                        <?php if ((int) $transaction->ChequeOrdered === 1 && (int) $transaction->Audited === 1 && $transaction->CEOApproved !== null && ((int) $transaction->StatusType === \PHPMVC\Models\TransactionStatusModel::STATUS_TRANSACTION_CHEQUE_READY_NO_COVERAGE || (int) $transaction->ChequePrinted === \PHPMVC\Models\ChequeModel::CHEQUE_ORDER_PRINTED)): ?>
                            <a href="/transactions/chequeready/<?= $transaction->TransactionId ?>"><i class="fa fa-check"></i> <?= $text_table_control_confirm_cheque_ready ?></a>
                        <?php endif; ?>
                        <?php endif; ?>

                        <?php if(array_key_exists('/transactions/close', $__privilegesKeys)): ?>
                        <?php if ((int) $transaction->ChequeOrdered === 1 && (int) $transaction->Audited === 1 && (int) $transaction->StatusType === \PHPMVC\Models\TransactionStatusModel::STATUS_TRANSACTION_CHEQUE_CLEARED): ?>
                            <a href="/transactions/close/<?= $transaction->TransactionId ?>" onclick="if(!confirm('<?= $text_table_control_close_confirm ?>')) return false;"><i class="fa fa-lock"></i> <?= $text_table_control_close ?></a>
                        <?php endif; ?>
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