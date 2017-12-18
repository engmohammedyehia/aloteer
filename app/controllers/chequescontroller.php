<?php
namespace PHPMVC\Controllers;

use PHPMVC\LIB\Messenger;
use PHPMVC\LIB\Helper;
use PHPMVC\LIB\InputFilter;
use PHPMVC\Models\AuditAssignmentResultModel;
use PHPMVC\Models\AuditModel;
use PHPMVC\Models\BankAccountModel;
use PHPMVC\Models\BranchModel;
use PHPMVC\Models\ChequeModel;
use PHPMVC\Models\ClientModel;
use PHPMVC\Models\TransactionConditionModel;
use PHPMVC\Models\TransactionModel;
use PHPMVC\Models\TransactionStatusModel;
use PHPMVC\Models\TransactionTypeModel;
use PHPMVC\Models\UserModel;

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
            $cheque->AmountLiteral = $this->filterString($_POST['AmountLiteral']);
            $cheque->Status = ChequeModel::CHEQUE_ORDER_CREATED;
            $cheque->Created = date('Y-m-d H:i:s');
            $cheque->UserId = $this->filterInt($_POST['UserId']);
            $cheque->ClientName = ($_POST['ClientName'] === '') ? ClientModel::getByPK($cheque->ClientId)->name : $this->filterString($_POST['ClientName']);
            $cheque->Reason = $this->filterString($_POST['Reason']);
            $cheque->ChequeNumber = $this->filterString($_POST['ChequeNumber']);
            $cheque->BranchId = $this->filterInt($_POST['BranchId']);

            if($cheque->save()) {

                $status = new TransactionStatusModel();
                $status->UserId = $cheque->UserId;
                $status->TransactionId = $transaction->TransactionId;
                $status->StatusType = TransactionStatusModel::STATUS_TRANSACTION_CHEQUE_ORDERED;
                $status->Created = date('Y-m-d H:i:s');
                $status->save();

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
            $cheque->AmountLiteral = $this->filterString($_POST['AmountLiteral']);
            $cheque->Status = ChequeModel::CHEQUE_ORDER_CREATED;
            $cheque->Created = date('Y-m-d H:i:s');
            $cheque->UserId = $this->filterInt($_POST['UserId']);
            $cheque->ClientName = ($_POST['ClientName'] === '') ? ClientModel::getByPK($cheque->ClientId)->name : $this->filterString($_POST['ClientName']);
            $cheque->Reason = $this->filterString($_POST['Reason']);
            $cheque->ChequeNumber = $this->filterString($_POST['ChequeNumber']);
            $cheque->BranchId = $this->filterInt($_POST['BranchId']);

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
            $status->Created = date('Y-m-d H:i:s');
            $status->save();

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
                $status->Created = date('Y-m-d H:i:s');
                $status->save();

                $this->messenger->add($this->language->get('message_printed_success'));
                $this->redirect('/cheques/printed');
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
            $status->Created = date('Y-m-d H:i:s');
            $status->save();

            $this->messenger->add($this->language->get('message_handover_success'));

        } else {
            $this->messenger->add($this->language->get('message_handover_failed'), Messenger::APP_MESSAGE_ERROR);
        }

        $this->redirect('/cheques/handedover');
    }
}