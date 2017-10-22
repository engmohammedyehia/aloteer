<form autocomplete="off" class="appForm clearfix" method="post" enctype="application/x-www-form-urlencoded">
    <fieldset>
        <legend><?= $text_legend ?></legend>
        <div class="input_wrapper n40 border">
            <label<?= $this->labelFloat('ConditionTitle', $condition) ?>><?= $text_label_ConditionTitle ?></label>
            <input required type="text" name="ConditionTitle" id="ConditionTitle" maxlength="100" value="<?= $this->showValue('ConditionTitle', $condition) ?>">
        </div>
        <div class="input_wrapper_other n40 full_padding select required fa-border">
            <span class="required">*</span>
            <select data-selectivity="true" required name="TransactionTypeId" id="TransactionTypeId">
                <?php if ($types !== false): foreach ($types as $type): ?>
                    <option value="<?= $type->TransactionTypeId ?>" <?= $this->selectedIf('TransactionTypeId', $type->TransactionTypeId, $condition) ?>><?= $type->TransactionType ?></option>
                <?php endforeach; endif; ?>
            </select>
        </div>
        <div class="input_wrapper_other n20 padding">
            <label><?= $text_label_Required ?> <span class="required">*</span></label>
            <label class="radio">
                <input required type="radio" name="Required" id="Required" <?= $this->radioCheckedIf('Required', 1, $condition) ?> value="1">
                <div class="radio_button"></div>
                <span><?= $text_label_Required_1 ?></span>
            </label>
            <label class="radio">
                <input required type="radio" name="Required" id="Required" <?= $this->radioCheckedIf('Required', 0, $condition) ?> value="0">
                <div class="radio_button"></div>
                <span><?= $text_label_Required_0 ?></span>
            </label>
        </div>
        <input type="hidden" name="token" value="<?= $this->_registry->session->CSRFToken ?>">
        <input class="no_float" type="submit" name="submit" value="<?= $text_label_save ?>">
    </fieldset>
</form>