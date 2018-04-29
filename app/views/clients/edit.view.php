<form autocomplete="off" class="appForm clearfix" method="post" enctype="multipart/form-data">
    <fieldset>
        <legend><?= $text_employee_details ?></legend>
        <div class="input_wrapper n50 border">
            <label<?= $this->labelFloat('name', $client) ?>><?= $text_label_name ?> <span class="required">*</span></label>
            <input required type="text" name="name" id="name" maxlength="80" value="<?= $this->showValue('name', $client) ?>">
        </div>
        <div class="input_wrapper_other n30 padding border">
            <label<?= $this->labelFloat('id_type', $client) ?>><?= $text_label_id_type ?> <span class="required">*</span></label>
            <label class="radio">
                <input required type="radio" name="id_type" id="id_type" <?= $this->radioCheckedIf('id_type', 1, $client) ?> value="1">
                <div class="radio_button"></div>
                <span><?= $text_label_id_type_1 ?></span>
            </label>
            <label class="radio">
                <input required type="radio" name="id_type" id="id_type" <?= $this->radioCheckedIf('id_type', 2, $client) ?> value="2">
                <div class="radio_button"></div>
                <span><?= $text_label_id_type_2 ?></span>
            </label>
            <label class="radio">
                <input required type="radio" name="id_type" id="id_type" <?= $this->radioCheckedIf('id_type', 3, $client) ?> value="3">
                <div class="radio_button"></div>
                <span><?= $text_label_id_type_3 ?></span>
            </label>
        </div>
        <div class="input_wrapper n20 padding">
            <label<?= $this->labelFloat('id_number', $client) ?>><?= $text_label_id_number ?> <span class="required">*</span></label>
            <input required data-language="en" type="text" name="id_number" id="id_number" maxlength="15" value="<?= $this->showValue('id_number', $client) ?>">
        </div>
        <div class="input_wrapper n25 padding border">
            <label<?= $this->labelFloat('mobile', $client) ?>><?= $text_label_mobile ?> <span class="required">*</span></label>
            <input required data-language="en" type="text" name="mobile" id="mobile" value="<?= $this->showValue('mobile', $client) ?>" maxlength="15">
        </div>
        <div class="input_wrapper n25 padding border">
            <label<?= $this->labelFloat('phone', $client) ?>><?= $text_label_phone ?></label>
            <input type="text" data-language="en" name="phone" id="phone" value="<?= $this->showValue('phone', $client) ?>" maxlength="15">
        </div>
        <div class="input_wrapper n25 padding border">
            <label<?= $this->labelFloat('fax', $client) ?>><?= $text_label_fax ?></label>
            <input type="text" data-language="en" name="fax" id="fax" value="<?= $this->showValue('fax', $client) ?>" maxlength="15">
        </div>
        <div class="input_wrapper n25 padding">
            <label<?= $this->labelFloat('email', $client) ?>><?= $text_label_email ?> <span class="required">*</span></label>
            <input type="text" name="email" id="email" value="<?= $this->showValue('email', $client) ?>" maxlength="50">
        </div>
        <div class="input_wrapper n15 padding border">
            <label<?= $this->labelFloat('pobox', $client) ?>><?= $text_label_pobox ?></label>
            <input type="text" data-language="en" name="pobox" id="pobox" value="<?= $this->showValue('pobox', $client) ?>" maxlength="50">
        </div>
        <div class="input_wrapper_other n25 full_padding select border required">
            <span class="required">*</span>
            <select data-selectivity="true" required name="city" id="city">
                <option value=""><?= $text_label_city ?></option>
                <option value="1" <?= $this->selectedIf('city', 1, $client) ?>><?= $text_city_1 ?></option>
                <option value="2" <?= $this->selectedIf('city', 2, $client) ?>><?= $text_city_2 ?></option>
                <option value="3" <?= $this->selectedIf('city', 3, $client) ?>><?= $text_city_3 ?></option>
                <option value="4" <?= $this->selectedIf('city', 4, $client) ?>><?= $text_city_4 ?></option>
                <option value="5" <?= $this->selectedIf('city', 5, $client) ?>><?= $text_city_5 ?></option>
                <option value="6" <?= $this->selectedIf('city', 6, $client) ?>><?= $text_city_6 ?></option>
                <option value="7" <?= $this->selectedIf('city', 7, $client) ?>><?= $text_city_7 ?></option>
                <option value="8" <?= $this->selectedIf('city', 8, $client) ?>><?= $text_city_8 ?></option>
                <option value="9" <?= $this->selectedIf('city', 9, $client) ?>><?= $text_city_9 ?></option>
                <option value="10" <?= $this->selectedIf('city', 10, $client) ?>><?= $text_city_10 ?></option>
                <option value="11" <?= $this->selectedIf('city', 11, $client) ?>><?= $text_city_11 ?></option>
                <option value="12" <?= $this->selectedIf('city', 12, $client) ?>><?= $text_city_12 ?></option>
                <option value="13" <?= $this->selectedIf('city', 13, $client) ?>><?= $text_city_13 ?></option>
                <option value="14" <?= $this->selectedIf('city', 14, $client) ?>><?= $text_city_14 ?></option>
                <option value="15" <?= $this->selectedIf('city', 15, $client) ?>><?= $text_city_15 ?></option>
                <option value="16" <?= $this->selectedIf('city', 16, $client) ?>><?= $text_city_16 ?></option>
                <option value="17" <?= $this->selectedIf('city', 17, $client) ?>><?= $text_city_17 ?></option>
                <option value="18" <?= $this->selectedIf('city', 18, $client) ?>><?= $text_city_18 ?></option>
                <option value="19" <?= $this->selectedIf('city', 19, $client) ?>><?= $text_city_19 ?></option>
                <option value="20" <?= $this->selectedIf('city', 20, $client) ?>><?= $text_city_20 ?></option>
                <option value="21" <?= $this->selectedIf('city', 21, $client) ?>><?= $text_city_21 ?></option>
                <option value="22" <?= $this->selectedIf('city', 22, $client) ?>><?= $text_city_22 ?></option>
            </select>
        </div>
        <div class="input_wrapper n15 padding border">
            <label<?= $this->labelFloat('zip_code', $client) ?>><?= $text_label_zip_code ?></label>
            <input type="text" data-language="en" name="zip_code" id="zip_code" value="<?= $this->showValue('zip_code', $client) ?>" maxlength="8">
        </div>
        <div class="input_wrapper_other n45 padding">
            <label><?= $text_label_file ?></label>
            <input type="file" name="file" id="file" value="<?= $this->showValue('file') ?>">
        </div>
        <div class="input_wrapper n50 border">
            <label<?= $this->labelFloat('BankName', $client) ?>><?= $text_label_BankName ?></label>
            <input type="text" name="BankName" id="BankName" maxlength="80" value="<?= $this->showValue('BankName', $client) ?>">
        </div>
        <div class="input_wrapper n50 padding">
            <label<?= $this->labelFloat('BankIBAN', $client) ?>><?= $text_label_BankIBAN ?></label>
            <input type="text" name="BankIBAN" id="BankIBAN" maxlength="80" value="<?= $this->showValue('BankIBAN', $client) ?>">
        </div>
        <?php if ($client->CommercialRegistration !== null): ?>
            <div class="input_wrapper_other n100">
                <img src="/uploads/images/<?= $client->CommercialRegistration ?>" width="100%">
            </div>
        <?php endif; ?>
        <input type="hidden" name="token" value="<?= $this->_registry->session->CSRFToken ?>">
        <input class="no_float" type="submit" name="submit" value="<?= $text_label_save ?>">
    </fieldset>
</form>