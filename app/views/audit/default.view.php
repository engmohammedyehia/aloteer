<table class="data">
    <thead>
    <tr>
        <th><?= $text_table_title ?></th>
        <th><?= $text_table_assigned_by ?></th>
        <th><?= $text_table_assigned_to ?></th>
        <th><?= $text_table_created ?></th>
    </tr>
    </thead>
    <tbody>
    <?php
    if(false !== $orders) {
        foreach ($orders as $order) {
            ?>
            <tr>
                <td><?= $order->TransactionTitle ?></td>
                <td><?= $order->AssignedByName ?></td>
                <td><?= $order->AssignedToName ?></td>
                <td><?= $order->Created ?></td>
            </tr>
            <?php
        }
    }
    ?>
    </tbody>
</table>