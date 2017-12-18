<form autocomplete="off" class="appForm clearfix" method="post" enctype="application/x-www-form-urlencoded">
    <fieldset>
        <legend><?= $text_legend ?></legend>
        <div class="input_wrapper_other n100">
            <label><?= $text_label_BankAccountState ?> <span class="required">*</span></label>
            <label class="radio">
                <input required type="radio" name="BankAccountState" id="BankAccountState" <?= $this->radioCheckedIf('BankAccountState', \PHPMVC\Models\TransactionStatusModel::STATUS_TRANSACTION_CHEQUE_READY) ?> value="<?= \PHPMVC\Models\TransactionStatusModel::STATUS_TRANSACTION_CHEQUE_READY ?>">
                <div class="radio_button"></div>
                <span><?= $text_label_BankAccountState_1 ?></span>
            </label>
            <label class="radio">
                <input required type="radio" name="BankAccountState" id="BankAccountState" <?= $this->radioCheckedIf('BankAccountState', \PHPMVC\Models\TransactionStatusModel::STATUS_TRANSACTION_CHEQUE_READY_NO_COVERAGE) ?> value="<?= \PHPMVC\Models\TransactionStatusModel::STATUS_TRANSACTION_CHEQUE_READY_NO_COVERAGE ?>">
                <div class="radio_button"></div>
                <span><?= $text_label_BankAccountState_2 ?></span>
            </label>
        </div>
        <input type="hidden" name="token" value="<?= $this->_registry->session->CSRFToken ?>">
        <input class="no_float" type="submit" name="submit" value="<?= $text_label_save ?>">
    </fieldset>
</form>