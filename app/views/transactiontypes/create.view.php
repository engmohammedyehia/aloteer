<form autocomplete="off" class="appForm clearfix" method="post" enctype="application/x-www-form-urlencoded">
    <fieldset>
        <legend><?= $text_legend ?></legend>
        <div class="input_wrapper n100">
            <label<?= $this->labelFloat('TransactionType') ?>><?= $text_label_TransactionType ?></label>
            <input required type="text" name="TransactionType" id="TransactionType" maxlength="50" value="<?= $this->showValue('TransactionType') ?>">
        </div>
        <input type="hidden" name="token" value="<?= $this->_registry->session->CSRFToken ?>">
        <input class="no_float" type="submit" name="submit" value="<?= $text_label_save ?>">
    </fieldset>
</form>