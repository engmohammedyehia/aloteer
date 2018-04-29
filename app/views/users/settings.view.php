<form autocomplete="off" class="appForm clearfix" method="post" enctype="multipart/form-data">
    <fieldset>
        <legend><?= $text_legend_settings ?></legend>
        <?php if ((int) $this->session->u->GroupId === 4) { ?>
        <div class="input_wrapper_other n50 border">
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
        <div class="input_wrapper_other n50 padding">
            <label><?= $text_authenticate_with_sms ?></label>
            <label class="radio">
                <input required type="radio" name="AuthSMS" id="AuthSMS" <?= @$userSettings['AuthSMS'] == 1 ? 'checked' : '' ?> value="1">
                <div class="radio_button"></div>
                <span><?= $text_disable_app_enable ?></span>
            </label>
            <label class="radio">
                <input required type="radio" name="AuthSMS" id="AuthSMS" <?= @$userSettings['AuthSMS'] == 2 ? 'checked' : '' ?> value="2">
                <div class="radio_button"></div>
                <span><?= $text_disable_app_disable ?></span>
            </label>
        </div>
        <div class="input_wrapper_other required">
            <label><?= $text_label_disable_app_message ?></label>
            <textarea name="DisableAppMessage" id="DisableAppMessage" cols="30" rows="10"><?= @$userSettings['DisableAppMessage'] ?></textarea>
        </div>
        <div class="input_wrapper n30 border">
            <label class="floated"><?= $text_correct_hijri_date ?></label>
            <input type="number" step="1" min="-2" max="2" name="CorrectHijriDate" id="CorrectHijriDate" value="<?= @$userSettings['CorrectHijriDate'] ?>">
        </div>
        <div class="input_wrapper_other n35 border padding">
            <label><?= $text_start_work_time ?></label>
            <input type="time" name="StartHour" id="StartHour" value="<?= @$userSettings['StartHour'] ?>">
        </div>
        <div class="input_wrapper_other n35 padding">
            <label><?= $text_end_work_time ?></label>
            <input type="time" name="EndHour" id="EndHour" value="<?= @$userSettings['EndHour'] ?>">
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