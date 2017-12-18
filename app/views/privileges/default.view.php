<div class="container">
    <a href="/privileges/create" class="button"><i class="fa fa-plus"></i> <?= $text_new_item ?></a>
    <table class="data">
        <thead>
            <tr>
                <th><?= $text_table_privilege ?></th>
                <th><?= $text_table_url ?></th>
                <th><?= $text_table_control ?></th>
            </tr>
        </thead>
        <tbody>
        <?php if(false !== $privileges): foreach ($privileges as $privilege): ?>
            <tr>
                <td><?= $privilege->PrivilegeTitle ?></td>
                <td style="direction: ltr"><?= $privilege->Privilege ?></td>
                <td>
                    <?php if(array_key_exists('/privileges/edit', $__privilegesKeys)): ?>
                        <a href="/privileges/edit/<?= $privilege->PrivilegeId ?>"><i class="fa fa-edit"></i></a>
                    <?php endif; ?>
                    <?php if(array_key_exists('/privileges/delete', $__privilegesKeys)): ?>
                        <a href="/privileges/delete/<?= $privilege->PrivilegeId ?>" onclick="if(!confirm('<?= $text_table_control_delete_confirm ?>')) return false;"><i class="fa fa-trash"></i></a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; endif; ?>
        </tbody>
    </table>
</div>