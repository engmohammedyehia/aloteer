<?php
namespace PHPMVC\Controllers;

use PHPMVC\LIB\InputFilter;
use PHPMVC\LIB\Helper;
use PHPMVC\Models\NotificationModel;
use PHPMVC\Models\TransactionModel;
use PHPMVC\Models\TransactionStatusModel;
use PHPMVC\lib\Messenger;

class StatusesController extends AbstractController
{
    use InputFilter;
    use Helper;

    private $_createActionRoles = [
        'Notes' => 'alphanum'
    ];

    public function createAction()
    {
        $transactionId = (int) $this->filterInt(@$this->_params[0]);
        $statusType = (int) $this->filterInt(@$this->_params[1]);

        if ($transactionId === 0 || $statusType === 0) {
            $this->redirect('/transactions');
        }

        $transaction = TransactionModel::getByPK($transactionId);

        $reflection = new \ReflectionClass('PHPMVC\Models\TransactionStatusModel');
        $statuses = $reflection->getConstants();

        if($transaction === false || !in_array($statusType, $statuses)) {
            $this->redirect('/transactions');
        }

        $this->language->load('template.common');
        $this->language->load('statuses.labels');
        $this->language->load('statuses.create');
        $this->language->load('statuses.messages');
        $this->language->load('validation.errors');

        if(isset($_POST['submit']) &&
            $this->isValid($this->_createActionRoles, $_POST) &&
            $this->requestHasValidToken()
        ) {
            $status = new TransactionStatusModel();
            $status->UserId = $this->session->u->UserId;
            $status->TransactionId = $transaction->TransactionId;
            $status->StatusType = $statusType + 1;
            $status->Created = date('Y-m-d H:i:s');
            $status->Note = $this->filterString($_POST['Notes']);

            if($status->save()) {
                NotificationModel::sendNotification([$transaction], 'text_notification_2', serialize([$transaction->TransactionTitle]), '/transactions');
                $this->messenger->add($this->language->get('message_save_success'));
                $this->redirect('/transactions');
            } else {
                $this->messenger->add($this->language->get('message_save_failes'), Messenger::APP_MESSAGE_ERROR);
            }
        }

        $this->_view();
    }
}