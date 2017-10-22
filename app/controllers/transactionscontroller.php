<?php
namespace PHPMVC\Controllers;

use PHPMVC\LIB\Messenger;
use PHPMVC\LIB\Helper;
use PHPMVC\LIB\InputFilter;
use PHPMVC\Models\ClientModel;
use PHPMVC\Models\TransactionModel;
use PHPMVC\Models\TransactionStatusModel;
use PHPMVC\Models\TransactionTypeModel;

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

        $transaction = TransactionModel::getByPK($id);
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
}