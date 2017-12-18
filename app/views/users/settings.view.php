<form autocomplete="off" class="appForm clearfix" method="post" enctype="multipart/form-data">
    <fieldset>
        <legend><?= $text_legend_settings ?></legend>
        <?php if ((int) $this->session->u->GroupId === 4) { ?>
        <div class="input_wrapper_other">
            <label><?= $text_disable_app ?></label>
            <label class="radio">
                <input required type="radio" name="DisableApp" id="DisableApp" <?= @$userSettings['DisableApp'] == 1 ? 'checked' : '' ?> value="1">
                <div class="radio_button"></div>
                <span><?= $text_disable_app_enable ?></span>
            </label>
            <label class="radio">
                <input required type="radio" name="DisableApp" id="DisableApp" <?= @$userSettings['DisableApp'] == 2 ? 'checked' : '' ?> value="2">
                <div class="radio_button"></div>
                <span><?= $text_disable_app_disable ?></span>
            </label>
        </div>
        <div class="input_wrapper_other required">
            <label><?= $text_label_disable_app_message ?></label>
            <textarea name="DisableAppMessage" id="DisableAppMessage" cols="30" rows="10"><?= @$userSettings['DisableAppMessage'] ?></textarea>
        </div>
        <?php } else { ?>
        <p>&nbsp;</p>
        <p><?= $text_no_settings_yet ?></p>
        <p>&nbsp;</p>
        <?php } ?>
        <input type="hidden" name="token" value="<?= $this->_registry->session->CSRFToken ?>">
        <input class="no_float" type="submit" name="submit" value="<?= $text_label_save ?>">
    </fieldset>
</form>