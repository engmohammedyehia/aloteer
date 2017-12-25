<div class="container">
    <a href="/auditroutes/create" class="button"><i class="fa fa-plus"></i> <?= $text_new_item ?></a>
    <table class="data">
        <thead>
            <tr>
                <th><?= $text_table_branch ?></th>
                <th><?= $text_table_employee_name ?></th>
                <th><?= $text_table_enabled ?></th>
                <th><?= $text_table_control ?></th>
            </tr>
        </thead>
        <tbody>
        <?php if(false !== $routes): foreach ($routes as $route): ?>
            <tr>
                <td><?= $route->Branch ?></td>
                <td><?= $route->EmpName ?></td>
                <td><?= (int) $route->Enabled === 1 ? '<i class="fa fa-check"></i>' : '<i class="fa fa-times"></i>' ?></td>
                <td class="controls_td">
                    <a href="javascript:;" class="open_controls"><i class="fa fa-caret-square-o-left"></i></a>
                    <div class="controls_container">
                        <?php if(array_key_exists('/auditroutes/edit', $__privilegesKeys)): ?>
                            <a href="/auditroutes/edit/<?= $route->RouteId ?>"><i class="fa fa-edit"></i> <?= $text_table_control_edit ?></a>
                        <?php endif; ?>
                        <?php if(array_key_exists('/auditroutes/delete', $__privilegesKeys)): ?>
                            <a href="/auditroutes/delete/<?= $route->RouteId ?>" onclick="if(!confirm('<?= $text_table_control_delete_confirm ?>')) return false;"><i class="fa fa-trash"></i> <?= $text_table_control_delete ?></a>
                        <?php endif; ?>
                    </div>
                </td>
            </tr>
        <?php endforeach; endif; ?>
        </tbody>
    </table>
</div>