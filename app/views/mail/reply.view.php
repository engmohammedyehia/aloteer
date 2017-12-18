<form autocomplete="off" class="appForm clearfix" method="post" enctype="application/x-www-form-urlencoded">
    <fieldset>
        <legend><?= $text_legend ?></legend>
        <div class="input_wrapper n100 border">
            <label class="floated"><?= $text_title_label ?> <span class="required">*</span></label>
            <input required type="text" name="title" id="title" maxlength="80" value="<?= $mail->title ?>">
        </div>
        <div class="input_wrapper_other required">
            <label><?= $text_content_label ?> <span class="required">*</span></label>
            <textarea autofocus required name="content" id="content" cols="30" rows="10">&#10;&#10;----------------------------------------------------------&#10;<?= (isset($_POST['content'])) ? $_POST['content'] : $mail->content ?></textarea>
        </div>
        <input type="hidden" name="token" value="<?= $this->_registry->session->CSRFToken ?>">
        <input class="no_float" type="submit" name="submit" value="<?= $text_label_save ?>">
    </fieldset>
</form>