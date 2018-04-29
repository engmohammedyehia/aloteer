<div class="container">
    <a href="/projects/create" class="button"><i class="fa fa-plus"></i> <?= $text_new_item ?></a>
    <table class="data">
        <thead>
            <tr>
                <th><?= $text_table_project_name ?></th>
                <th><?= $text_table_control ?></th>
            </tr>
        </thead>
        <tbody>
        <?php if(false !== $projects): foreach ($projects as $branch): ?>
            <tr>
                <td><?= $branch->ProjectName ?></td>
                <td class="controls_td">
                    <a href="javascript:;" class="open_controls"><i class="fa fa-caret-square-o-left"></i></a>
                    <div class="controls_container">
                        <?php if(array_key_exists('/projects/edit', $__privilegesKeys)): ?>
                        <a href="/projects/edit/<?= $branch->ProjectId ?>"><i class="fa fa-edit"></i> <?= $text_table_control_edit ?></a>
                        <?php endif; ?>
                        <?php if(array_key_exists('/projects/delete', $__privilegesKeys)): ?>
                        <a href="/projects/delete/<?= $branch->ProjectId ?>" onclick="if(!confirm('<?= $text_table_control_delete_confirm ?>')) return false;"><i class="fa fa-trash"></i> <?= $text_table_control_delete ?></a>
                        <?php endif; ?>
                    </div>
                </td>
            </tr>
        <?php endforeach; endif; ?>
        </tbody>
    </table>
</div>