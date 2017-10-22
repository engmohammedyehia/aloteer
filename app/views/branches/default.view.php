<div class="container">
    <a href="/branches/create" class="button"><i class="fa fa-plus"></i> <?= $text_new_item ?></a>
    <table class="data">
        <thead>
            <tr>
                <th><?= $text_table_branch_name ?></th>
                <th><?= $text_table_control ?></th>
            </tr>
        </thead>
        <tbody>
        <?php if(false !== $branches): foreach ($branches as $branch): ?>
            <tr>
                <td><?= $branch->BranchName ?></td>
                <td class="controls_td">
                    <a href="javascript:;" class="open_controls"><i class="fa fa-caret-square-o-left"></i></a>
                    <div class="controls_container">
                        <a href="/branches/edit/<?= $branch->BranchId ?>"><i class="fa fa-edit"></i> <?= $text_table_control_edit ?></a>
                        <a href="/branches/delete/<?= $branch->BranchId ?>" onclick="if(!confirm('<?= $text_table_control_delete_confirm ?>')) return false;"><i class="fa fa-trash"></i> <?= $text_table_control_delete ?></a>
                    </div>
                </td>
            </tr>
        <?php endforeach; endif; ?>
        </tbody>
    </table>
</div>