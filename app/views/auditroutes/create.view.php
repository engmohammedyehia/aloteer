<form autocomplete="off" class="appForm clearfix" method="post" enctype="application/x-www-form-urlencoded">
    <fieldset>
        <legend><?= $text_legend ?></legend>
        <div class="input_wrapper_other padding n45 select left_padding border">
            <select required name="BranchId">
                <option value=""><?= $text_label_BranchId ?></option>
                <?php if (false !== $branches): foreach ($branches as $branch): ?>
                    <option <?= $this->selectedIf('BranchId', $branch->BranchId) ?> value="<?= $branch->BranchId ?>"><?= $branch->BranchName ?></option>
                <?php endforeach;endif; ?>
            </select>
        </div>
        <div class="input_wrapper_other n45 select required right_padding">
            <span class="required">*</span>
            <select required name="UserId" id="UserId">
                <option value=""><?= $text_label_UserId ?></option>
                <?php if (false !== $auditors): foreach ($auditors as $auditor): ?>
                    <option <?= $this->selectedIf('UserId', $auditor->UserId) ?> value="<?= $auditor->UserId ?>"><?= $auditor->Name ?></option>
                <?php endforeach; endif; ?>
            </select>
        </div>
        <div class="input_wrapper_other n10 padding">
            <label><?= $text_label_Enabled ?></label>
            <label class="checkbox block">
                <input <?= $this->radioCheckedIf('Enabled', 1) ?> type="checkbox" name="Enabled" id="Enabled" value="1">
                <div class="checkbox_button"></div>
            </label>
        </div>
        <input type="hidden" name="token" value="<?= $this->_registry->session->CSRFToken ?>">
        <input class="no_float" type="submit" name="submit" value="<?= $text_label_save ?>">
    </fieldset>
</form>