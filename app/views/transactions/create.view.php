<form autocomplete="off" class="appForm clearfix" method="post" enctype="application/x-www-form-urlencoded">
    <fieldset>
        <legend><?= $text_employee_details ?></legend>
        <div class="input_wrapper n50 border">
            <label<?= $this->labelFloat('TransactionTitle') ?>><?= $text_label_TransactionTitle ?> <span class="required">*</span></label>
            <input required type="text" name="TransactionTitle" id="TransactionTitle" maxlength="80" value="<?= $this->showValue('TransactionTitle') ?>">
        </div>
        <div class="input_wrapper_other n50 right_padding select required">
            <span class="required">*</span>
            <select required name="TransactionTypeId" id="TransactionTypeId">
                <option value=""><?= $text_label_TransactionTypeId_select ?></option>
                <?php if (false !== $types): foreach ($types as $type): ?>
                <option <?= $this->selectedIf('TransactionTypeId', $type->TransactionTypeId) ?> value="<?= $type->TransactionTypeId ?>"><?= $type->TransactionType ?></option>
                <?php endforeach; endif; ?>
            </select>
        </div>
        <div class="input_wrapper_other n50 left_padding select required border">
            <span class="required">*</span>
            <select required name="ClientId" id="ClientId">
                <option value=""><?= $text_label_ClientId_select ?></option>
                <?php if (false !== $clients): foreach ($clients as $client): ?>
                    <option <?= $this->selectedIf('ClientId', $client->id) ?> value="<?= $client->id ?>"><?= $client->name ?></option>
                <?php endforeach; endif; ?>
            </select>
        </div>
        <div class="input_wrapper_other n50 padding select required">
            <span class="required">*</span>
            <select required name="ProjectId" id="ProjectId">
                <option value=""><?= $text_label_ProjectId_select ?></option>
                <?php if (false !== $projects): foreach ($projects as $project): ?>
                    <option <?= $this->selectedIf('ProjectId', $project->ProjectId) ?> value="<?= $project->ProjectId ?>"><?= $project->ProjectName ?></option>
                <?php endforeach; endif; ?>
            </select>
        </div>
        <div class="input_wrapper_other required">
            <label><?= $text_label_TransactionSummary ?> <span class="required">*</span></label>
            <textarea required name="TransactionSummary" id="TransactionSummary" cols="30" rows="10"><?= @$_POST['TransactionSummary'] ?></textarea>
        </div>
        <input type="hidden" name="token" value="<?= $this->_registry->session->CSRFToken ?>">
        <input class="no_float" type="submit" name="submit" value="<?= $text_label_save ?>">
    </fieldset>
</form>