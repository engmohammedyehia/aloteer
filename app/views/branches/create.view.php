<form autocomplete="off" class="appForm clearfix" method="post" enctype="application/x-www-form-urlencoded">
    <fieldset>
        <legend><?= $text_legend ?></legend>
        <div class="input_wrapper n80 border">
            <label<?= $this->labelFloat('BranchName') ?>><?= $text_label_BranchName ?></label>
            <input required type="text" name="BranchName" id="BranchName" maxlength="50" value="<?= $this->showValue('BranchName') ?>">
        </div>
        <div class="input_wrapper_other n20 padding">
            <label<?= $this->labelFloat('Color') ?>><?= $text_label_Color ?></label>
            <input required type="color" name="Color" id="Color" value="<?= $this->showValue('Color') ?>">
        </div>
        <input type="hidden" name="token" value="<?= $this->_registry->session->CSRFToken ?>">
        <input class="no_float" type="submit" name="submit" value="<?= $text_label_save ?>">
    </fieldset>
</form>