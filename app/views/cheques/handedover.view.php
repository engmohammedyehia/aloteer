<table class="data">
    <thead>
    <tr>
        <th><?= $text_table_cheque_number ?></th>
        <th><?= $text_table_client_name ?></th>
        <th data-name="<?= $text_table_bank_name ?>"><?= $text_table_bank_name ?></th>
        <th data-name="<?= $text_table_branch_name ?>"><?= $text_table_branch_name ?></th>
        <th><?= $text_table_amount ?></th>
        <th><?= $text_table_user_name ?></th>
        <th><?= $text_table_created ?></th>
        <th><?= $text_table_control ?></th>
    </tr>
    </thead>
    <tbody>
    <?php
    if(false !== $orders) {
        foreach ($orders as $order) {
            ?>
            <tr>
                <td><?= $order->ChequeNumber ?></td>
                <td><?= $order->ClientName ?></td>
                <td><?= $order->BankName ?> - <?= $order->BankBranchName ?></td>
                <td><?= $order->BranchName ?></td>
                <td><?= $order->Amount ?></td>
                <td><?= $order->UserName ?></td>
                <td><?= $order->HandedOverDate ?></td>
                <td class="controls_td">
                    <?php if ((int) $order->Status === \PHPMVC\Models\ChequeModel::CHEQUE_ORDER_HANDED_TO_CLIENT) { ?>
                        <a href="javascript:;" class="open_controls"><i class="fa fa-caret-square-o-left"></i></a>
                        <div class="controls_container">
                            <?php if(array_key_exists('/cheques/clear', $__privilegesKeys)): ?>
                                <a href="/cheques/clear/<?= $order->ChequeId ?>" onclick="if(!confirm('<?= $text_table_control_clear_confirm ?>')) return false;"><i class="fa fa-check"></i> <?= $text_table_control_clear ?></a>
                            <?php endif; ?>
                        </div>
                    <?php } ?>
                </td>
            </tr>
            <?php
        }
    }
    ?>
    </tbody>
</table>