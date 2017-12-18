<div class="container">
    <a href="/notifications/readall" class="button" onclick="if(!confirm('<?= $text_table_control_view_confirm ?>')) return false;"><i class="fa fa-eye"></i> <?= $text_new_item ?></a>
    <a href="/notifications/truncate" class="button" onclick="if(!confirm('<?= $text_table_control_truncate_confirm ?>')) return false;"><i class="fa fa-trash"></i> <?= $text_new_item_2 ?></a>
    <table class="data">
        <thead>
            <tr>
                <th><?= $text_table_title ?></th>
                <th><?= $text_table_datetime ?></th>
                <th><?= $text_table_control ?></th>
            </tr>
        </thead>
        <tbody>
        <?php if(false !== $notifications): foreach ($notifications as $notification): ?>
            <tr>
                <td class="<?= $notification->Seen == 0 ? 'unread' : 'read' ?>"><a href="/notifications/view/<?= $notification->NotificationId ?>"><?php $this->language->swapKey($notification->NotificationType, unserialize($notification->Content)); echo $this->language->get($notification->NotificationType) ?></a></td>
                <td><?= (new DateTime($notification->Created))->format('Y-m-d h:i a') ?></td>
                <td class="controls_td">
                    <a href="javascript:;" class="open_controls"><i class="fa fa-caret-square-o-left"></i></a>
                    <div class="controls_container">
                        <a href="/notifications/delete/<?= $notification->NotificationId ?>" onclick="if(!confirm('<?= $text_table_control_delete_confirm ?>')) return false;"><i class="fa fa-trash"></i> <?= $text_table_control_delete ?></a>
                    </div>
                </td>
            </tr>
        <?php endforeach; endif; ?>
        </tbody>
    </table>
</div>