<?php
namespace PHPMVC\Controllers;
use PHPMVC\LIB\Helper;
use PHPMVC\LIB\InputFilter;
use PHPMVC\lib\Messenger;
use PHPMVC\Models\BranchModel;

class BranchesController extends AbstractController
{
    use InputFilter;
    use Helper;

    private $_createActionRoles =
    [
        'BranchName'   => 'req|alpha|between(3,50)'
    ];

    public function defaultAction()
    {
        $this->language->load('template.common');
        $this->language->load('branches.default');

        $this->_data['branches'] = BranchModel::getAll();

        $this->_view();
    }

    public function createAction()
    {
        $this->language->load('template.common');
        $this->language->load('branches.labels');
        $this->language->load('branches.create');
        $this->language->load('branches.messages');
        $this->language->load('validation.errors');

        if(isset($_POST['submit']) &&
            $this->isValid($this->_createActionRoles, $_POST) &&
            $this->requestHasValidToken()
        ) {
            $type = new BranchModel();
            $type->BranchName = $this->filterString($_POST['BranchName']);
            $type->Color = $this->filterString($_POST['Color']);
            if($type->save()) {
                $this->messenger->add($this->language->get('message_save_success'));
                $this->redirect('/branches');
            } else {
                $this->messenger->add($this->language->get('message_save_success'), Messenger::APP_MESSAGE_ERROR);
            }
        }

        $this->_view();
    }

    public function editAction()
    {

        $id = $this->filterInt($this->_params[0]);
        $type = BranchModel::getByPK($id);

        if($type === false) {
            $this->redirect('/branches');
        }

        $this->_data['branch'] = $type;

        $this->language->load('template.common');
        $this->language->load('branches.labels');
        $this->language->load('branches.edit');
        $this->language->load('branches.messages');
        $this->language->load('validation.errors');

        if(isset($_POST['submit']) &&
            $this->isValid($this->_createActionRoles, $_POST) &&
            $this->requestHasValidToken()
        ) {
            $type->BranchName = $this->filterString($_POST['BranchName']);
            $type->Color = $this->filterString($_POST['Color']);
            if($type->save()) {
                $this->messenger->add($this->language->get('message_save_success'));
                $this->redirect('/branches');
            } else {
                $this->messenger->add($this->language->get('message_save_success'), Messenger::APP_MESSAGE_ERROR);
            }
        }

        $this->_view();
    }

    public function deleteAction()
    {

        $id = $this->filterInt($this->_params[0]);
        $type = BranchModel::getByPK($id);

        if($type === false) {
            $this->redirect('/branches');
        }

        $this->language->load('branches.messages');

        if($type->delete()) {
            $this->messenger->add($this->language->get('message_delete_success'));
        } else {
            $this->messenger->add($this->language->get('message_delete_success'), Messenger::APP_MESSAGE_ERROR);
        }

        $this->redirect('/branches');
    }

}