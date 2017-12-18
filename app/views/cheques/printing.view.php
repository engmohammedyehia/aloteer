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
                    <a href="javascript:;" class="open_controls"><i class="fa fa-caret-square-o-left"></i></a>
                    <div class="controls_container">
                        <?php if(array_key_exists('/cheques/done', $__privilegesKeys)): ?>
                            <a href="/cheques/done/<?= $order->ChequeId ?>"><i class="fa fa-print"></i> <?= $text_table_control_printed ?></a>
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