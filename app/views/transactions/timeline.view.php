<table class="data">
    <thead>
    <tr>
        <th><?= $text_table_transaction_status_type ?></th>
        <th><?= $text_table_transaction_created ?></th>
        <th><?= $text_table_transaction_user ?></th>
        <th><?= $text_table_transaction_note ?></th>
    </tr>
    </thead>
    <tbody>
    <?php
    if(false !== $statuses) {
        foreach ($statuses as $status) {
            ?>
            <tr>
                <td><?= ${'text_status_' . $status->StatusType} ?></td>
                <td><?= $status->Created ?></td>
                <td><?= $status->UserName ?></td>
                <td><?= $status->Note ?></td>
            </tr>
            <?php
        }
    }
    ?>
    </tbody>
</table>