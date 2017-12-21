<form autocomplete="off" class="appForm clearfix" method="post" enctype="application/x-www-form-urlencoded">
    <fieldset>
        <legend><?= $text_legend ?></legend>
        <div class="input_wrapper n50 border required">
            <label<?= $this->labelFloat('BankName') ?>><?= $text_label_BankName ?> <span class="required">*</span></label>
            <input required type="text" name="BankName" id="BankName" value="<?= $this->showValue('BankName') ?>">
        </div>
        <div class="input_wrapper n50 required padding">
            <label<?= $this->labelFloat('BankAccountOwner') ?>><?= $text_label_BankAccountOwner ?> <span class="required">*</span></label>
            <input required type="text" name="BankAccountOwner" id="BankAccountOwner" value="<?= $this->showValue('BankAccountOwner') ?>">
        </div>
        <div class="input_wrapper n40 border required">
            <label<?= $this->labelFloat('BankAccountUsage') ?>><?= $text_label_BankAccountUsage ?> <span class="required">*</span></label>
            <input required type="text" name="BankAccountUsage" id="BankAccountUsage" value="<?= $this->showValue('BankAccountUsage') ?>">
        </div>
        <div class="input_wrapper n30 border padding">
            <label<?= $this->labelFloat('BankAccountIBAN') ?>><?= $text_label_BankAccountIBAN ?> <span class="required">*</span></label>
            <input required data-language="en" type="text" name="BankAccountIBAN" id="BankAccountIBAN" value="<?= $this->showValue('BankAccountIBAN') ?>" maxlength="30">
        </div>
        <div class="input_wrapper_other padding n30 select">
            <select required name="BranchId">
                <option value=""><?= $text_user_BranchId ?></option>
                <?php if (false !== $branches): foreach ($branches as $branch): ?>
                    <option <?= $this->selectedIf('BranchId', $branch->BranchId) ?> value="<?= $branch->BranchId ?>"><?= $branch->BranchName ?></option>
                <?php endforeach;endif; ?>
            </select>
        </div>
        <input type="hidden" name="token" value="<?= $this->_registry->session->CSRFToken ?>">
        <input class="no_float" type="submit" name="submit" value="<?= $text_label_save ?>">
    </fieldset>
</form>