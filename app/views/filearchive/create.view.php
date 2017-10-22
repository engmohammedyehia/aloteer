<form autocomplete="off" class="appForm clearfix" method="post" enctype="multipart/form-data">
    <fieldset>
        <legend><?= $text_legend ?></legend>
        <div class="input_wrapper n50 border">
            <label<?= $this->labelFloat('FileTitle') ?>><?= $text_label_FileTitle ?></label>
            <input required type="text" name="FileTitle" id="FileTitle" maxlength="100" value="<?= $this->showValue('FileTitle') ?>">
        </div>
        <div class="input_wrapper_other n50 padding">
            <label><?= $text_label_FilePath ?></label>
            <input required type="file" name="FilePath" id="FilePath">
        </div>
        <input type="hidden" name="token" value="<?= $this->_registry->session->CSRFToken ?>">
        <input class="no_float" type="submit" name="submit" value="<?= $text_label_save ?>">
    </fieldset>
</form>