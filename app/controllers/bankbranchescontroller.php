<?php
namespace PHPMVC\Controllers;
use PHPMVC\LIB\Helper;
use PHPMVC\LIB\InputFilter;
use PHPMVC\lib\Messenger;
use PHPMVC\Models\BankBranchModel;
use PHPMVC\Models\BranchModel;

class BankBranchesController extends AbstractController
{
    use InputFilter;
    use Helper;

    public function defaultAction()
    {
        $this->language->load('template.common');
        $this->language->load('bankbranches.default');

        $this->_data['branches'] = BankBranchModel::getAll();

        $this->_view();
    }

    public function createAction()
    {
        $this->language->load('template.common');
        $this->language->load('bankbranches.labels');
        $this->language->load('bankbranches.create');
        $this->language->load('bankbranches.messages');
        $this->language->load('validation.errors');

        $this->_data['branches'] = BranchModel::getAll();

        if(isset($_POST['submit']) &&
            $this->requestHasValidToken()
        ) {

            $bankBranch = new BankBranchModel();

            $bankBranch->BankBranchName = $this->filterString($_POST['BankBranchName']);
            $bankBranch->BranchId = $this->filterInt($_POST['BranchId']);

            if($bankBranch->save()) {
                $this->messenger->add($this->language->get('message_save_success'));
                $this->redirect('/bankbranches');
            } else {
                $this->messenger->add($this->language->get('message_save_success'), Messenger::APP_MESSAGE_ERROR);
            }
        }

        $this->_view();
    }

    public function editAction()
    {

        $id = $this->filterInt($this->_params[0]);
        $bankBranch = BankBranchModel::getByPK($id);

        if($bankBranch === false) {
            $this->redirect('/bankbranches');
        }

        $this->_data['bankBranch'] = $bankBranch;

        $this->language->load('template.common');
        $this->language->load('bankbranches.labels');
        $this->language->load('bankbranches.edit');
        $this->language->load('bankbranches.messages');
        $this->language->load('validation.errors');

        $this->_data['branches'] = BranchModel::getAll();

        if(isset($_POST['submit']) &&
            $this->requestHasValidToken()
        ) {

            $bankBranch->BankBranchName = $this->filterString($_POST['BankBranchName']);
            $bankBranch->BranchId = $this->filterInt($_POST['BranchId']);

            if($bankBranch->save()) {
                $this->messenger->add($this->language->get('message_save_success'));
                $this->redirect('/bankbranches');
            } else {
                $this->messenger->add($this->language->get('message_save_success'), Messenger::APP_MESSAGE_ERROR);
            }
        }

        $this->_view();
    }

    public function deleteAction()
    {

        $id = $this->filterInt($this->_params[0]);
        $bankBranch = BankBranchModel::getByPK($id);

        if($bankBranch === false) {
            $this->redirect('/bankbranches');
        }

        $this->language->load('bankbranches.messages');

        if($bankBranch->delete()) {
            $this->messenger->add($this->language->get('message_delete_success'));
        } else {
            $this->messenger->add($this->language->get('message_delete_success'), Messenger::APP_MESSAGE_ERROR);
        }

        $this->redirect('/bankbranches');
    }

}