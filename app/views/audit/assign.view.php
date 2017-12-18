<form autocomplete="off" class="appForm clearfix" method="post" enctype="application/x-www-form-urlencoded">
    <fieldset>
        <legend><?= $text_legend_details ?></legend>
        <div class="input_wrapper_other n100 select required">
            <span class="required">*</span>
            <select required name="UserId" id="UserId">
                <option value=""><?= $text_label_UserId_select ?></option>
                <?php if (false !== $auditors): foreach ($auditors as $auditor): ?>
                <option <?= $this->selectedIf('TransactionTypeId', $auditor->UserId) ?> value="<?= $auditor->UserId ?>"><?= $auditor->Name ?></option>
                <?php endforeach; endif; ?>
            </select>
        </div>
        <input type="hidden" name="token" value="<?= $this->_registry->session->CSRFToken ?>">
        <input class="no_float" type="submit" name="submit" value="<?= $text_label_save ?>">
    </fieldset>
</form>