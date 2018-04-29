<form autocomplete="off" class="appForm clearfix" method="post" enctype="application/x-www-form-urlencoded">
    <fieldset>
        <legend><?= $text_legend ?></legend>
        <div class="input_wrapper n100">
            <label<?= $this->labelFloat('ProjectName') ?>><?= $text_label_ProjectName ?></label>
            <input required type="text" name="ProjectName" id="ProjectName" maxlength="50" value="<?= $this->showValue('ProjectName') ?>">
        </div>
        <input type="hidden" name="token" value="<?= $this->_registry->session->CSRFToken ?>">
        <input class="no_float" type="submit" name="submit" value="<?= $text_label_save ?>">
    </fieldset>
</form>