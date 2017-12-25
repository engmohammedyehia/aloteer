<?php
namespace PHPMVC\Controllers;
use PHPMVC\LIB\Helper;
use PHPMVC\LIB\InputFilter;
use PHPMVC\lib\Messenger;
use PHPMVC\Models\AuditRouteModel;
use PHPMVC\Models\BranchModel;
use PHPMVC\Models\TransactionTypeModel;
use PHPMVC\Models\UserModel;

class AuditRoutesController extends AbstractController
{
    use InputFilter;
    use Helper;

    public function defaultAction()
    {
        $this->language->load('template.common');
        $this->language->load('auditroutes.default');

        $this->_data['routes'] = AuditRouteModel::getAll();

        $this->_view();
    }

    public function createAction()
    {
        $this->language->load('template.common');
        $this->language->load('auditroutes.labels');
        $this->language->load('auditroutes.create');
        $this->language->load('auditroutes.messages');
        $this->language->load('validation.errors');

        $this->_data['auditors'] = UserModel::getUsersByType(6);
        $this->_data['branches'] = BranchModel::getAll();

        if(isset($_POST['submit']) &&
            $this->requestHasValidToken()
        ) {
            $auditRoute = new AuditRouteModel();
            $auditRoute->BranchId = $this->filterString($_POST['BranchId']);
            $auditRoute->UserId = $this->filterString($_POST['UserId']);
            $auditRoute->Enabled = isset($_POST['Enabled']) ? 1 : 0;

            if($auditRoute->save()) {
                $this->messenger->add($this->language->get('message_save_success'));
                $this->redirect('/auditroutes');
            } else {
                $this->messenger->add($this->language->get('message_save_success'), Messenger::APP_MESSAGE_ERROR);
            }
        }

        $this->_view();
    }

    public function editAction()
    {

        $id = $this->filterInt($this->_params[0]);
        $auditRoute = AuditRouteModel::getByPK($id);

        if($auditRoute === false) {
            $this->redirect('/auditroutes');
        }

        $this->_data['route'] = $auditRoute;

        $this->_data['auditors'] = UserModel::getUsersByType(6);
        $this->_data['branches'] = BranchModel::getAll();

        $this->language->load('template.common');
        $this->language->load('auditroutes.labels');
        $this->language->load('auditroutes.edit');
        $this->language->load('auditroutes.messages');
        $this->language->load('validation.errors');

        if(isset($_POST['submit']) &&
            $this->requestHasValidToken()
        ) {
            $auditRoute->BranchId = $this->filterString($_POST['BranchId']);
            $auditRoute->UserId = $this->filterString($_POST['UserId']);
            $auditRoute->Enabled = isset($_POST['Enabled']) ? 1 : 0;

            if($auditRoute->save()) {
                $this->messenger->add($this->language->get('message_save_success'));
                $this->redirect('/auditroutes');
            } else {
                $this->messenger->add($this->language->get('message_save_success'), Messenger::APP_MESSAGE_ERROR);
            }
        }

        $this->_view();
    }

    public function deleteAction()
    {

        $id = $this->filterInt($this->_params[0]);
        $auditRoute = AuditRouteModel::getByPK($id);

        if($auditRoute === false) {
            $this->redirect('/auditroutes');
        }

        $this->language->load('auditroutes.messages');

        if($auditRoute->delete()) {
            $this->messenger->add($this->language->get('message_delete_success'));
        } else {
            $this->messenger->add($this->language->get('message_delete_success'), Messenger::APP_MESSAGE_ERROR);
        }

        $this->redirect('/auditroutes');
    }

}