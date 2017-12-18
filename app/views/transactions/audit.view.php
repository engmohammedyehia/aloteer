<form autocomplete="off" class="appForm clearfix" method="post" enctype="application/x-www-form-urlencoded">
    <fieldset>
        <legend><?= $text_employee_details ?></legend>
        <div class="input_wrapper_other n100 up_down_padding required">
            <label><?= $text_conditions_select ?> <span class="required">*</span></label>
            <?php if (false !== $conditions): foreach ($conditions as $condition): ?>
                <label class="checkbox block">
                    <input type="checkbox" <?= in_array($condition->ConditionId, $previousConditions) ? 'checked' : '' ?> name="conditions[]" id="conditions" value="<?= $condition->ConditionId ?>">
                    <div class="checkbox_button"></div>
                    <span><?= $condition->ConditionTitle ?></span>
                </label>
            <?php endforeach; endif; ?>
        </div>
        <input type="hidden" name="token" value="<?= $this->_registry->session->CSRFToken ?>">
        <input class="no_float" type="submit" name="submit" value="<?= $text_label_save ?>">
    </fieldset>
</form>