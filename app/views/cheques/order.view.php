<form autocomplete="off" class="appForm clearfix" method="post" enctype="application/x-www-form-urlencoded">
    <fieldset>
        <legend><?= $text_legend_details ?></legend>
        <div class="input_wrapper n15 border required">
            <label<?= $this->labelFloat('ChequeNumber') ?>><?= $text_label_ChequeNumber ?> <span class="required">*</span></label>
            <input type="number" min="1" step="1" name="ChequeNumber" id="ChequeNumber" value="<?= $this->showValue('ChequeNumber') ?>">
        </div>
        <div class="input_wrapper n20 border required padding">
            <label<?= $this->labelFloat('Amount') ?>><?= $text_label_Amount ?> <span class="required">*</span></label>
            <input type="number" min="1" step="1" name="Amount" id="Amount" value="<?= $this->showValue('Amount') ?>">
        </div>
        <div class="input_wrapper n65 required padding">
            <label<?= $this->labelFloat('ClientName') ?>><?= $text_label_ClientName ?> <span class="required">*</span></label>
            <input type="text" name="ClientName" id="ClientName" value="<?= $this->showValue('ClientName') ?>">
        </div>
        <div class="input_wrapper n100 required">
            <label<?= $this->labelFloat('Reason') ?>><?= $text_label_Reason ?> <span class="required">*</span></label>
            <input type="text" name="Reason" id="Reason" value="<?= $this->showValue('Reason') ?>">
        </div>
        <div class="input_wrapper_other n35 select required left_padding border">
            <span class="required">*</span>
            <select required name="AccountId" id="AccountId">
                <option value=""><?= $text_label_AccountId ?></option>
                <?php if (false !== $bankAccounts): foreach ($bankAccounts as $bankAccount): ?>
                    <option <?= $this->selectedIf('AccountId', $bankAccount->AccountId) ?> value="<?= $bankAccount->AccountId ?>"><?= $bankAccount->BankName . ' (' . $bankAccount->BranchName . ')' ?></option>
                <?php endforeach; endif; ?>
            </select>
        </div>
        <div class="input_wrapper_other n35 select required right_padding">
            <span class="required">*</span>
            <select required name="UserId" id="UserId">
                <option value=""><?= $text_label_UserId ?></option>
                <?php if (false !== $users): foreach ($users as $user): ?>
                <option <?= $this->selectedIf('UserId', $user->UserId) ?> value="<?= $user->UserId ?>"><?= $user->Name ?></option>
                <?php endforeach; endif; ?>
            </select>
        </div>
        <div class="input_wrapper_other n30 select required right_padding">
            <span class="required">*</span>
            <select required name="BranchId" id="BranchId">
                <option value=""><?= $text_label_BranchId ?></option>
                <?php if (false !== $branches): foreach ($branches as $branch): ?>
                    <option <?= $this->selectedIf('BranchId', $branch->BranchId) ?> value="<?= $branch->BranchId ?>"><?= $branch->BranchName ?></option>
                <?php endforeach; endif; ?>
            </select>
        </div>
        <div class="input_wrapper_other n100">
            <label><?= $text_label_handedToTheFirstBeneficier ?></label>
            <label class="checkbox block">
                <input checked type="checkbox" name="handedToTheFirstBeneficier" id="handedToTheFirstBeneficier" value="1">
                <div class="checkbox_button"></div>
            </label>
        </div>
        <input type="hidden" name="token" value="<?= $this->_registry->session->CSRFToken ?>">
        <input class="no_float" type="submit" name="submit" value="<?= $text_label_save ?>">
    </fieldset>
</form>