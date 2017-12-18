<div class="container">
    <a href="/mail/new" class="button"><i class="fa fa-plus"></i>
        <?= $text_new_button ?></a>
    <table class="data">
        <thead>
        <tr>
            <th style="width: 300px"><?= $text_title; ?></th>
            <th><?= $text_sender; ?></th>
            <th><?= $text_datetime; ?></th>
            <th style="width: 80px"><?= $text_table_control ?></th>
        </tr>
        </thead>
        <tbody>
        <?php
        if($mail !== false) {
            foreach($mail as $mailObj) {
                ?>
                <tr>
                    <td<?= ($mailObj->seen == 0) ? ' class="unread"' : ' class="read"' ?>><a title="<?= $text_table_control_view ?>" href="/mail/view/<?= $mailObj->id ?>"><?= $mailObj->title; ?></a></td>
                    <td><?= $mailObj->sender; ?></td>
                    <td><?= $mailObj->created; ?></td>
                    <td class="controls_td">
                        <a href="javascript:;" class="open_controls"><i class="fa fa-caret-square-o-left"></i></a>
                        <div class="controls_container">
                            <a href="/mail/reply/<?= $mailObj->id ?>"><i class="fa fa-reply"></i> <?= $text_table_control_reply ?></a>
                            <a href="/mail/forward/<?= $mailObj->id ?>"><i class="fa fa-location-arrow"></i> <?= $text_table_control_forward ?></a>
                            <a href="/mail/delete/<?= $mailObj->id ?>/?token=<?= $this->session->CSRFToken ?>" onclick="if(!confirm('<?= $text_table_control_delete_confirm ?>')) return false;"><i class="fa fa-trash"></i> <?= $text_table_control_delete ?></a>
                        </div>
                    </td>
                </tr>
                <?php
            }
        }
        ?>
        </tbody>
    </table>
</div>