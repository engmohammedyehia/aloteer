<?php
namespace PHPMVC\Controllers;

use PHPMVC\LIB\Messenger;
use PHPMVC\LIB\Helper;
use PHPMVC\LIB\InputFilter;
use PHPMVC\Lib\Tafqeet\Tafqeet;
use PHPMVC\Models\AuditAssignmentResultModel;
use PHPMVC\Models\AuditModel;
use PHPMVC\Models\BankAccountModel;
use PHPMVC\Models\BankBranchModel;
use PHPMVC\Models\BranchModel;
use PHPMVC\Models\ChequeDeletedModel;
use PHPMVC\Models\ChequeModel;
use PHPMVC\Models\ClientModel;
use PHPMVC\Models\NotificationModel;
use PHPMVC\Models\TransactionConditionModel;
use PHPMVC\Models\TransactionModel;
use PHPMVC\Models\TransactionStatusModel;
use PHPMVC\Models\TransactionTypeModel;
use PHPMVC\Models\UserModel;
use PHPMVC\Models\UserProfileModel;

class ChequesController extends AbstractController
{
    use InputFilter;
    use Helper;

    private $_createActionRoles =
    [

    ];

    public function defaultAction()
    {
        $this->language->load('template.common');
        $this->language->load('cheques.default');

        $this->_data['orders'] = ChequeModel::getAll();

        $this->_view();
    }

    public function printingAction()
    {
        $this->language->load('template.common');
        $this->language->load('cheques.default');
        $this->language->load('cheques.printing');

        $this->_data['orders'] = ChequeModel::getPrintingCheques();

        $this->_view();
    }

    public function printedAction()
    {
        $this->language->load('template.common');
        $this->language->load('cheques.default');
        $this->language->load('cheques.printed');
        $this->language->load('transactions.status');

        $this->_data['orders'] = ChequeModel::getPrintedCheques();

        $this->_view();
    }

    public function clearedAction()
    {
        $this->language->load('template.common');
        $this->language->load('cheques.default');
        $this->language->load('cheques.cleared');
        $this->language->load('transactions.status');

        $this->_data['orders'] = ChequeModel::getClearedCheques();

        $this->_view();
    }

    public function canceledAction()
    {
        $this->language->load('template.common');
        $this->language->load('cheques.default');
        $this->language->load('cheques.canceled');
        $this->language->load('transactions.status');

        $this->_data['orders'] = ChequeDeletedModel::getCanceledCheques();

        $this->_view();
    }

    public function obsoletedAction()
    {
        $this->language->load('template.common');
        $this->language->load('cheques.default');
        $this->language->load('cheques.obsoleted');
        $this->language->load('transactions.status');

        $this->_data['orders'] = ChequeModel::getObsoletedCheques();

        $this->_view();
    }

    public function handedOverAction()
    {
        $this->language->load('template.common');
        $this->language->load('cheques.default');
        $this->language->load('cheques.handedover');
        $this->language->load('transactions.status');

        $this->_data['orders'] = ChequeModel::getHandedOverToClientCheques();

        $this->_view();
    }

    public function orderAction()
    {
        $transactionId = (int) $this->filterInt(@$this->_params[0]);
        $transaction = TransactionModel::getByPK($transactionId);

        if($transaction === false) {
            $this->redirect('/transactions');
        }

        $this->language->load('template.common');
        $this->language->load('cheques.labels');
        $this->language->load('cheques.order');
        $this->language->load('cheques.messages');
        $this->language->load('validation.errors');

        $this->language->swapKey('title', [$transaction->TransactionTitle]);

        $this->_data['users'] = UserModel::getUsersByType(11);
        $this->_data['bankAccounts'] = BankAccountModel::getAll();
        $this->_data['branches'] = BranchModel::getAll();

        if(isset($_POST['submit']) &&
            $this->isValid($this->_createActionRoles, $_POST) &&
            $this->requestHasValidToken()
        ) {

            $cheque = new ChequeModel();
            $cheque->TransactionId = $transaction->TransactionId;
            $cheque->ClientId = ClientModel::getByPK($transaction->ClientId)->id;
            $cheque->AccountId = $this->filterInt($_POST['AccountId']);
            $cheque->Amount = $this->filterInt($_POST['Amount']);

            $numToStr = new Tafqeet('Numbers');
            $numToStr->setFeminine(2);
            $numToStr->setFormat(1);
            $cheque->AmountLiteral = $numToStr->int2str($cheque->Amount);

            $cheque->Status = ChequeModel::CHEQUE_ORDER_CREATED;
            $cheque->Created = $this->startup->_hijri_;
            $cheque->CreatedJ = date('Y-m-d H:i:s');
            $cheque->UserId = $this->filterInt($_POST['UserId']);
            $cheque->ClientName = ($_POST['ClientName'] === '') ? ClientModel::getByPK($cheque->ClientId)->name : $this->filterString($_POST['ClientName']);
            $cheque->Reason = $this->filterString($_POST['Reason']);
            $cheque->ChequeNumber = $this->filterString($_POST['ChequeNumber']);
            $cheque->BranchId = $this->filterInt($_POST['BranchId']);
            $cheque->handedToTheFirstBeneficier = isset($_POST['handedToTheFirstBeneficier']) ? true : false;

            if($cheque->save()) {

                $status = new TransactionStatusModel();
                $status->UserId = $cheque->UserId;
                $status->TransactionId = $transaction->TransactionId;
                $status->StatusType = TransactionStatusModel::STATUS_TRANSACTION_CHEQUE_ORDERED;
                $status->Created = $this->startup->_hijri_;
                $status->save();

                $users = TransactionStatusModel::exportTransactionUsers($transaction, $this->session->u);
                $chequeUser = UserProfileModel::getByPK($cheque->UserId);
                NotificationModel::sendNotification($users, 'text_notification_5', serialize([$transaction->TransactionTitle, ($chequeUser->FirstName . ' ' . $chequeUser->LastName)]), 'javascript:;');

                $this->messenger->add($this->language->get('message_save_success'));
                $this->redirect('/transactions');

            } else {
                $this->messenger->add($this->language->get('message_save_failes'), Messenger::APP_MESSAGE_ERROR);
            }
        }

        $this->_view();
    }

    public function editAction()
    {
        $chequeId = (int) $this->filterInt(@$this->_params[0]);
        $cheque = ChequeModel::getByPK($chequeId);

        if($cheque === false) {
            $this->redirect('/cheques/default');
        }

        $this->language->load('template.common');
        $this->language->load('cheques.labels');
        $this->language->load('cheques.edit');
        $this->language->load('cheques.messages');
        $this->language->load('validation.errors');

        $transaction = TransactionModel::getByPK($cheque->TransactionId);
        $this->language->swapKey('title', [$transaction->TransactionTitle]);

        $this->_data['cheque'] = ChequeModel::getByPK($chequeId);
        $this->_data['users'] = UserModel::getUsersByType(11);
        $this->_data['bankAccounts'] = BankAccountModel::getAll();
        $this->_data['branches'] = BranchModel::getAll();

        if(isset($_POST['submit']) &&
            $this->isValid($this->_createActionRoles, $_POST) &&
            $this->requestHasValidToken()
        ) {
            $cheque->AccountId = $this->filterInt($_POST['AccountId']);
            $cheque->Amount = $this->filterInt($_POST['Amount']);

            $numToStr = new Tafqeet('Numbers');
            $numToStr->setFeminine(2);
            $numToStr->setFormat(1);
            $cheque->AmountLiteral = $numToStr->int2str($cheque->Amount);

            $cheque->Status = ChequeModel::CHEQUE_ORDER_CREATED;
            $cheque->Created = $this->startup->_hijri_;
            $cheque->CreatedJ = date('Y-m-d');
            $cheque->UserId = $this->filterInt($_POST['UserId']);
            $cheque->ClientName = ($_POST['ClientName'] === '') ? ClientModel::getByPK($cheque->ClientId)->name : $this->filterString($_POST['ClientName']);
            $cheque->Reason = $this->filterString($_POST['Reason']);
            $cheque->ChequeNumber = $this->filterString($_POST['ChequeNumber']);
            $cheque->BranchId = $this->filterInt($_POST['BranchId']);
            $cheque->handedToTheFirstBeneficier = isset($_POST['handedToTheFirstBeneficier']) ? true : false;

            if($cheque->save()) {
                $this->messenger->add($this->language->get('message_save_success'));
                $this->redirect('/cheques/default');
            } else {
                $this->messenger->add($this->language->get('message_save_failed'), Messenger::APP_MESSAGE_ERROR);
            }
        }

        $this->_view();
    }

    public function viewAction()
    {
        $chequeId = (int) $this->filterInt(@$this->_params[0]);
        $cheque = ChequeModel::getByPK($chequeId);

        if($cheque === false) {
            $this->redirect('/cheques/default');
        }

        $this->language->load('template.common');
        $this->language->load('cheques.labels');
        $this->language->load('cheques.view');
        $this->language->load('cheques.messages');
        $this->language->load('validation.errors');

        $transaction = TransactionModel::getByPK($cheque->TransactionId);
        $this->_data['TransactionTitle'] = $transaction->TransactionTitle;
        $this->_data['ClientName'] = ClientModel::getByPK($cheque->ClientId)->name;

        $this->language->swapKey('title', [$transaction->TransactionTitle]);

        $this->_data['cheque'] = ChequeModel::getByPK($chequeId);
        $this->_data['users'] = UserModel::getUsersByType(11);
        $this->_data['bankAccounts'] = BankAccountModel::getAll();
        $this->_data['branches'] = BranchModel::getAll();

        $this->_view();
    }

    public function deleteAction()
    {
        $chequeId = (int) $this->filterInt(@$this->_params[0]);
        $cheque = ChequeModel::getByPK($chequeId);

        if($cheque === false) {
            $this->redirect('/cheques/default');
        }

        $this->language->load('cheques.messages');

        if($cheque->delete()) {
            $status = TransactionStatusModel::getOneBy(
                [
                    'TransactionId'     => $cheque->TransactionId,
                    'StatusType'        => TransactionStatusModel::STATUS_TRANSACTION_CHEQUE_ORDERED
                ]
            );
            $status->delete();
            $this->messenger->add($this->language->get('message_delete_success'));
            $this->redirect('/cheques/default');
        } else {
            $this->messenger->add($this->language->get('message_delete_failed'), Messenger::APP_MESSAGE_ERROR);
        }

        $this->_view();
    }

    public function printAction()
    {
        $chequeId = (int) $this->filterInt(@$this->_params[0]);
        $cheque = ChequeModel::getByPK($chequeId);

        if($cheque === false) {
            $this->redirect('/cheques/default');
        }

        $cheque->Status = ChequeModel::CHEQUE_ORDER_PRINTING;

        $this->language->load('cheques.messages');

        if($cheque->save()) {

            $status = new TransactionStatusModel();
            $status->UserId = $cheque->UserId;
            $status->TransactionId = $cheque->TransactionId;
            $status->StatusType = TransactionStatusModel::STATUS_TRANSACTION_CHEQUE_PRINTING;
            $status->Created = $this->startup->_hijri_;
            $status->save();

            $transaction = TransactionModel::getByPK($cheque->TransactionId);
            $users = TransactionStatusModel::exportTransactionUsers($transaction, $this->session->u);
            $chequeUser = UserProfileModel::getByPK($cheque->UserId);
            NotificationModel::sendNotification($users, 'text_notification_6', serialize([$cheque->ChequeNumber, ($chequeUser->FirstName . ' ' . $chequeUser->LastName)]), 'javascript:;');

            $this->messenger->add($this->language->get('message_print_success'));
            $this->redirect('/cheques/printing');
        } else {
            $this->messenger->add($this->language->get('message_print_failed'), Messenger::APP_MESSAGE_ERROR);
        }
    }

    public function doneAction()
    {
        $chequeId = (int) $this->filterInt(@$this->_params[0]);
        $cheque = ChequeModel::getByPK($chequeId);

        if($cheque === false) {
            $this->redirect('/cheques/default');
        }

        $this->language->load('cheques.messages');

        $cheque->Status = ChequeModel::CHEQUE_ORDER_PRINTED;

        $this->language->load('template.common');
        $this->language->load('cheques.labels');
        $this->language->load('cheques.done');
        $this->language->load('cheques.messages');

        $transaction = TransactionModel::getByPK($cheque->TransactionId);

        $this->_data['TransactionTitle'] = $transaction->TransactionTitle;
        $this->_data['BranchName'] = BranchModel::getByPK($cheque->BranchId)->BranchName;

        $this->language->swapKey('title', [$transaction->TransactionTitle]);

        $this->_data['cheque'] = ChequeModel::getByPK($chequeId);
        $this->_data['users'] = UserModel::getUsersByType(11);
        $this->_data['bankAccounts'] = BankAccountModel::getAll();
        $this->_data['branches'] = BranchModel::getAll();

        if(isset($_POST['submit']) &&
            $this->requestHasValidToken()
        ) {
            if($cheque->save()) {

                $status = new TransactionStatusModel();
                $status->UserId = $cheque->UserId;
                $status->TransactionId = $cheque->TransactionId;
                $status->StatusType = TransactionStatusModel::STATUS_TRANSACTION_CHEQUE_PRINTED;
                $status->Created = $this->startup->_hijri_;
                $status->save();

                $users = TransactionStatusModel::exportTransactionUsers($transaction, $this->session->u);
                $chequeUser = UserProfileModel::getByPK($cheque->UserId);
                NotificationModel::sendNotification($users, 'text_notification_7', serialize([($chequeUser->FirstName . ' ' . $chequeUser->LastName), $cheque->ChequeNumber, $transaction->TransactionTitle]), 'javascript:;');

                $this->messenger->add($this->language->get('message_printed_success'));
                $this->redirect('/cheques/handoverinvoice/' . $cheque->ChequeId);
            } else {
                $this->messenger->add($this->language->get('message_printed_failed'), Messenger::APP_MESSAGE_ERROR);
            }
        }

        $this->_view();
    }

    public function handoverAction()
    {
        $chequeId = (int) $this->filterInt(@$this->_params[0]);
        $cheque = ChequeModel::getByPK($chequeId);

        if($cheque === false) {
            $this->redirect('/cheques/printed');
        }

        $this->language->load('cheques.messages');

        $cheque->Status = ChequeModel::CHEQUE_ORDER_HANDED_TO_CLIENT;
        $cheque->HandedOverDate = date('Y-m-d');

        if($cheque->save()) {

            $status = new TransactionStatusModel();
            $status->UserId = $this->session->u->UserId;
            $status->TransactionId = $cheque->TransactionId;
            $status->StatusType = TransactionStatusModel::STATUS_TRANSACTION_CHEQUE_HANDED_TO_CLIENT;
            $status->Created = $this->startup->_hijri_;
            $status->save();

            $transaction = TransactionModel::getByPK($cheque->TransactionId);
            $users = TransactionStatusModel::exportTransactionUsers($transaction, $this->session->u);
            NotificationModel::sendNotification($users, 'text_notification_11', serialize([$cheque->ChequeNumber, $cheque->ClientName, ($this->session->u->profile->FirstName . ' ' . $this->session->u->profile->LastName)]), 'javascript:;');

            $this->messenger->add($this->language->get('message_handover_success'));

        } else {
            $this->messenger->add($this->language->get('message_handover_failed'), Messenger::APP_MESSAGE_ERROR);
        }

        $this->redirect('/cheques/handedover');
    }

    public function clearAction()
    {
        $chequeId = (int) $this->filterInt(@$this->_params[0]);
        $cheque = ChequeModel::getByPK($chequeId);

        if($cheque === false) {
            $this->redirect('/cheques/default');
        }

        $cheque->Status = ChequeModel::CHEQUE_ORDER_CLEARED;

        $this->language->load('cheques.messages');

        if($cheque->save()) {

            $status = new TransactionStatusModel();
            $status->UserId = $this->session->u->UserId;
            $status->TransactionId = $cheque->TransactionId;
            $status->StatusType = TransactionStatusModel::STATUS_TRANSACTION_CHEQUE_CLEARED;
            $status->Created = $this->startup->_hijri_;
            $status->save();

            $transaction = TransactionModel::getByPK($cheque->TransactionId);
            $users = TransactionStatusModel::exportTransactionUsers($transaction, $this->session->u);
            NotificationModel::sendNotification($users, 'text_notification_12', serialize([$cheque->ChequeNumber]), 'javascript:;');

            $this->messenger->add($this->language->get('message_clear_success'));
            $this->redirect('/cheques/cleared');
        } else {
            $this->messenger->add($this->language->get('message_clear_failed'), Messenger::APP_MESSAGE_ERROR);
        }
    }

    public function cancelAction()
    {
        $chequeId = (int) $this->filterInt(@$this->_params[0]);
        $cheque = ChequeModel::getByPK($chequeId);

        if($cheque === false) {
            $this->redirect('/cheques/default');
        }

        $this->language->load('cheques.messages');

        if($cheque->delete()) {

            $deletedCheuqe = new ChequeDeletedModel();
            $deletedCheuqe->TransactionId = $cheque->TransactionId;
            $deletedCheuqe->ClientId = $cheque->ClientId;
            $deletedCheuqe->AccountId = $cheque->AccountId;
            $deletedCheuqe->Amount = $cheque->Amount;
            $deletedCheuqe->AmountLiteral = $cheque->AmountLiteral;
            $deletedCheuqe->Status = $cheque->Status;
            $deletedCheuqe->Created = $this->startup->_hijri_;
            $deletedCheuqe->UserId = $cheque->UserId;
            $deletedCheuqe->ClientName = $cheque->ClientName;
            $deletedCheuqe->Reason = $cheque->Reason;
            $deletedCheuqe->ChequeNumber = $cheque->ChequeNumber;
            $deletedCheuqe->BranchId = $cheque->BranchId;
            $deletedCheuqe->save();

            $this->messenger->add($this->language->get('message_cancel_success'));
            $this->redirect('/cheques/printed');
        } else {
            $this->messenger->add($this->language->get('message_cancel_failed'), Messenger::APP_MESSAGE_ERROR);
        }
    }

    public function handOverInvoiceAction()
    {
        $chequeId = (int) $this->filterInt(@$this->_params[0]);
        $cheque = ChequeModel::getByPK($chequeId);

        if($cheque === false) {
            $this->redirect('/cheques/default');
        }

        $this->language->load('cheques.messages');

        $cheque->Status = ChequeModel::CHEQUE_ORDER_PRINTED;

        $this->language->load('template.common');
        $this->language->load('cheques.labels');
        $this->language->load('cheques.handoverinvoice');

        $transaction = TransactionModel::getByPK($cheque->TransactionId);

        $this->_data['TransactionTitle'] = $transaction->TransactionTitle;
        $this->_data['BranchName'] = BranchModel::getByPK($cheque->BranchId)->BranchName;

        $this->language->swapKey('title', [$cheque->ChequeNumber]);

        $this->_data['cheque'] = ChequeModel::getByPK($chequeId);
        $this->_data['bankAccount'] = BankAccountModel::getByPK($cheque->AccountId);
        $this->_data['bankAccountBranch'] = BankBranchModel::getByPK($this->_data['bankAccount']->BankBranchId);
        $this->_data['branch'] = BranchModel::getByPK($cheque->BranchId);

        if(isset($_POST['submit'])) {

            $this->redirect('/cheques/printed');
        }

        $this->_view();
    }
}