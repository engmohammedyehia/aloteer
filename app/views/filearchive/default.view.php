<div class="container">
    <a href="/filearchive/create/<?= $id ?>" class="button"><i class="fa fa-plus"></i> <?= $text_new_item ?></a>
    <a href="/filearchive/zipanddownload/<?= $id ?>" class="button"><i class="fa fa-download"></i> <?= $text_download_all ?></a>
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
                        <a href="/filearchive/view/<?= $file->FileId ?>"><i class="fa fa-eye"></i> <?= $text_table_control_view ?></a>
                        <a href="/filearchive/edit/<?= $file->FileId ?>"><i class="fa fa-edit"></i> <?= $text_table_control_edit ?></a>
                        <a href="/filearchive/delete/<?= $file->FileId ?>" onclick="if(!confirm('<?= $text_table_control_delete_confirm ?>')) return false;"><i class="fa fa-trash"></i> <?= $text_table_control_delete ?></a>
                        <a href="/filearchive/download/<?= $file->FileId ?>"><i class="fa fa-download"></i> <?= $text_table_control_download ?></a>
                    </div>
                </td>
            </tr>
        <?php endforeach; endif; ?>
        </tbody>
    </table>
</div>