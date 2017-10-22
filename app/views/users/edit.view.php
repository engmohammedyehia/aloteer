<form autocomplete="off" class="appForm clearfix" method="post" enctype="application/x-www-form-urlencoded">
    <fieldset>
        <legend><?= $text_legend ?></legend>
        <div class="input_wrapper n40 border">
            <label<?= $this->labelFloat('FirstName', $profile) ?>><?= $text_label_FirstName ?></label>
            <input required type="text" name="FirstName" maxlength="10" value="<?= $this->showValue('FirstName', $profile) ?>">
        </div>
        <div class="input_wrapper n40 border padding">
            <label<?= $this->labelFloat('LastName', $profile) ?>><?= $text_label_LastName ?></label>
            <input required type="text" name="LastName" maxlength="10" value="<?= $this->showValue('LastName', $profile) ?>">
        </div>
        <div class="input_wrapper n20 padding">
            <label<?= $this->labelFloat('PhoneNumber', $user) ?>><?= $text_label_PhoneNumber ?></label>
            <input data-language="en" required type="text" name="PhoneNumber" value="<?= $this->showValue('PhoneNumber', $user) ?>">
        </div>
        <div class="input_wrapper_other left_padding n50 select border">
            <select required name="GroupId">
                <option value=""><?= $text_user_GroupId ?></option>
                <?php if (false !== $groups): foreach ($groups as $group): ?>
                    <option <?= $this->selectedIf('GroupId', $group->GroupId, $user) ?> value="<?= $group->GroupId ?>"><?= $group->GroupName ?></option>
                <?php endforeach;endif; ?>
            </select>
        </div>
        <div class="input_wrapper_other padding n50 select">
            <select required name="BranchId">
                <option value=""><?= $text_user_BranchId ?></option>
                <?php if (false !== $branches): foreach ($branches as $branch): ?>
                    <option <?= $this->selectedIf('BranchId', $branch->BranchId, $user) ?> value="<?= $branch->BranchId ?>"><?= $branch->BranchName ?></option>
                <?php endforeach;endif; ?>
            </select>
        </div>
        <input class="no_float" type="submit" name="submit" value="<?= $text_label_save ?>">
    </fieldset>
</form>