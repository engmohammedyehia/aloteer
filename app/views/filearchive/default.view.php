<div class="container">
    <?php if(array_key_exists('/filearchive/create', $__privilegesKeys)): ?>
    <a href="/filearchive/create/<?= $id ?>" class="button"><i class="fa fa-plus"></i> <?= $text_new_item ?></a>
    <?php endif; ?>
    <?php if(array_key_exists('/filearchive/zipanddownload', $__privilegesKeys)): ?>
    <?php if (false !== $files): ?>
    <a href="/filearchive/zipanddownload/<?= $id ?>" class="button"><i class="fa fa-download"></i> <?= $text_download_all ?></a>
    <?php endif; ?>
    <?php endif; ?>
    <table class="data">
        <thead>
            <tr>
                <th><?= $text_table_title ?></th>
                <th><?= $text_table_control ?></th>
            </tr>
        </thead>
        <tbody>
        <?php if(false !== $files): foreach ($files as $file): ?>
            <tr>
                <td><?= $file->FileTitle ?></td>
                <td class="controls_td">
                    <a href="javascript:;" class="open_controls"><i class="fa fa-caret-square-o-left"></i></a>
                    <div class="controls_container">
                        <?php if(array_key_exists('/filearchive/view', $__privilegesKeys)): ?>
                            <a href="/filearchive/view/<?= $file->FileId ?>"><i class="fa fa-eye"></i> <?= $text_table_control_view ?></a>
                        <?php endif; ?>
                        <?php if(array_key_exists('/filearchive/edit', $__privilegesKeys)): ?>
                            <a href="/filearchive/edit/<?= $file->FileId ?>"><i class="fa fa-edit"></i> <?= $text_table_control_edit ?></a>
                        <?php endif; ?>
                        <?php if(array_key_exists('/filearchive/delete', $__privilegesKeys)): ?>
                            <a href="/filearchive/delete/<?= $file->FileId ?>" onclick="if(!confirm('<?= $text_table_control_delete_confirm ?>')) return false;"><i class="fa fa-trash"></i> <?= $text_table_control_delete ?></a>
                        <?php endif; ?>
                        <?php if(array_key_exists('/filearchive/download', $__privilegesKeys)): ?>
                            <a href="/filearchive/download/<?= $file->FileId ?>"><i class="fa fa-download"></i> <?= $text_table_control_download ?></a>
                        <?php endif; ?>
                    </div>
                </td>
            </tr>
        <?php endforeach; endif; ?>
        </tbody>
    </table>
</div>