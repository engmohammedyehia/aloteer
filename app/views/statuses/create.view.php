<form autocomplete="off" class="appForm clearfix" method="post" enctype="application/x-www-form-urlencoded">
    <fieldset>
        <legend><?= $text_legend_details ?></legend>
        <div class="input_wrapper_other n100">
            <label<?= $this->labelFloat('Notes') ?>><?= $text_label_Notes ?></label>
            <textarea name="Notes" id="Notes" cols="30" rows="10" placeholder="<?= $text_placeholder ?>"><?= $this->showValue('Notes') ?></textarea>
        </div>
        <input type="hidden" name="token" value="<?= $this->_registry->session->CSRFToken ?>">
        <input class="no_float" type="submit" name="submit" value="<?= $text_label_save ?>">
    </fieldset>
</form>