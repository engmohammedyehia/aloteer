<form autocomplete="off" class="appForm clearfix" method="post" enctype="multipart/form-data">
    <fieldset>
        <legend><?= $text_legend ?></legend>
        <div class="input_wrapper n50 border">
            <label<?= $this->labelFloat('FileTitle', $file) ?>><?= $text_label_FileTitle ?></label>
            <input required type="text" name="FileTitle" id="FileTitle" maxlength="100" value="<?= $this->showValue('FileTitle', $file) ?>">
        </div>
        <div class="input_wrapper_other n50 padding">
            <label><?= $text_label_FilePath ?></label>
            <input required type="file" name="FilePath" id="FilePath">
        </div>
        <div class="input_wrapper_other n100">
            <?php
            $webURL = ((isset($_SERVER['HTTPS'])) ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . '/';
            $theFile = new \SplFileInfo($file->FilePath);
            ?>
            <?php if (in_array($theFile->getExtension(), ['png', 'jpg', 'jpeg', 'gif'])) { ?>
                <img src="/uploads/images/<?= $file->FilePath ?>" width="100%">
            <?php } elseif($theFile->getExtension() === 'pdf') { ?>
                <a class="button" href="/filearchive/download/<?= $file->FileId ?>"><i class="fa fa-download"></i> <?= $text_download_file ?></a>
            <?php } else { ?>
                <a target="_blank" class="button" href="https://view.officeapps.live.com/op/view.aspx?src=<?= $webURL ?>uploads/documents/<?= $file->FilePath ?>"><i class="fa fa-eye"></i> <?= $text_view_file ?></a>
                <a class="button" href="/filearchive/download/<?= $file->FileId ?>"><i class="fa fa-download"></i> <?= $text_download_file ?></a>
            <?php } ?>
        </div>
        <input type="hidden" name="token" value="<?= $this->_registry->session->CSRFToken ?>">
        <input class="no_float" type="submit" name="submit" value="<?= $text_label_save ?>">
    </fieldset>
</form>