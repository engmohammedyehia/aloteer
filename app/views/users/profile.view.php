<form autocomplete="off" class="appForm clearfix" method="post" enctype="multipart/form-data">
    <fieldset>
        <legend><?= $text_legend_profile ?></legend>
        <div class="input_wrapper n30 border required">
            <label<?= $this->labelFloat('FirstName', $profile) ?>><?= $text_label_FirstName ?> <span class="required">*</span></label>
            <input required type="text" name="FirstName" maxlength="10" value="<?= $this->showValue('FirstName', $profile) ?>">
        </div>
        <div class="input_wrapper n30 border padding required">
            <label<?= $this->labelFloat('LastName', $profile) ?>><?= $text_label_LastName ?> <span class="required">*</span></label>
            <input required type="text" name="LastName" maxlength="10" value="<?= $this->showValue('LastName', $profile) ?>">
        </div>
        <div class="input_wrapper n20 border padding">
            <label<?= $this->labelFloat('PhoneNumber', $user) ?>><?= $text_label_PhoneNumber ?></label>
            <input data-language="en" required type="text" name="PhoneNumber" value="<?= $this->showValue('PhoneNumber', $user) ?>">
        </div>
        <div class="input_wrapper_other n20 padding">
            <label><?= $text_label_DOB ?></label>
            <input type="date" name="DOB" value="<?= $this->showValue('DOB', $profile) ?>">
        </div>
        <div class="input_wrapper n40 border">
            <label<?= $this->labelFloat('Address', $profile) ?>><?= $text_label_Address ?></label>
            <input type="text" name="Address" value="<?= $this->showValue('Address', $profile) ?>">
        </div>
        <div class="input_wrapper_other n30 padding border">
            <label><?= $text_label_Image ?></label>
            <input type="file" accept="image/*" name="Image" id="Image" value="<?= $this->showValue('Image') ?>">
        </div>
        <div class="input_wrapper_other n30 padding">
            <label><?= $text_label_Signature ?></label>
            <input type="file" accept="image/*" name="Signature" id="Signature" value="<?= $this->showValue('Signature') ?>">
        </div>
        <?php if ($profile->Image !== null): ?>
            <div class="input_wrapper_other n100">
                <img src="/uploads/images/<?= $profile->Image ?>" width="100" height="100">
            </div>
        <?php endif; ?>
        <?php if ($profile->Signature !== null): ?>
            <div class="input_wrapper_other n100">
                <img src="/uploads/images/<?= $profile->Signature ?>">
            </div>
        <?php endif; ?>
        <input type="hidden" name="token" value="<?= $this->_registry->session->CSRFToken ?>">
        <input class="no_float" type="submit" name="submit" value="<?= $text_label_save ?>">
    </fieldset>
</form>