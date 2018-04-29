<form autocomplete="off" class="appForm clearfix" method="post" enctype="application/x-www-form-urlencoded">
    <fieldset>
        <div class="input_wrapper n15 border required">
            <label<?= $this->labelFloat('ChequeNumber', $cheque) ?>><?= $text_label_ChequeNumber ?> <span class="required">*</span></label>
            <input disabled type="number" min="1" step="1" name="ChequeNumber" id="ChequeNumber" value="<?= $this->showValue('ChequeNumber', $cheque) ?>">
        </div>
        <div class="input_wrapper n20 border required padding">
            <label<?= $this->labelFloat('Amount', $cheque) ?>><?= $text_label_Amount ?> <span class="required">*</span></label>
            <input disabled type="number" min="1" step="1" name="Amount" id="Amount" value="<?= $this->showValue('Amount', $cheque) ?>">
        </div>
        <div class="input_wrapper n65 required padding">
            <label<?= $this->labelFloat('AmountLiteral', $cheque) ?>><?= $text_label_AmountLiteral ?> <span class="required">*</span></label>
            <input disabled type="text" name="AmountLiteral" id="AmountLiteral" value="<?= $this->showValue('AmountLiteral', $cheque) ?>">
        </div>
        <div class="input_wrapper n50 border required">
            <label<?= $this->labelFloat('ClientName', $cheque) ?>><?= $text_label_ClientName ?> <span class="required">*</span></label>
            <input disabled type="text" name="ClientName" id="ClientName" value="<?= $this->showValue('ClientName', $cheque) ?>">
        </div>
        <div class="input_wrapper n50 required padding">
            <label<?= $this->labelFloat('Reason', $cheque) ?>><?= $text_label_Reason ?> <span class="required">*</span></label>
            <input disabled type="text" name="Reason" id="Reason" value="<?= $this->showValue('Reason', $cheque) ?>">
        </div>
        <div class="input_wrapper n100 required">
            <label class="floated"><?= $text_label_On_Bank ?> <span class="required">*</span></label>
            <input disabled type="text" name="OnBank" id="OnBank" value="<?= $bankAccount->BankName ?> <?= $bankAccountBranch->BankBranchName ?> حساب رقم <?= $bankAccount->BankAccountNumber ?>">
        </div>
        <button onclick="window.print();"><?= $text_label_print ?></button>
        <input class="no_float" type="submit" name="submit" value="<?= $text_label_back ?>">
    </fieldset>
</form>
<div class="invoice_print">
    <div class="cheque_branch"><?= $branch->BranchName ?></div>
    <div class="invoice_number">رقم سند الصرف <?= 1111 + (int) $cheque->ChequeId ?></div>
    <div class="cheque_date"><?= $cheque->Created ?></div>
    <div class="cheque_date"><?= $cheque->Created ?></div>
    <div class="cheque_number"><?= $cheque->ChequeNumber ?></div>
    <div class="cheque_client"><?= $cheque->ClientName ?></div>
    <div class="cheque_amount">#<?= $cheque->Amount ?>#</div>
    <div class="cheque_amount_literal"><?= $cheque->AmountLiteral ?> ريال فقط لا غير</div>
    <div class="cheque_on_bank"><?= $bankAccount->BankName ?> <?= $bankAccountBranch->BankBranchName ?> حساب رقم <?= $bankAccount->BankAccountNumber ?></div>
    <div class="cheque_reason"><?= $cheque->Reason ?></div>
</div>