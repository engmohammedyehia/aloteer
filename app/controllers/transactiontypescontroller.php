<?php
namespace PHPMVC\Controllers;
use PHPMVC\LIB\Helper;
use PHPMVC\LIB\InputFilter;
use PHPMVC\lib\Messenger;
use PHPMVC\Models\TransactionTypeModel;

class TransactionTypesController extends AbstractController
{
    use InputFilter;
    use Helper;

    private $_createActionRoles =
    [
        'TransactionType'   => 'req|alpha|between(3,50)'
    ];

    public function defaultAction()
    {
        $this->language->load('template.common');
        $this->language->load('transactiontypes.default');

        $this->_data['types'] = TransactionTypeModel::getAll();

        $this->_view();
    }

    public function createAction()
    {
        $this->language->load('template.common');
        $this->language->load('transactiontypes.labels');
        $this->language->load('transactiontypes.create');
        $this->language->load('transactiontypes.messages');
        $this->language->load('validation.errors');

        if(isset($_POST['submit']) &&
            $this->isValid($this->_createActionRoles, $_POST) &&
            $this->requestHasValidToken()
        ) {
            $type = new TransactionTypeModel();
            $type->TransactionType = $this->filterString($_POST['TransactionType']);
            if($type->save()) {
                $this->messenger->add($this->language->get('message_save_success'));
                $this->redirect('/transactiontypes');
            } else {
                $this->messenger->add($this->language->get('message_save_success'), Messenger::APP_MESSAGE_ERROR);
            }
        }

        $this->_view();
    }

    public function editAction()
    {

        $id = $this->filterInt($this->_params[0]);
        $type = TransactionTypeModel::getByPK($id);

        if($type === false) {
            $this->redirect('/transactiontypes');
        }

        $this->_data['type'] = $type;

        $this->language->load('template.common');
        $this->language->load('transactiontypes.labels');
        $this->language->load('transactiontypes.edit');
        $this->language->load('transactiontypes.messages');
        $this->language->load('validation.errors');

        if(isset($_POST['submit']) &&
            $this->isValid($this->_createActionRoles, $_POST) &&
            $this->requestHasValidToken()
        ) {
            $type->TransactionType = $this->filterString($_POST['TransactionType']);
            if($type->save()) {
                $this->messenger->add($this->language->get('message_save_success'));
                $this->redirect('/transactiontypes');
            } else {
                $this->messenger->add($this->language->get('message_save_success'), Messenger::APP_MESSAGE_ERROR);
            }
        }

        $this->_view();
    }

    public function deleteAction()
    {

        $id = $this->filterInt($this->_params[0]);
        $type = TransactionTypeModel::getByPK($id);

        if($type === false) {
            $this->redirect('/transactiontypes');
        }

        $this->language->load('transactiontypes.messages');

        if($type->delete()) {
            $this->messenger->add($this->language->get('message_delete_success'));
        } else {
            $this->messenger->add($this->language->get('message_delete_success'), Messenger::APP_MESSAGE_ERROR);
        }

        $this->redirect('/transactiontypes');
    }

}