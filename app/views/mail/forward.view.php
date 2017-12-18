<form autocomplete="off" class="appForm clearfix" method="post" enctype="application/x-www-form-urlencoded">
    <fieldset>
        <legend><?= $text_legend ?></legend>
        <div class="input_wrapper n50 border">
            <label class="floated"><?= $text_title_label ?> <span class="required">*</span></label>
            <input required type="text" name="title" id="title" maxlength="80" value="<?= $mtitle ?>">
        </div>
        <div class="input_wrapper_other n50 right_padding select required">
            <span class="required">*</span>
            <select multiple data-selectivity="true" required name="receiverId[]" id="receiverId">
                <option value=""><?= $text_name_label ?></option>
                <?php if (false !== $allowedUsers): foreach ($allowedUsers as $allowedUser): ?>
                    <option <?= $this->selectedIf('receiverId', $allowedUser->UserId) ?> value="<?= $allowedUser->UserId ?>"><?= $allowedUser->FirstName  . ' ' . $allowedUser->LastName ?></option>
                <?php endforeach; endif; ?>
            </select>
        </div>
        <div class="input_wrapper_other required">
            <label><?= $text_content_label ?> <span class="required">*</span></label>
            <textarea required name="content" id="content" cols="30" rows="10"><?= $content ?></textarea>
        </div>
        <input type="hidden" name="token" value="<?= $this->_registry->session->CSRFToken ?>">
        <input class="no_float" type="submit" name="submit" value="<?= $text_label_save ?>">
    </fieldset>
</form>