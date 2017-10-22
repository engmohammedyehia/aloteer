<form autocomplete="off" class="appForm clearfix" method="post" enctype="application/x-www-form-urlencoded">
    <fieldset>
        <legend><?= $text_legend ?></legend>
        <div class="input_wrapper_other n40 left_padding select border required">
            <span class="required">*</span>
            <select data-selectivity="true" required name="BankName" id="BankName">
                <option value=""><?= $text_label_bank ?></option>
                <option value="1" <?= $this->selectedIf('BankName', 1, $account) ?>><?= $text_bank_1 ?></option>
                <option value="2" <?= $this->selectedIf('BankName', 2, $account) ?>><?= $text_bank_2 ?></option>
                <option value="3" <?= $this->selectedIf('BankName', 3, $account) ?>><?= $text_bank_3 ?></option>
                <option value="4" <?= $this->selectedIf('BankName', 4, $account) ?>><?= $text_bank_4 ?></option>
                <option value="5" <?= $this->selectedIf('BankName', 5, $account) ?>><?= $text_bank_5 ?></option>
                <option value="6" <?= $this->selectedIf('BankName', 6, $account) ?>><?= $text_bank_6 ?></option>
                <option value="7" <?= $this->selectedIf('BankName', 7, $account) ?>><?= $text_bank_7 ?></option>
                <option value="8" <?= $this->selectedIf('BankName', 8, $account) ?>><?= $text_bank_8 ?></option>
                <option value="9" <?= $this->selectedIf('BankName', 9, $account) ?>><?= $text_bank_9 ?></option>
                <option value="10" <?= $this->selectedIf('BankName', 10, $account) ?>><?= $text_bank_10 ?></option>
                <option value="11" <?= $this->selectedIf('BankName', 11, $account) ?>><?= $text_bank_11 ?></option>
                <option value="12" <?= $this->selectedIf('BankName', 12, $account) ?>><?= $text_bank_12 ?></option>
                <option value="13" <?= $this->selectedIf('BankName', 13, $account) ?>><?= $text_bank_13 ?></option>
            </select>
        </div>
        <div class="input_wrapper n30 padding border">
            <label<?= $this->labelFloat('BankAccountNumber', $account) ?>><?= $text_label_BankAccountNumber ?> <span class="required">*</span></label>
            <input required data-language="en" type="text" name="BankAccountNumber" id="BankAccountNumber" value="<?= $this->showValue('BankAccountNumber', $account) ?>" maxlength="20">
        </div>
        <div class="input_wrapper n30 padding">
            <label<?= $this->labelFloat('BankAccountIBAN', $account) ?>><?= $text_label_BankAccountIBAN ?> <span class="required">*</span></label>
            <input required data-language="en" type="text" name="BankAccountIBAN" id="BankAccountIBAN" value="<?= $this->showValue('BankAccountIBAN', $account) ?>" maxlength="30">
        </div>
        <input type="hidden" name="token" value="<?= $this->_registry->session->CSRFToken ?>">
        <input class="no_float" type="submit" name="submit" value="<?= $text_label_save ?>">
    </fieldset>
</form>