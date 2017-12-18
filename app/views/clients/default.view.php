<a class="button" href="/clients/create"><i class="fa fa-plus"></i> <?= $text_clients_add ?></a>
<table class="data">
    <thead>
    <tr>
        <th><?= $text_table_name ?></th>
        <th><?= $text_table_id_number ?></th>
        <th><?= $text_table_city ?></th>
        <th><?= $text_table_mobile ?></th>
        <th><?= $text_table_transactions_count ?></th>
        <th class="controls_td"><?= $text_table_control ?></th>
    </tr>
    </thead>
    <tbody>
    <?php
    if(false !== $clients) {
        foreach ($clients as $client) {
            ?>
            <tr>
                <td><?= $client->name ?></td>
                <td><?= $client->id_number ?></td>
                <td><?= ${'text_city_' . $client->city} ?></td>
                <td><?= $client->mobile ?></td>
                <td class="center"><?= $client->transactionsCount ?></td>
                <td class="controls_td">
                    <a href="javascript:;" class="open_controls"><i class="fa fa-caret-square-o-left"></i></a>
                    <div class="controls_container">
                        <?php if(array_key_exists('/clients/view', $__privilegesKeys)): ?>
                            <a href="/clients/view/<?= $client->id ?>"><i class="fa fa-eye"></i> <?= $text_table_control_view ?></a>
                        <?php endif; ?>
                        <?php if(array_key_exists('/clients/edit', $__privilegesKeys)): ?>
                            <a href="/clients/edit/<?= $client->id ?>"><i class="fa fa-edit"></i> <?= $text_table_control_edit ?></a>
                        <?php endif; ?>
                        <?php if(array_key_exists('/clients/delete', $__privilegesKeys)): ?>
                            <a href="/clients/delete/<?= $client->id ?>" onclick="if(!confirm('<?= $text_table_control_delete_confirm ?>')) return false;"><i class="fa fa-trash"></i> <?= $text_table_control_delete ?></a>
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