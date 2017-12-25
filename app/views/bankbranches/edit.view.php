<form autocomplete="off" class="appForm clearfix" method="post" enctype="application/x-www-form-urlencoded">
    <fieldset>
        <legend><?= $text_legend ?></legend>
        <div class="input_wrapper n70 border required">
            <label<?= $this->labelFloat('BankBranchName', $bankBranch) ?>><?= $text_label_BankBranchName ?> <span class="required">*</span></label>
            <input required type="text" name="BankBranchName" id="BankBranchName" value="<?= $this->showValue('BankBranchName', $bankBranch) ?>">
        </div>
        <div class="input_wrapper_other padding n30 select required">
            <span class="required">*</span>
            <select required name="BranchId">
                <option value=""><?= $text_user_BranchId ?></option>
                <?php if (false !== $branches): foreach ($branches as $branch): ?>
                    <option <?= $this->selectedIf('BranchId', $branch->BranchId, $bankBranch) ?> value="<?= $branch->BranchId ?>"><?= $branch->BranchName ?></option>
                <?php endforeach;endif; ?>
            </select>
        </div>
        <input type="hidden" name="token" value="<?= $this->_registry->session->CSRFToken ?>">
        <input class="no_float" type="submit" name="submit" value="<?= $text_label_save ?>">
    </fieldset>
</form>