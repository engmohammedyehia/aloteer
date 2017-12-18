<div class="container">
    <a href="/mail/new" class="button"><i class="fa fa-plus"></i>
        <?= $text_new_button ?></a>
    <table class="data">
        <thead>
        <tr>
            <th><?= $text_title; ?></th>
            <th><?= $text_sender; ?></th>
            <th class="center"><?= $text_datetime; ?></th>
        </tr>
        </thead>
        <tbody>
        <?php
        if($mail !== false) {
            foreach($mail as $mailObj) {
                ?>
                <tr>
                    <td<?= ($mailObj->seen == 0) ? ' class="unread"' : ' class="read"' ?>><a href="/mail/view/<?= $mailObj->id ?>"><?= $mailObj->title; ?></a></td>
                    <td><?= $mailObj->receiver; ?></td>
                    <td class="center" style="width: 150px"><?= $mailObj->created; ?></td>
                </tr>
                <?php
            }
        }
        ?>
        </tbody>
    </table>
</div>