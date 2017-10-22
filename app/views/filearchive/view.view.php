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