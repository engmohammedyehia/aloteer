<form autocomplete="off" class="appForm clearfix" method="post" enctype="application/x-www-form-urlencoded">
    <fieldset>
        <legend><?= $text_employee_details ?></legend>
        <div class="input_wrapper n40 border">
            <label<?= $this->labelFloat('TransactionTitle', $transaction) ?>><?= $text_label_TransactionTitle ?> <span class="required">*</span></label>
            <input required type="text" name="TransactionTitle" id="TransactionTitle" maxlength="80" value="<?= $this->showValue('TransactionTitle', $transaction) ?>">
        </div>
        <div class="input_wrapper_other n30 full_padding select border required">
            <span class="required">*</span>
            <select required name="TransactionTypeId" id="TransactionTypeId">
                <option value=""><?= $text_label_TransactionTypeId_select ?></option>
                <?php if (false !== $types): foreach ($types as $type): ?>
                    <option <?= $this->selectedIf('TransactionTypeId', $type->TransactionTypeId, $transaction) ?> value="<?= $type->TransactionTypeId ?>"><?= $type->TransactionType ?></option>
                <?php endforeach; endif; ?>
            </select>
        </div>
        <div class="input_wrapper_other n30 padding select required">
            <span class="required">*</span>
            <select required name="ClientId" id="ClientId">
                <option value=""><?= $text_label_TransactionTypeId_select ?></option>
                <?php if (false !== $clients): foreach ($clients as $client): ?>
                    <option <?= $this->selectedIf('ClientId', $client->id, $transaction) ?> value="<?= $client->id ?>"><?= $client->name ?></option>
                <?php endforeach; endif; ?>
            </select>
        </div>
        <div class="input_wrapper_other required">
            <label><?= $text_label_TransactionSummary ?> <span class="required">*</span></label>
            <textarea required name="TransactionSummary" id="TransactionSummary" cols="30" rows="10"><?= $transaction->TransactionSummary ?></textarea>
        </div>
        <input type="hidden" name="token" value="<?= $this->_registry->session->CSRFToken ?>">
        <input class="no_float" type="submit" name="submit" value="<?= $text_label_save ?>">
    </fieldset>
</form>