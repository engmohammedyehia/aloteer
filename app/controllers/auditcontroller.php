<?php
namespace PHPMVC\Controllers;

use PHPMVC\LIB\InputFilter;
use PHPMVC\LIB\Helper;
use PHPMVC\Models\AuditModel;
use PHPMVC\Models\NotificationModel;
use PHPMVC\Models\TransactionModel;
use PHPMVC\Models\TransactionStatusModel;
use PHPMVC\lib\Messenger;
use PHPMVC\Models\UserModel;

class AuditController extends AbstractController
{
    use InputFilter;
    use Helper;

    private $_createActionRoles = [
        'UserId' => 'req|num'
    ];

    public function defaultAction()
    {
        $this->language->load('template.common');
        $this->language->load('audit.default');

        $this->_data['orders'] = AuditModel::getAll();

        $this->_view();
    }

    public function reviewAction()
    {
        $this->language->load('template.common');
        $this->language->load('audit.review');
        $this->language->load('transactions.status');

        $this->_data['transactions'] = TransactionModel::getAllForReview();

        $this->_view();
    }

    public function reviewedAction()
    {
        $this->language->load('template.common');
        $this->language->load('audit.reviewed');
        $this->language->load('transactions.status');

        $this->_data['transactions'] = TransactionModel::getAllForReviewed();

        $this->_view();
    }

    public function assignAction()
    {
        $transactionId = (int) $this->filterInt(@$this->_params[0]);
        $transaction = TransactionModel::getByPK($transactionId);

        if($transaction === false) {
            $this->redirect('/transactions');
        }

        // To be reviewed
        /*
        if($statusType > 1) {
            $previousStatus = TransactionStatusModel::getOneBy(['StatusType' => ($statusType - 1)]);
            if($statusType - (int) $previousStatus->StatusType > 1){
                $this->redirect('/transactions');
            }
        }
        */

        $this->language->load('template.common');
        $this->language->load('audit.labels');
        $this->language->load('audit.assign');
        $this->language->load('audit.messages');
        $this->language->load('validation.errors');

        $this->_data['auditors'] = UserModel::getUsersByType(6);

        if(isset($_POST['submit']) &&
            $this->isValid($this->_createActionRoles, $_POST) &&
            $this->requestHasValidToken()
        ) {

            $audit = new AuditModel();
            $audit->UserId = $this->filterInt($_POST['UserId']);
            $audit->TransactionId = $transaction->TransactionId;
            $audit->AssignedBy = $this->session->u->UserId;
            $audit->Created = date('Y-m-d H:i:s');

            if($audit->save()) {

                $status = new TransactionStatusModel();
                $status->UserId = $audit->UserId;
                $status->TransactionId = $transaction->TransactionId;
                $status->StatusType = TransactionStatusModel::STATUS_TRANSACTION_UNDER_REVIEW;
                $status->Created = date('Y-m-d H:i:s');
                $status->save();

                NotificationModel::sendNotification([$audit], 'text_notification_3', serialize([$transaction->TransactionTitle]), '/transactions/audit/' . $transaction->TransactionId);
                $this->messenger->add($this->language->get('message_save_success'));
                $this->redirect('/transactions');
            } else {
                $this->messenger->add($this->language->get('message_save_failes'), Messenger::APP_MESSAGE_ERROR);
            }
        }

        $this->_view();
    }
}