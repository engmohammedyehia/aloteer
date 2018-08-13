<?php
namespace PHPMVC\Controllers;

use PHPMVC\LIB\Messenger;
use PHPMVC\LIB\Helper;
use PHPMVC\LIB\InputFilter;
use PHPMVC\Models\AuditAssignmentResultModel;
use PHPMVC\Models\AuditModel;
use PHPMVC\Models\AuditRouteModel;
use PHPMVC\Models\BankAccountModel;
use PHPMVC\Models\BankBranchModel;
use PHPMVC\Models\BranchModel;
use PHPMVC\Models\ChequeModel;
use PHPMVC\Models\ClientModel;
use PHPMVC\Models\NotificationModel;
use PHPMVC\Models\ProjectModel;
use PHPMVC\Models\SignatureRequestModel;
use PHPMVC\Models\TransactionConditionModel;
use PHPMVC\Models\TransactionDeletedModel;
use PHPMVC\Models\TransactionFileModel;
use PHPMVC\Models\TransactionModel;
use PHPMVC\Models\TransactionStatusModel;
use PHPMVC\Models\TransactionTypeModel;
use PHPMVC\Models\UserModel;
use PHPMVC\Models\UserProfileModel;

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

        // Auditor
        if((int) $this->session->u->GroupId === 6) {
            $this->_data['transactions'] = TransactionModel::getAllForAuditors($this->session->u->BranchId);
        // Branch Manager
        } elseif ((int) $this->session->u->GroupId === 7) {
            $this->_data['transactions'] = TransactionModel::getAll($this->session->u->BranchId);
        // Finance Manager or CEO or Vice Presiendet
        } else {
            $this->_data['transactions'] = TransactionModel::getAll();
        }

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
        $this->_data['projects'] = ProjectModel::getAll();

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
            $transaction->ProjectId = $this->filterInt($_POST['ProjectId']);
            $transaction->Payment = $this->filterInt($_POST['Payment']);
            $transaction->Created = date('Y-m-d');

            if($transaction->save()) {
                $this->messenger->add($this->language->get('message_save_success'));
            } else {
                $this->messenger->add($this->language->get('message_save_failed'), Messenger::APP_MESSAGE_ERROR);
            }

            $autoAuditRoute = AuditRouteModel::getBy(['BranchId' => $transaction->BranchId]);

            $notificationUsers = UserModel::getUsersByType(7, $this->session->u->BranchId);
            $notificationUsers = $notificationUsers === false ? UserModel::getUsersByType(8, $this->session->u->BranchId) : $notificationUsers;
            NotificationModel::sendNotification($notificationUsers, 'text_notification_1', serialize([$transaction->TransactionTitle]), '/transactions/view/' . $transaction->TransactionId);

            if(false !== $autoAuditRoute) {
                $autoAuditRoute = $autoAuditRoute->current();
                if((int) $autoAuditRoute->Enabled === 1) {
                    $audit = new AuditModel();
                    $audit->UserId = $autoAuditRoute->UserId;
                    $audit->TransactionId = $transaction->TransactionId;
                    $audit->AssignedBy = $this->session->u->UserId;
                    $audit->Created = date('Y-m-d H:i:s');
                    $audit->save();

                    $status = new TransactionStatusModel();
                    $status->UserId = $notificationUsers[0]->UserId;
                    $status->TransactionId = $transaction->TransactionId;
                    $status->StatusType = TransactionStatusModel::STATUS_TRANSACTION_APPROVED_BY_MANAGER;
                    $status->Created = date('Y-m-d H:i:s');
                    $status->save();

                    $status = new TransactionStatusModel();
                    $status->UserId = $audit->UserId;
                    $status->TransactionId = $transaction->TransactionId;
                    $status->StatusType = TransactionStatusModel::STATUS_TRANSACTION_UNDER_REVIEW;
                    $status->Created = date('Y-m-d H:i:s');
                    $status->save();

                    NotificationModel::sendNotification([$audit], 'text_notification_3', serialize([$transaction->TransactionTitle]), '/transactions/audit/' . $transaction->TransactionId);
                }
            }

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
        $this->_data['projects'] = ProjectModel::getAll();

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
            $transaction->ProjectId = $this->filterInt($_POST['ProjectId']);
            $transaction->Payment = $this->filterInt($_POST['Payment']);

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
        $this->_data['projects'] = ProjectModel::getAll();

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
        $this->_data['types'] = TransactionTypeModel::getAll();
        $this->_data['clients'] = ClientModel::getAll();
        $this->_data['files'] = TransactionFileModel::getBy(['TransactionId' => $transaction->TransactionId]);

        $this->language->swapKey('title', [$transaction->TransactionTitle]);

        $this->_data['previousConditions'] = $previousConditions = AuditAssignmentResultModel::getPreviousConditions($this->session->u, $transaction);

        if(isset($_POST['submit']) &&
            $this->requestHasValidToken()
        ) {

            if($transactionConditions === false && !empty($_POST['conditions'])) {
                $transaction->Audited = 1;
                $status = new TransactionStatusModel();
                $status->StatusType = TransactionStatusModel::STATUS_TRANSACTION_REVIEWED;
                $status->UserId = $auditOrder->UserId;
                $status->Created = date('Y-m-d H:i:s');
                $status->TransactionId = $transaction->TransactionId;
                $status->save();
                $users = TransactionStatusModel::exportTransactionUsers($transaction, $this->session->u);
                NotificationModel::sendNotification($users, 'text_notification_4', serialize([$transaction->TransactionTitle, ($this->session->u->profile->FirstName . ' ' . $this->session->u->profile->LastName)]), '/transactions');
                $users = UserModel::getUsersByType(11);
                NotificationModel::sendNotification($users, 'text_notification_4_b', serialize([$transaction->TransactionTitle]), '/cheques/order/' . $transaction->TransactionId);
                $users = UserModel::getUsersByType(9);
                NotificationModel::sendNotification($users, 'text_notification_4_c', serialize([$transaction->TransactionTitle]), '/transactions');
                goto saveAudit;
            }

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
                $users = UserModel::getUsersByType(11);
                NotificationModel::sendNotification($users, 'text_notification_4_b', serialize([$transaction->TransactionTitle]), '/cheques/order/' . $transaction->TransactionId);
                $users = UserModel::getUsersByType(9);
                NotificationModel::sendNotification($users, 'text_notification_4_c', serialize([$transaction->TransactionTitle]), '/transactions');
            } else {
                $transaction->Audited = 0;
            }

            saveAudit:
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
        } elseif (isset($_POST['refuse'])) {

            $status = new TransactionStatusModel();
            $status->StatusType = TransactionStatusModel::STATUS_TRANSACTION_RETURNED;
            $status->UserId = $auditOrder->UserId;
            $status->Created = date('Y-m-d H:i:s');
            $status->TransactionId = $transaction->TransactionId;
            $status->Note = $this->filterString($_POST['reason']);
            $status->save();

            $theAuditor = UserProfileModel::getByPK($auditOrder->UserId);

            $users = TransactionStatusModel::exportTransactionUsers($transaction, $this->session->u);
            NotificationModel::sendNotification($users, 'text_notification_15', serialize([$transaction->TransactionTitle, $theAuditor->FirstName . ' ' . $theAuditor->LastName]), '/transactions');

            if($previousConditions !== false) {
                foreach ($previousConditions as $previousCondition) {
                    $previousCondition->delete();
               }
            }

            if($auditOrder->delete()) {
                $this->messenger->add($this->language->get('message_transaction_returned'));
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

        $users = TransactionStatusModel::exportTransactionUsers($transaction, $this->session->u);
        NotificationModel::sendNotification($users, 'text_notification_8', serialize([$transaction->TransactionTitle]), 'javascript:;');

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
                $users = TransactionStatusModel::exportTransactionUsers($transaction, $this->session->u);
                if((int) $status->StatusType === TransactionStatusModel::STATUS_TRANSACTION_CHEQUE_READY) {
                    NotificationModel::sendNotification($users, 'text_notification_9', serialize([$cheque->ChequeNumber, $transaction->TransactionTitle]), 'javascript:;');
                } else {
                    NotificationModel::sendNotification($users, 'text_notification_10', serialize([$cheque->ChequeNumber, $transaction->TransactionTitle]), 'javascript:;');
                }
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

        $users = TransactionStatusModel::exportTransactionUsers($transaction, $this->session->u);
        NotificationModel::sendNotification($users, 'text_notification_14', serialize([$transaction->TransactionTitle]), 'javascript:;');

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
        $branch = BranchModel::getByPK($transaction->BranchId)->BranchName;
        $this->_data['client'] = ClientModel::getByPK($transaction->ClientId)->name;
        $this->_data['cheque'] = ChequeModel::getBy(['TransactionId' => $transaction->TransactionId])->current();
        $this->_data['bankAccount'] = $bankAccount = BankAccountModel::getByPK($this->_data['cheque']->AccountId);
        $bankBranch = BankBranchModel::get('(SELECT BankBranchName From ' . BankBranchModel::getModelTableName() . ' WHERE BankBranchId = (SELECT BankBranchId FROM ' . BankAccountModel::getModelTableName() .  ' WHERE AccountId = ' . $this->_data['cheque']->AccountId . '))')->current();
        $this->_data['bank'] = $bankAccount->BankName . ' - ' . $bankBranch->BankBranchName;

        $this->language->swapKey('title', [$transaction->TransactionTitle]);
        $this->language->swapKey('text_reviewed_by', [$branch]);

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

    public function correctionAction()
    {
        $id = $this->filterInt($this->_params[0]);

        $transaction = TransactionModel::getByPK($id);

        if($transaction === false) {
            $this->redirect('/transactions');
        }

        $autoAuditRoute = AuditRouteModel::getBy(['BranchId' => $transaction->BranchId]);

        if(false !== $autoAuditRoute) {
            $autoAuditRoute = $autoAuditRoute->current();
            if((int) $autoAuditRoute->Enabled === 1) {

                $this->language->load('audit.messages');

                $audit = new AuditModel();
                $audit->UserId = $autoAuditRoute->UserId;
                $audit->TransactionId = $transaction->TransactionId;
                $audit->AssignedBy = $this->session->u->UserId;
                $audit->Created = date('Y-m-d H:i:s');
                $audit->save();

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
                $this->redirect('/audit/assign/'.$transaction->TransactionId);
            }
        } else {
            $this->redirect('/audit/assign/'.$transaction->TransactionId);
        }
    }
}