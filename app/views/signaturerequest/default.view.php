<div class="container">
    <table class="data">
        <thead>
            <tr>
                <th><?= $text_table_transaction ?></th>
                <th><?= $text_table_approved ?></th>
                <th><?= $text_table_approve_date ?></th>
                <th><?= $text_table_control ?></th>
            </tr>
        </thead>
        <tbody>
        <?php if(false !== $signatures): foreach ($signatures as $signature): ?>
            <tr>
                <td><?= $signature->Transaction ?></td>
                <td><?= (int) $signature->Approved === 0 ? '<i class="fa fa-times"></i>' : '<i class="fa fa-check"></i>' ?></td>
                <td><?= $signature->ApproveDate ?></td>
                <td class="controls_td">
                    <?php if ((int) $signature->Approved === 0): ?>
                        <a href="javascript:;" class="open_controls"><i class="fa fa-caret-square-o-left"></i></a>
                        <div class="controls_container">
                            <?php if(array_key_exists('/signaturerequest/approve', $__privilegesKeys)): ?>
                                <a href="/signaturerequest/approve/<?= $signature->RequestId ?>" onclick="if(!confirm('<?= $text_table_control_approve_confirm ?>')) return false;"><i class="fa fa-thumbs-up"></i> <?= $text_table_control_approve ?></a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; endif; ?>
        </tbody>
    </table>
</div>