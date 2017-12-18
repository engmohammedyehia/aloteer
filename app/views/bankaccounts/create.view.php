<form autocomplete="off" class="appForm clearfix" method="post" enctype="application/x-www-form-urlencoded">
    <fieldset>
        <legend><?= $text_legend ?></legend>
        <div class="input_wrapper n40 border required">
            <label<?= $this->labelFloat('BankName') ?>><?= $text_label_BankName ?> <span class="required">*</span></label>
            <input required type="text" name="BankName" id="BankName" value="<?= $this->showValue('BankName') ?>">
        </div>
        <div class="input_wrapper n30 padding border">
            <label<?= $this->labelFloat('BankAccountNumber') ?>><?= $text_label_BankAccountNumber ?> <span class="required">*</span></label>
            <input required data-language="en" type="text" name="BankAccountNumber" id="BankAccountNumber" value="<?= $this->showValue('BankAccountNumber') ?>" maxlength="20">
        </div>
        <div class="input_wrapper n30 padding">
            <label<?= $this->labelFloat('BankAccountIBAN') ?>><?= $text_label_BankAccountIBAN ?> <span class="required">*</span></label>
            <input required data-language="en" type="text" name="BankAccountIBAN" id="BankAccountIBAN" value="<?= $this->showValue('BankAccountIBAN') ?>" maxlength="30">
        </div>
        <input type="hidden" name="token" value="<?= $this->_registry->session->CSRFToken ?>">
        <input class="no_float" type="submit" name="submit" value="<?= $text_label_save ?>">
    </fieldset>
</form>