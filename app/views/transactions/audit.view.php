<form autocomplete="off" class="appForm clearfix" method="post" enctype="application/x-www-form-urlencoded">
    <fieldset>
        <legend><?= $text_details ?></legend>
        <div class="input_wrapper n40 border">
            <label<?= $this->labelFloat('TransactionTitle', $transaction) ?>><?= $text_label_TransactionTitle ?></label>
            <input disabled required type="text" name="TransactionTitle" id="TransactionTitle" maxlength="80" value="<?= $this->showValue('TransactionTitle', $transaction) ?>">
        </div>
        <div class="input_wrapper_other n30 full_padding select border required">
            <select disabled required name="TransactionTypeId" id="TransactionTypeId">
                <option value=""><?= $text_label_TransactionTypeId_select ?></option>
                <?php if (false !== $types): foreach ($types as $type): ?>
                    <option <?= $this->selectedIf('TransactionTypeId', $type->TransactionTypeId, $transaction) ?> value="<?= $type->TransactionTypeId ?>"><?= $type->TransactionType ?></option>
                <?php endforeach; endif; ?>
            </select>
        </div>
        <div class="input_wrapper_other n30 padding select required">
            <select disabled required name="ClientId" id="ClientId">
                <option value=""><?= $text_label_TransactionTypeId_select ?></option>
                <?php if (false !== $clients): foreach ($clients as $client): ?>
                    <option <?= $this->selectedIf('ClientId', $client->id, $transaction) ?> value="<?= $client->id ?>"><?= $client->name ?></option>
                <?php endforeach; endif; ?>
            </select>
        </div>
        <div class="input_wrapper_other required">
            <label><?= $text_label_TransactionSummary ?></label>
            <textarea disabled required name="TransactionSummary" id="TransactionSummary" cols="30" rows="20"><?= $transaction->TransactionSummary ?></textarea>
        </div>
        <div class="input_wrapper_other required">
            <label></label>
        </div>
    </fieldset>
    <br>
    <div class="container">
        <?php if(array_key_exists('/filearchive/create', $__privilegesKeys)): ?>
            <a href="/filearchive/create/<?= $transaction->TransactionId ?>" class="button"><i class="fa fa-plus"></i> <?= $text_new_item ?></a>
        <?php endif; ?>
        <?php if(array_key_exists('/filearchive/zipanddownload', $__privilegesKeys)): ?>
            <?php if (false !== $files): ?>
                <a href="/filearchive/zipanddownload/<?= $transaction->TransactionId ?>" class="button"><i class="fa fa-download"></i> <?= $text_download_all ?></a>
            <?php endif; ?>
        <?php endif; ?>
        <table class="data">
            <thead>
            <tr>
                <th><?= $text_table_title ?></th>
                <th><?= $text_table_control ?></th>
            </tr>
            </thead>
            <tbody>
            <?php if(false !== $files): foreach ($files as $file): ?>
                <tr>
                    <td><?= $file->FileTitle ?></td>
                    <td class="controls_td">
                        <a href="javascript:;" class="open_controls"><i class="fa fa-caret-square-o-left"></i></a>
                        <div class="controls_container">
                            <?php if(array_key_exists('/filearchive/view', $__privilegesKeys)): ?>
                                <a href="/filearchive/view/<?= $file->FileId ?>"><i class="fa fa-eye"></i> <?= $text_table_control_view ?></a>
                            <?php endif; ?>
                            <?php if(array_key_exists('/filearchive/edit', $__privilegesKeys)): ?>
                                <a href="/filearchive/edit/<?= $file->FileId ?>"><i class="fa fa-edit"></i> <?= $text_table_control_edit ?></a>
                            <?php endif; ?>
                            <?php if(array_key_exists('/filearchive/delete', $__privilegesKeys)): ?>
                                <a href="/filearchive/delete/<?= $file->FileId ?>" onclick="if(!confirm('<?= $text_table_control_delete_confirm ?>')) return false;"><i class="fa fa-trash"></i> <?= $text_table_control_delete ?></a>
                            <?php endif; ?>
                            <?php if(array_key_exists('/filearchive/download', $__privilegesKeys)): ?>
                                <a href="/filearchive/download/<?= $file->FileId ?>"><i class="fa fa-download"></i> <?= $text_table_control_download ?></a>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
    <br>
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
            <?php if (false === $conditions): ?>
                <label class="checkbox block">
                    <input type="checkbox" name="conditions[]" id="conditions" value="1">
                    <div class="checkbox_button"></div>
                    <span><?= $text_transaction_satisfied ?></span>
                </label>
            <?php endif; ?>
        </div>
        <div class="input_wrapper_other n100">
            <label><?= $text_reason ?></label>
            <textarea name="reason" id="reason" cols="30" rows="10"></textarea>
        </div>
        <input type="hidden" name="token" value="<?= $this->_registry->session->CSRFToken ?>">
        <input class="no_float" type="submit" name="refuse" value="<?= $text_label_refuse ?>" onclick="if(!confirm('<?= $text_refuse_confirm ?>')) return false;">
        <input class="no_float" type="submit" name="submit" value="<?= $text_label_save ?>">
    </fieldset>
</form>