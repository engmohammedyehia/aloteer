<table class="data">
    <thead>
    <tr>
        <th><?= $text_table_cheque_number ?></th>
        <th><?= $text_table_client_name ?></th>
        <th><?= $text_table_bank_name ?></th>
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
                <td><?= $order->BankName ?></td>
                <td><?= $order->Amount ?></td>
                <td><?= $order->UserName ?></td>
                <td><?= $order->Created ?></td>
                <td class="controls_td">
                    <?php if ((int) $order->Status === \PHPMVC\Models\ChequeModel::CHEQUE_ORDER_READY_BALANCE_COVERED) { ?>
                        <a href="javascript:;" class="open_controls"><i class="fa fa-caret-square-o-left"></i></a>
                        <div class="controls_container">
                            <?php if(array_key_exists('/cheques/handover', $__privilegesKeys)): ?>
                                <a href="/cheques/handover/<?= $order->ChequeId ?>" onclick="if(!confirm('<?= $text_table_control_hand_over_confirm ?>')) return false;"><i class="fa fa-handshake-o"></i> <?= $text_table_control_handover ?></a>
                            <?php endif; ?>
                        </div>
                    <?php } elseif((int) $order->Status === \PHPMVC\Models\ChequeModel::CHEQUE_ORDER_READY_BALANCE_NOT_COVERED) { ?>
                    <?= $this->language->get('text_status_10') ?>
                    <?php } else { ?>
                    <?= $this->language->get('text_title_no_approved_yet') ?>
                    <?php } ?>
                </td>
            </tr>
            <?php
        }
    }
    ?>
    </tbody>
</table>