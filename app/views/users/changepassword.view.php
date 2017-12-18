<form autocomplete="off" class="appForm clearfix" method="post" enctype="multipart/form-data">
    <fieldset>
        <legend><?= $text_legend_change_password ?></legend>
        <div class="input_wrapper n40 border required">
            <label<?= $this->labelFloat('OldPassword') ?>><?= $text_label_OldPassword ?> <span class="required">*</span></label>
            <input required type="password" name="OldPassword" value="<?= $this->showValue('OldPassword') ?>">
        </div>
        <div class="input_wrapper n30 border required padding">
            <label<?= $this->labelFloat('Password') ?>><?= $text_label_Password ?> <span class="required">*</span></label>
            <input required type="password" name="Password" value="<?= $this->showValue('Password') ?>">
        </div>
        <div class="input_wrapper n30 padding required">
            <label<?= $this->labelFloat('PasswordConfirm') ?>><?= $text_label_PasswordConfirm ?> <span class="required">*</span></label>
            <input required type="password" name="PasswordConfirm" value="<?= $this->showValue('PasswordConfirm') ?>">
        </div>
        <input type="hidden" name="token" value="<?= $this->_registry->session->CSRFToken ?>">
        <input class="no_float" type="submit" name="submit" value="<?= $text_label_save ?>">
    </fieldset>
</form>