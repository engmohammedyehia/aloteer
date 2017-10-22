<?php
namespace PHPMVC\Controllers;
use PHPMVC\LIB\Helper;
use PHPMVC\LIB\InputFilter;
use PHPMVC\lib\Messenger;
use PHPMVC\Models\TransactionConditionModel;
use PHPMVC\Models\TransactionTypeModel;

class TransactionConditionsController extends AbstractController
{
    use InputFilter;
    use Helper;

    private $_createActionRoles =
    [
        'ConditionTitle'        => 'req|alphanum|between(3,100)',
        'TransactionTypeId'     => 'req|num',
        'Required'              => 'req|num|inset[0,1]'
    ];

    public function defaultAction()
    {
        $this->language->load('template.common');
        $this->language->load('transactionconditions.default');

        $this->_data['conditions'] = TransactionConditionModel::getAll();

        $this->_view();
    }

    public function createAction()
    {
        $this->language->load('template.common');
        $this->language->load('transactionconditions.labels');
        $this->language->load('transactionconditions.create');
        $this->language->load('transactionconditions.messages');
        $this->language->load('validation.errors');

        $this->_data['types'] = TransactionTypeModel::getAll();

        if(isset($_POST['submit']) &&
            $this->isValid($this->_createActionRoles, $_POST) &&
            $this->requestHasValidToken()
        ) {
            $condition = new TransactionConditionModel();
            $condition->ConditionTitle = $this->filterString($_POST['ConditionTitle']);
            $condition->Required = $this->filterInt($_POST['Required']);
            $condition->TransactionTypeId = $this->filterInt($_POST['TransactionTypeId']);
            if($condition->save()) {
                $this->messenger->add($this->language->get('message_save_success'));
                $this->redirect('/transactionconditions');
            } else {
                $this->messenger->add($this->language->get('message_save_success'), Messenger::APP_MESSAGE_ERROR);
            }
        }

        $this->_view();
    }

    public function editAction()
    {

        $id = $this->filterInt($this->_params[0]);
        $condition = TransactionConditionModel::getByPK($id);

        if($condition === false) {
            $this->redirect('/transactionconditions');
        }

        $this->_data['condition'] = $condition;

        $this->language->load('template.common');
        $this->language->load('transactionconditions.labels');
        $this->language->load('transactionconditions.edit');
        $this->language->load('transactionconditions.messages');
        $this->language->load('validation.errors');

        $this->_data['types'] = TransactionTypeModel::getAll();

        if(isset($_POST['submit']) &&
            $this->isValid($this->_createActionRoles, $_POST) &&
            $this->requestHasValidToken()
        ) {
            $condition->ConditionTitle = $this->filterString($_POST['ConditionTitle']);
            $condition->Required = $this->filterInt($_POST['Required']);
            $condition->TransactionTypeId = $this->filterInt($_POST['TransactionTypeId']);
            if($condition->save()) {
                $this->messenger->add($this->language->get('message_save_success'));
                $this->redirect('/transactionconditions');
            } else {
                $this->messenger->add($this->language->get('message_save_success'), Messenger::APP_MESSAGE_ERROR);
            }
        }

        $this->_view();
    }

    public function deleteAction()
    {

        $id = $this->filterInt($this->_params[0]);
        $condition = TransactionConditionModel::getByPK($id);

        if($condition === false) {
            $this->redirect('/transactionconditions');
        }

        $this->language->load('transactionconditions.messages');

        if($condition->delete()) {
            $this->messenger->add($this->language->get('message_delete_success'));
        } else {
            $this->messenger->add($this->language->get('message_delete_success'), Messenger::APP_MESSAGE_ERROR);
        }

        $this->redirect('/transactionconditions');
    }

}