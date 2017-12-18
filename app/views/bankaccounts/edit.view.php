<form autocomplete="off" class="appForm clearfix" method="post" enctype="application/x-www-form-urlencoded">
    <fieldset>
        <legend><?= $text_legend ?></legend>
        <div class="input_wrapper n40 border required">
            <label<?= $this->labelFloat('BankName', $account) ?>><?= $text_label_BankName ?> <span class="required">*</span></label>
            <input required type="text" name="BankName" id="BankName" value="<?= $this->showValue('BankName', $account) ?>">
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