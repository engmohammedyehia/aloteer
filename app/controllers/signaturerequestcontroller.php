<?php
namespace PHPMVC\Controllers;

use PHPMVC\LIB\Messenger;
use PHPMVC\LIB\Helper;
use PHPMVC\LIB\InputFilter;
use PHPMVC\Models\AuditAssignmentResultModel;
use PHPMVC\Models\AuditModel;
use PHPMVC\Models\ChequeModel;
use PHPMVC\Models\ClientModel;
use PHPMVC\Models\NotificationModel;
use PHPMVC\Models\SignatureRequestModel;
use PHPMVC\Models\TransactionConditionModel;
use PHPMVC\Models\TransactionDeletedModel;
use PHPMVC\Models\TransactionModel;
use PHPMVC\Models\TransactionStatusModel;
use PHPMVC\Models\TransactionTypeModel;
use PHPMVC\Models\UserModel;

class SignatureRequestController extends AbstractController
{
    use InputFilter;
    use Helper;

    private $_createActionRoles =
    [
        'TransactionTitle'      => 'req|alphanum|between(5,100)',
        'TransactionTypeId'     => 'req|num',
        'ClientId'              => 'req|num'
    ];

    public function defaultAction()
    {
        $this->language->load('template.common');
        $this->language->load('signaturerequests.default');

        $this->_data['signatures'] = SignatureRequestModel::getMySignatures($this->session->u);

        $this->_view();
    }

    public function createAction()
    {
        $id = $this->filterInt($this->_params['0']);

        $transaction = TransactionModel::getByPK($id);
        if($transaction === false) {
            $this->redirect('/transactions/default');
        }

        $users = TransactionStatusModel::exportTransactionUsersAll($transaction);

        $success = false;

        foreach ($users as $user) {
            $signatureRequest = new SignatureRequestModel();
            $signatureRequest->TransactionId = $transaction->TransactionId;
            $signatureRequest->UserId = $user->UserId;
            $signatureRequest->Approved = 0;
            $success = $signatureRequest->save();
        }

        $this->language->load('signaturerequests.messages');

        if($success) {
            $this->messenger->add($this->language->get('text_signature_request_success'));
        } else {
            $this->messenger->add($this->language->get('text_signature_request_failed'), Messenger::APP_MESSAGE_ERROR);
        }

        $this->redirect('/transactions/default');
    }

    public function approveAction()
    {
        $id = $this->filterInt($this->_params['0']);

        $signature = SignatureRequestModel::getByPK($id);

        if($signature === false || $signature->UserId !== $this->session->u->UserId) {
            $this->redirect('/signaturerequest/default');
        }

        $this->language->load('signaturerequests.messages');

        $signature->Approved = 1;
        $signature->ApproveDate = date('Y-m-d H:i:s');

        if($signature->save()) {
            $this->messenger->add($this->language->get('text_signature_request_approve_success'));
        } else {
            $this->messenger->add($this->language->get('text_signature_request_approve_failed'), Messenger::APP_MESSAGE_ERROR);
        }

        $this->redirect('/signaturerequest/default');
    }
}