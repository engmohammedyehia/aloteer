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

class TransactionsController extends AbstractController
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
        $this->language->load('transactions.default');
        $this->language->load('transactions.status');

        $this->_data['transactions'] = TransactionModel::getAll();

        $this->_view();
    }

    public function canceledAction()
    {
        $this->language->load('template.common');
        $this->language->load('transactions.default');
        $this->language->load('transactions.status');
        $this->language->load('transactions.canceled');

        $this->_data['transactions'] = TransactionDeletedModel::getAll();

        $this->_view();
    }

    public function createAction()
    {
        $this->language->load('template.common');
        $this->language->load('transactions.labels');
        $this->language->load('transactions.add');
        $this->language->load('transactions.messages');
        $this->language->load('validation.errors');

        $this->_data['types'] = TransactionTypeModel::getAll();
        $this->_data['clients'] = ClientModel::getAll();

        if(isset($_POST['submit']) &&
            $this->isValid($this->_createActionRoles, $_POST) &&
            $this->requestHasValidToken()
        ) {

            $transaction = new TransactionModel();
            $transaction->TransactionTitle = $this->filterString(@$_POST['TransactionTitle']);
            $transaction->TransactionTypeId = $this->filterInt(@$_POST['TransactionTypeId']);
            $transaction->ClientId = $this->filterInt(@$_POST['ClientId']);
            $transaction->TransactionSummary = $this->filterString(@$_POST['TransactionSummary']);
            $transaction->UserId = $this->session->u->UserId;
            $transaction->BranchId = $this->session->u->BranchId;
            $transaction->Created = date('Y-m-d');

            if($transaction->save()) {
                $this->messenger->add($this->language->get('message_save_success'));
            } else {
                $this->messenger->add($this->language->get('message_save_failed'), Messenger::APP_MESSAGE_ERROR);
            }

            $notificationUsers = UserModel::getUsersByType(7, $this->session->u->BranchId);
            NotificationModel::sendNotification($notificationUsers, 'text_notification_1', serialize([$transaction->TransactionTitle]), '/transactions/view/' . $transaction->TransactionId);

            $this->redirect('/transactions');
        }
        $this->_view();
    }

    public function editAction()
    {
        $id = $this->filterInt($this->_params[0]);

        $transaction = TransactionModel::getByPK($id);
        if($transaction === false) {
            $this->redirect('/transactions');
        }

        $this->language->load('template.common');
        $this->language->load('transactions.labels');
        $this->language->load('transactions.edit');
        $this->language->load('transactions.messages');
        $this->language->load('validation.errors');

        $this->_data['transaction'] = $transaction;
        $this->_data['types'] = TransactionTypeModel::getAll();
        $this->_data['clients'] = ClientModel::getAll();

        $this->language->swapKey('title', [$transaction->TransactionTitle]);

        if(isset($_POST['submit']) &&
            $this->isValid($this->_createActionRoles, $_POST) &&
            $this->requestHasValidToken()
        ) {

            $transaction->TransactionTitle = $this->filterString(@$_POST['TransactionTitle']);
            $transaction->TransactionTypeId = $this->filterInt(@$_POST['TransactionTypeId']);
            $transaction->ClientId = $this->filterInt(@$_POST['ClientId']);
            $transaction->TransactionSummary = $this->filterString(@$_POST['TransactionSummary']);
            $transaction->UpdatedBy = $this->session->u->UserId;

            if($transaction->save()) {
                $this->messenger->add($this->language->get('message_save_success'));
            } else {
                $this->messenger->add($this->language->get('message_save_failed'), Messenger::APP_MESSAGE_ERROR);
            }

            $this->redirect('/transactions');
        }
        $this->_view();
    }

    public function viewAction()
    {
        $id = $this->filterInt($this->_params[0]);

        $transaction = isset($_GET['d']) && $_GET['d'] === 'true' ? TransactionDeletedModel::getByPK($id) : TransactionModel::getByPK($id);
        if($transaction === false) {
            $this->redirect('/transactions');
        }

        $this->language->load('template.common');
        $this->language->load('transactions.labels');
        $this->language->load('transactions.view');

        $this->_data['transaction'] = $transaction;
        $this->_data['types'] = TransactionTypeModel::getAll();
        $this->_data['clients'] = ClientModel::getAll();

        $this->language->swapKey('title', [$transaction->TransactionTitle]);

        $this->_view();
    }

    public function deleteAction()
    {
        $id = $this->filterInt($this->_params[0]);

        $transaction = TransactionModel::getByPK($id);
        if($transaction === false) {
            $this->redirect('/transactions');
        }
        $this->language->load('transactions.messages');

        if($transaction->delete()) {
            $this->messenger->add($this->language->get('message_delete_success'));
        } else {
            $this->messenger->add($this->language->get('message_delete_failed'), Messenger::APP_MESSAGE_ERROR);
        }
        $this->redirect('/transactions');
    }

    public function timeLineAction()
    {
        $id = $this->filterInt($this->_params[0]);

        $transaction = TransactionModel::getByPK($id);

        if($transaction === false) {
            $this->redirect('/transactions');
        }

        $this->language->load('template.common');
        $this->language->load('transactions.labels');
        $this->language->load('transactions.timeline');
        $this->language->load('transactions.status');

        $this->language->swapKey('title', [$transaction->TransactionTitle]);

        $this->_data['statuses'] = TransactionStatusModel::getStatusesForTransaction($transaction);

        $this->_view();
    }

    public function auditAction()
    {
        $id = $this->filterInt($this->_params[0]);

        $transaction = TransactionModel::getByPK($id);
        if($transaction === false) {
            $this->redirect('/audit/review');
        }

        $auditOrder = AuditModel::getBy(['TransactionId' => $transaction->TransactionId, 'UserId' => $this->session->u->UserId]);
        if(false === $auditOrder) {
            $this->redirect('/audit/review');
        }
        $auditOrder = $auditOrder->current();

        $this->language->load('template.common');
        $this->language->load('transactions.labels');
        $this->language->load('transactions.audit');
        $this->language->load('transactions.messages');

        $this->_data['transaction'] = $transaction;
        $this->_data['conditions'] = $transactionConditions = TransactionConditionModel::getBy(['TransactionTypeId' => $transaction->TransactionTypeId]);

        $this->language->swapKey('title', [$transaction->TransactionTitle]);

        $this->_data['previousConditions'] = $previousConditions = AuditAssignmentResultModel::getPreviousConditions($this->session->u, $transaction);

        if(isset($_POST['submit']) &&
            $this->requestHasValidToken()
        ) {

            if(empty($_POST['conditions'])) {
                $this->redirect('/transactions/audit/' . $transaction->TransactionId);
            }

            if(!empty($previousConditions)) {
                $conditions = null !== $_POST['conditions'] ? $this->filterStringArray($_POST['conditions']) : [];
                $conditionsToAdd = array_values(array_diff($conditions, $previousConditions));
                $conditionsToDelete = array_values(array_diff($previousConditions, $conditions));

                if(!empty($conditionsToAdd)) {
                    foreach ($conditionsToAdd as $condition) {
                        $auditResult = new AuditAssignmentResultModel();
                        $auditResult->UserId = $this->session->u->UserId;
                        $auditResult->Created = date('Y-m-d');
                        $auditResult->TransactionId = $transaction->TransactionId;
                        $auditResult->AuditId = $auditOrder->AssignmentId;
                        $auditResult->ConditionId = $condition;
                        $auditResult->save();
                    }
                }

                if(!empty($conditionsToDelete)) {
                    foreach ($conditionsToDelete as $condition) {
                        $auditResult = AuditAssignmentResultModel::getBy(
                            [
                                'TransactionId' => $transaction->TransactionId,
                                'UserId'        => $this->session->u->UserId,
                                'ConditionId'   => $condition
                            ]
                        );
                        $auditResult->current()->delete();
                    }
                }
            } else {
                $conditions = $this->filterStringArray($_POST['conditions']);
                if(false !== $conditions) {
                    foreach ($conditions as $condition) {
                        $auditResult = new AuditAssignmentResultModel();
                        $auditResult->UserId = $this->session->u->UserId;
                        $auditResult->Created = date('Y-m-d');
                        $auditResult->TransactionId = $transaction->TransactionId;
                        $auditResult->AuditId = $auditOrder->AssignmentId;
                        $auditResult->ConditionId = $condition;
                        $auditResult->save();
                    }
                }
            }

            if(AuditAssignmentResultModel::transactionIsSatisfied($transaction) !== false) {
                $transaction->Audited = 1;
                $status = new TransactionStatusModel();
                $status->StatusType = TransactionStatusModel::STATUS_TRANSACTION_REVIEWED;
                $status->UserId = $auditOrder->UserId;
                $status->Created = date('Y-m-d H:i:s');
                $status->TransactionId = $transaction->TransactionId;
                $status->save();
                $users = TransactionStatusModel::exportTransactionUsers($transaction, $this->session->u);
                NotificationModel::sendNotification($users, 'text_notification_4', serialize([$transaction->TransactionTitle, ($this->session->u->profile->FirstName . ' ' . $this->session->u->profile->LastName)]), '/transactions');
            } else {
                $transaction->Audited = 0;
            }

            if($transaction->save()) {
                $this->messenger->add($this->language->get('message_audit_success'));
            } else {
                $this->messenger->add($this->language->get('message_audit_failed'), Messenger::APP_MESSAGE_ERROR);
            }

            if($transaction->Audited == 1) {
                $this->redirect('/audit/reviewed');
            } else {
                $this->redirect('/audit/review');
            }
        }

        $this->_view();
    }

    public function executiveConfirmAction()
    {
        $id = $this->filterInt($this->_params[0]);

        $transaction = TransactionModel::getByPK($id);

        if($transaction === false) {
            $this->redirect('/transactions');
        }

        $status = new TransactionStatusModel();
        $status->StatusType = TransactionStatusModel::STATUS_TRANSACTION_CEO_REVIEW;
        $status->UserId = $this->session->u->UserId;
        $status->Created = date('Y-m-d H:i:s');
        $status->TransactionId = $transaction->TransactionId;

        $this->language->load('transactions.messages');

        if($status->save()) {
            $this->messenger->add($this->language->get('message_admin_audit_success'));
        } else {
            $this->messenger->add($this->language->get('message_admin_audit_failed'), Messenger::APP_MESSAGE_ERROR);
        }

        $this->redirect('/transactions');
    }

    public function chequeReadyAction()
    {
        $id = $this->filterInt($this->_params[0]);

        $transaction = TransactionModel::getByPK($id);

        if($transaction === false) {
            $this->redirect('/transactions');
        }

        $this->language->load('template.common');
        $this->language->load('transactions.labels');
        $this->language->load('transactions.chequeready');
        $this->language->load('transactions.messages');

        $status = TransactionStatusModel::getTheLatestChequesStatues($transaction);

        if(isset($_POST['submit']) &&
            $this->requestHasValidToken()
        ) {
            if($status === false) {
                $status = new TransactionStatusModel();
                $status->StatusType = $this->filterInt($_POST['BankAccountState']);
                $status->UserId = $this->session->u->UserId;
                $status->Created = date('Y-m-d H:i:s');
                $status->TransactionId = $transaction->TransactionId;
            } else {
                $status->StatusType = $this->filterInt($_POST['BankAccountState']);
                $status->Created = date('Y-m-d H:i:s');
            }

            if($status->save()) {
                $cheque = ChequeModel::getOneBy(['TransactionId' => $transaction->TransactionId]);
                $cheque->Status = (int) $status->StatusType === TransactionStatusModel::STATUS_TRANSACTION_CHEQUE_READY ? ChequeModel::CHEQUE_ORDER_READY_BALANCE_COVERED : ChequeModel::CHEQUE_ORDER_READY_BALANCE_NOT_COVERED;
                $cheque->save();
                $this->messenger->add($this->language->get('message_cheque_success'));
            } else {
                $this->messenger->add($this->language->get('message_cheque_failed'), Messenger::APP_MESSAGE_ERROR);
            }
            $this->redirect('/transactions');
        }

        $this->_view();
    }

    public function closeAction()
    {
        $id = $this->filterInt($this->_params[0]);

        $transaction = TransactionModel::getByPK($id);

        if($transaction === false) {
            $this->redirect('/transactions');
        }

        $status = new TransactionStatusModel();
        $status->StatusType = TransactionStatusModel::STATUS_TRANSACTION_CLOSED;
        $status->UserId = $this->session->u->UserId;
        $status->Created = date('Y-m-d H:i:s');
        $status->TransactionId = $transaction->TransactionId;

        $this->language->load('transactions.messages');

        if($status->save()) {
            $this->messenger->add($this->language->get('message_close_success'));
        } else {
            $this->messenger->add($this->language->get('message_close_failed'), Messenger::APP_MESSAGE_ERROR);
        }

        $this->redirect('/transactions');
    }

    public function coverAction()
    {
        $id = $this->filterInt($this->_params[0]);

        $transaction = TransactionModel::getByPK($id);
        if($transaction === false) {
            $this->redirect('/transactions');
        }

        $signatureRequests = SignatureRequestModel::getSignaturesForTransaction($transaction);

        $this->language->load('template.common');
        $this->language->load('transactions.labels');
        $this->language->load('transactions.cover');

        $this->_data['signatureRequest'] = $signatureRequests;
        $this->_data['transaction'] = $transaction;

        $this->language->swapKey('title', [$transaction->TransactionTitle]);

        $this->_template->swapFooterResources(
            [
                'jquery'                => JS . 'vendor/jquery-1.12.0.min.js',
                'helper'                => JS . 'helper.js',
                'selectivity'           => JS . 'selectivity.js',
                'auth'                  => JS . 'auth.js',
                'main'                  => JS . 'main.js'
            ]
        );

        $this->_view();
    }
}