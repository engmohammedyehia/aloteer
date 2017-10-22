<?php
namespace PHPMVC\Controllers;

use PHPMVC\lib\FileUpload;
use PHPMVC\LIB\Messenger;
use PHPMVC\LIB\Helper;
use PHPMVC\LIB\InputFilter;
use PHPMVC\Models\ClientModel;
use PHPMVC\Models\TransactionFileModel;
use PHPMVC\Models\TransactionModel;
use PHPMVC\Models\TransactionTypeModel;

class FileArchiveController extends AbstractController
{
    use InputFilter;
    use Helper;

    private $_createActionRoles =
    [
        'FileTitle'      => 'req|alphanum|between(5,100)'
    ];

    public function defaultAction()
    {
        $transactionId = (int) $this->filterInt($this->_params[0]);
        $transaction = TransactionModel::getByPK($transactionId);
        if(false === $transaction) {
            $this->redirect('/');
        }
        $this->_data['id'] = $transaction->TransactionId;

        $this->language->load('template.common');
        $this->language->load('filearchive.default');

        $this->language->swapKey('title', [$transaction->TransactionTitle]);

        $this->_data['files'] = TransactionFileModel::getBy(['TransactionId' => $transaction->TransactionId]);
        $this->_view();
    }

    public function createAction()
    {

        $transactionId = (int) $this->filterInt($this->_params[0]);
        $transaction = TransactionModel::getByPK($transactionId);
        if(false === $transaction) {
            $this->redirect('/');
        }

        $this->language->load('template.common');
        $this->language->load('filearchive.labels');
        $this->language->load('filearchive.create');
        $this->language->load('filearchive.messages');
        $this->language->load('validation.errors');

        $this->language->swapKey('title', [$transaction->TransactionTitle]);

        if(isset($_POST['submit']) &&
            $this->isValid($this->_createActionRoles, $_POST) &&
            $this->requestHasValidToken()
        ) {

            $file = new TransactionFileModel();
            $file->FileTitle = $this->filterString($_POST['FileTitle']);
            $file->TransactionId = $transaction->TransactionId;

            if(!empty($_FILES['FilePath']['name'])) {
                $uploader = new FileUpload($_FILES['FilePath']);
                try {
                    $uploader->upload();
                    $file->FilePath = $uploader->getFileName();
                } catch (\Exception $e) {
                    $this->messenger->add($e->getMessage(), Messenger::APP_MESSAGE_ERROR);
                }
            }

            if(!$uploader->hasError && $file->save()) {
                $this->messenger->add($this->language->get('message_save_success'));
            } else {
                $this->messenger->add($this->language->get('message_save_failed'), Messenger::APP_MESSAGE_ERROR);
            }

            $this->redirect('/filearchive/default/'.$transaction->TransactionId);
        }
        $this->_view();
    }

    public function editAction()
    {
        $id = $this->filterInt($this->_params[0]);

        $file = TransactionFileModel::getByPK($id);

        if($file === false) {
            $this->redirect('/transactions');
        }

        $this->language->load('template.common');
        $this->language->load('filearchive.labels');
        $this->language->load('filearchive.edit');
        $this->language->load('filearchive.messages');
        $this->language->load('validation.errors');

        $this->_data['file'] = $file;

        $this->language->swapKey('title', [$file->FileTitle]);

        if(isset($_POST['submit']) &&
            $this->isValid($this->_createActionRoles, $_POST) &&
            $this->requestHasValidToken()
        ) {

            $file->FileTitle = $this->filterString($_POST['FileTitle']);

            if(!empty($_FILES['FilePath']['name'])) {
                $uploader = new FileUpload($_FILES['FilePath']);
                try {
                    $uploader->remove($file->FilePath);
                    $uploader->upload();
                    $file->FilePath = $uploader->getFileName();
                } catch (\Exception $e) {
                    $this->messenger->add($e->getMessage(), Messenger::APP_MESSAGE_ERROR);
                }
            }

            if(!$uploader->hasError && $file->save()) {
                $this->messenger->add($this->language->get('message_save_success'));
            } else {
                $this->messenger->add($this->language->get('message_save_failed'), Messenger::APP_MESSAGE_ERROR);
            }

            $this->redirect('/filearchive/default/'.$file->TransactionId);
        }
        $this->_view();
    }

    public function viewAction()
    {
        $id = $this->filterInt($this->_params[0]);

        $file = TransactionFileModel::getByPK($id);

        if($file === false) {
            $this->redirect('/transactions');
        }

        $this->language->load('template.common');
        $this->language->load('filearchive.labels');
        $this->language->load('filearchive.view');
        $this->language->load('filearchive.messages');
        $this->language->load('validation.errors');

        $this->_data['file'] = $file;

        $this->language->swapKey('title', [$file->FileTitle]);

        $this->_view();
    }

    public function downloadAction()
    {
        $id = $this->filterInt($this->_params[0]);

        $file = TransactionFileModel::getByPK($id);

        if($file === false) {
            $this->redirect('/transactions');
        }

        $theFile = new \SplFileInfo($file->FilePath);
        if(in_array($theFile->getExtension(), ['png', 'jpg', 'jpeg', 'gif'])) {
            $documentRoot = IMAGES_UPLOAD_STORAGE;
        } else {
            $documentRoot = DOCUMENTS_UPLOAD_STORAGE;
        }

        header('Content-Type: ' . mime_content_type($documentRoot . DS . $file->FilePath));
        header('Content-disposition: attachment; filename='.(str_replace(' ',  '_', $file->FileTitle) . '.' . (new \SplFileInfo($documentRoot . DS . $file->FilePath))->getExtension()));
        header('Content-Length: ' . filesize($documentRoot . DS . $file->FilePath));
        readfile($documentRoot . DS . $file->FilePath);
    }

    public function zipAndDownloadAction()
    {
        $id = $this->filterInt($this->_params[0]);

        $transaction = TransactionModel::getByPK($id);

        if($transaction === false) {
            $this->redirect('/transactions');
        }

        $files = TransactionFileModel::getBy(['TransactionId' => $transaction->TransactionId, '_results_' => ['FilePath']], [], \PDO::FETCH_COLUMN);
        $transactionTitle = $transaction->TransactionTitle;

        $zip = new \ZipArchive();
        $zip_name = DOCUMENTS_UPLOAD_STORAGE . DS . "archive.zip";
        $zip_file = str_replace(' ',  '_', $transactionTitle) . '.zip';
        $zip->open($zip_name, \ZIPARCHIVE::CREATE);

        foreach($files as $file){
            $theFile = new \SplFileInfo($file);
            $extension = $theFile->getExtension();
            if(in_array($extension, ['png', 'jpg', 'jpeg', 'gif'])) {
                $documentRoot = IMAGES_UPLOAD_STORAGE;
            } else {
                $documentRoot = DOCUMENTS_UPLOAD_STORAGE;
            }
            $theFile = TransactionFileModel::getBy(['FilePath' => $file])->current()->FileTitle;
            $zip->addFile($documentRoot . DS . $file, $theFile . '.' . $extension);
        }

        $zip->close();

        if(file_exists($zip_name)){
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Type: application/force-download");
            header("Content-Type: application/octet-stream", false);
            header("Content-Type: application/download", false);
            header('Content-Disposition: attachment; filename="' . $zip_file . '"');
            header("Content-Transfer-Encoding: binary");
            header("Content-Length: ".filesize($zip_name));
            readfile($zip_name);
            unlink($zip_name);
        }
    }

    public function deleteAction()
    {
        $id = $this->filterInt($this->_params[0]);

        $file = TransactionFileModel::getByPK($id);

        if($file === false) {
            $this->redirect('/transactions');
        }

        $this->language->load('filearchive.messages');

        if($file->delete()) {
            $theFile = new \SplFileInfo($file->FilePath);
            if(in_array($theFile->getExtension(), ['png', 'jpg', 'jpeg', 'gif'])) {
                $documentRoot = IMAGES_UPLOAD_STORAGE;
            } else {
                $documentRoot = DOCUMENTS_UPLOAD_STORAGE;
            }
            if($file->FilePath !== '' && file_exists($documentRoot.DS.$file->FilePath) && is_writable($documentRoot)) {
                unlink($documentRoot.DS.$file->FilePath);
            }
            $this->messenger->add($this->language->get('message_delete_success'));
        } else {
            $this->messenger->add($this->language->get('message_delete_failed'), Messenger::APP_MESSAGE_ERROR);
        }
        $this->redirect('/filearchive/default/'.$file->TransactionId);
    }
}