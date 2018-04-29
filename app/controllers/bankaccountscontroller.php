<?php
namespace PHPMVC\Controllers;
use PHPMVC\LIB\Helper;
use PHPMVC\LIB\InputFilter;
use PHPMVC\lib\Messenger;
use PHPMVC\Models\BankAccountModel;
use PHPMVC\Models\BankBranchModel;
use PHPMVC\Models\BranchModel;

class BankAccountsController extends AbstractController
{
    use InputFilter;
    use Helper;

    private $_createActionRoles =
    [
        'BankName'              => 'req|alpha|between(5,30)',
        'BankAccountIBAN'       => 'req|alphanum|max(30)|lang(en)'
    ];

    public function defaultAction()
    {
        $this->language->load('template.common');
        $this->language->load('bankaccounts.default');
        $this->language->load('bankaccounts.banklist');

        $this->_data['accounts'] = BankAccountModel::getAll();

        $this->_view();
    }

    public function createAction()
    {
        $this->language->load('template.common');
        $this->language->load('bankaccounts.labels');
        $this->language->load('bankaccounts.banklist');
        $this->language->load('bankaccounts.create');
        $this->language->load('bankaccounts.messages');
        $this->language->load('validation.errors');

        $this->_data['branches'] = BankBranchModel::getAll();

        if(isset($_POST['submit']) &&
            $this->isValid($this->_createActionRoles, $_POST) &&
            $this->requestHasValidToken()
        ) {

            $account = new BankAccountModel();

            $account->BankName = $this->filterString($_POST['BankName']);
            $account->BankAccountOwner = $this->filterString($_POST['BankAccountOwner']);
            $account->BankAccountUsage = $this->filterString($_POST['BankAccountUsage']);
            $account->BankAccountIBAN = $this->filterString($_POST['BankAccountIBAN']);
            $account->BankAccountNumber = $this->filterString($_POST['BankAccountNumber']);
            $account->BankBranchId = $this->filterInt($_POST['BankBranchId']);

            if($account->save()) {
                $this->messenger->add($this->language->get('message_save_success'));
                $this->redirect('/bankaccounts');
            } else {
                $this->messenger->add($this->language->get('message_save_success'), Messenger::APP_MESSAGE_ERROR);
            }
        }

        $this->_view();
    }

    public function editAction()
    {

        $id = $this->filterInt($this->_params[0]);
        $account = BankAccountModel::getByPK($id);

        if($account === false) {
            $this->redirect('/bankaccounts');
        }

        $this->_data['account'] = $account;

        $this->language->load('template.common');
        $this->language->load('bankaccounts.labels');
        $this->language->load('bankaccounts.banklist');
        $this->language->load('bankaccounts.edit');
        $this->language->load('bankaccounts.messages');
        $this->language->load('validation.errors');

        $this->_data['branches'] = BankBranchModel::getAll();

        if(isset($_POST['submit']) &&
            $this->isValid($this->_createActionRoles, $_POST) &&
            $this->requestHasValidToken()
        ) {

            $account->BankName = $this->filterString($_POST['BankName']);
            $account->BankAccountOwner = $this->filterString($_POST['BankAccountOwner']);
            $account->BankAccountUsage = $this->filterString($_POST['BankAccountUsage']);
            $account->BankAccountIBAN = $this->filterString($_POST['BankAccountIBAN']);
            $account->BankAccountNumber = $this->filterString($_POST['BankAccountNumber']);
            $account->BankBranchId = $this->filterInt($_POST['BankBranchId']);

            if($account->save()) {
                $this->messenger->add($this->language->get('message_save_success'));
                $this->redirect('/bankaccounts');
            } else {
                $this->messenger->add($this->language->get('message_save_success'), Messenger::APP_MESSAGE_ERROR);
            }
        }

        $this->_view();
    }

    public function deleteAction()
    {

        $id = $this->filterInt($this->_params[0]);
        $account = BankAccountModel::getByPK($id);

        if($account === false) {
            $this->redirect('/bankaccounts');
        }

        $this->language->load('bankaccounts.messages');

        if($account->delete()) {
            $this->messenger->add($this->language->get('message_delete_success'));
        } else {
            $this->messenger->add($this->language->get('message_delete_success'), Messenger::APP_MESSAGE_ERROR);
        }

        $this->redirect('/bankaccounts');
    }

}