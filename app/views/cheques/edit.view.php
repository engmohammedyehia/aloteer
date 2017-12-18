<form autocomplete="off" class="appForm clearfix" method="post" enctype="application/x-www-form-urlencoded">
    <fieldset>
        <legend><?= $text_legend_details ?></legend>
        <div class="input_wrapper n15 border required">
            <label<?= $this->labelFloat('ChequeNumber', $cheque) ?>><?= $text_label_ChequeNumber ?> <span class="required">*</span></label>
            <input type="number" min="1" step="1" name="ChequeNumber" id="ChequeNumber" value="<?= $this->showValue('ChequeNumber', $cheque) ?>">
        </div>
        <div class="input_wrapper n20 border required padding">
            <label<?= $this->labelFloat('Amount', $cheque) ?>><?= $text_label_Amount ?> <span class="required">*</span></label>
            <input type="number" min="1" step="1" name="Amount" id="Amount" value="<?= $this->showValue('Amount', $cheque) ?>">
        </div>
        <div class="input_wrapper n65 required padding">
            <label<?= $this->labelFloat('AmountLiteral', $cheque) ?>><?= $text_label_AmountLiteral ?> <span class="required">*</span></label>
            <input type="text" name="AmountLiteral" id="AmountLiteral" value="<?= $this->showValue('AmountLiteral', $cheque) ?>">
        </div>
        <div class="input_wrapper n50 border required">
            <label<?= $this->labelFloat('ClientName', $cheque) ?>><?= $text_label_ClientName ?> <span class="required">*</span></label>
            <input type="text" name="ClientName" id="ClientName" value="<?= $this->showValue('ClientName', $cheque) ?>">
        </div>
        <div class="input_wrapper n50 required padding">
            <label<?= $this->labelFloat('Reason', $cheque) ?>><?= $text_label_Reason ?> <span class="required">*</span></label>
            <input type="text" name="Reason" id="Reason" value="<?= $this->showValue('Reason', $cheque) ?>">
        </div>
        <div class="input_wrapper_other n35 select required left_padding border">
            <span class="required">*</span>
            <select required name="AccountId" id="AccountId">
                <option value=""><?= $text_label_AccountId ?></option>
                <?php if (false !== $bankAccounts): foreach ($bankAccounts as $bankAccount): ?>
                    <option <?= $this->selectedIf('AccountId', $bankAccount->AccountId, $cheque) ?> value="<?= $bankAccount->AccountId ?>"><?= $bankAccount->BankName ?></option>
                <?php endforeach; endif; ?>
            </select>
        </div>
        <div class="input_wrapper_other n35 select required right_padding">
            <span class="required">*</span>
            <select required name="UserId" id="UserId">
                <option value=""><?= $text_label_UserId ?></option>
                <?php if (false !== $users): foreach ($users as $user): ?>
                    <option <?= $this->selectedIf('UserId', $user->UserId, $cheque) ?> value="<?= $user->UserId ?>"><?= $user->Name ?></option>
                <?php endforeach; endif; ?>
            </select>
        </div>
        <div class="input_wrapper_other n30 select required right_padding">
            <span class="required">*</span>
            <select required name="BranchId" id="BranchId">
                <option value=""><?= $text_label_BranchId ?></option>
                <?php if (false !== $branches): foreach ($branches as $branch): ?>
                    <option <?= $this->selectedIf('BranchId', $branch->BranchId, $cheque) ?> value="<?= $branch->BranchId ?>"><?= $branch->BranchName ?></option>
                <?php endforeach; endif; ?>
            </select>
        </div>
        <input type="hidden" name="token" value="<?= $this->_registry->session->CSRFToken ?>">
        <input class="no_float" type="submit" name="submit" value="<?= $text_label_save ?>">
    </fieldset>
</form>