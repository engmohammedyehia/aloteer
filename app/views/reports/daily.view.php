<form autocomplete="off" class="appForm clearfix" method="post" enctype="multipart/form-data">
    <fieldset>
        <legend><?= $text_legend_details ?></legend>
        <div class="input_wrapper_other n50 border">
            <label<?= $this->labelFloat('from') ?>><?= $text_label_from ?></label>
            <input required type="date" name="from" id="from" value="<?= $this->showValue('from') ?>">
        </div>
        <div class="input_wrapper_other right_padding n50 select required">
            <select data-selectivity="true" name="branch">
                <option value=""><?= $text_label_branch ?></option>
                <?php if (false !== $branches): foreach ($branches as $branch): ?>
                    <option <?= $this->selectedIf('branch', $branch->BranchId) ?> value="<?= $branch->BranchId ?>"><?= $branch->BranchName ?></option>
                <?php endforeach;endif; ?>
            </select>
        </div>
        <input type="hidden" name="token" value="<?= $this->_registry->session->CSRFToken ?>">
        <input class="no_float" type="submit" name="submit" value="<?= $text_label_save ?>">
    </fieldset>
</form>
<?php if(false !== $cheques) { ?>
    <br><br>
    <a href="javascript:;" class="button" onclick="window.print();"><i class="fa fa-print"></i> <?= $text_print ?></a>
    <table class="data report">
        <thead>
        <tr>
            <th><?= $text_table_cheque_number ?></th>
            <th><?= $text_table_client_name ?></th>
            <th data-name="<?= $text_table_bank_name ?>"><?= $text_table_bank_name ?></th>
            <th data-name="<?= $text_table_branch_name ?>"><?= $text_table_branch_name ?></th>
            <th><?= $text_table_amount ?></th>
            <th><?= $text_table_created ?></th>
        </tr>
        </thead>
        <tbody>
        <?php $total = 0; foreach ($cheques as $cheque) { ?>
            <tr>
                <td><?= $cheque->ChequeNumber ?></td>
                <td><?= $cheque->ClientName ?></td>
                <td><?= $cheque->BankName ?> - <?= $cheque->BankBranchName ?></td>
                <td><?= $cheque->BranchName ?></td>
                <td><?= $cheque->Amount ?></td>
                <td><?= $cheque->Created ?></td>
            </tr>
        <?php $total += $cheque->Amount;} ?>
        <tr style="background-color: #cccccc !important;">
            <td colspan="5" style="text-align: left;background-color: #cccccc !important;"><?= $text_total ?></td>
            <td style="background-color: #cccccc !important;"><?= $total ?></td>
        </tr>
        </tbody>
    </table>
<?php } ?>