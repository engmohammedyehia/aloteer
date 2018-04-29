<?php
namespace PHPMVC\Controllers;
use PHPMVC\LIB\Helper;
use PHPMVC\LIB\InputFilter;
use PHPMVC\lib\Messenger;
use PHPMVC\Models\ProjectModel;

class ProjectsController extends AbstractController
{
    use InputFilter;
    use Helper;

    private $_createActionRoles =
    [
        'ProjectName'   => 'req|alpha|between(3,50)'
    ];

    public function defaultAction()
    {
        $this->language->load('template.common');
        $this->language->load('projects.default');

        $this->_data['projects'] = ProjectModel::getAll();

        $this->_view();
    }

    public function createAction()
    {
        $this->language->load('template.common');
        $this->language->load('projects.labels');
        $this->language->load('projects.create');
        $this->language->load('projects.messages');
        $this->language->load('validation.errors');

        if(isset($_POST['submit']) &&
            $this->isValid($this->_createActionRoles, $_POST) &&
            $this->requestHasValidToken()
        ) {
            $type = new ProjectModel();
            $type->ProjectName = $this->filterString($_POST['ProjectName']);
            if($type->save()) {
                $this->messenger->add($this->language->get('message_save_success'));
                $this->redirect('/projects');
            } else {
                $this->messenger->add($this->language->get('message_save_success'), Messenger::APP_MESSAGE_ERROR);
            }
        }

        $this->_view();
    }

    public function editAction()
    {

        $id = $this->filterInt($this->_params[0]);
        $type = ProjectModel::getByPK($id);

        if($type === false) {
            $this->redirect('/projects');
        }

        $this->_data['branch'] = $type;

        $this->language->load('template.common');
        $this->language->load('projects.labels');
        $this->language->load('projects.edit');
        $this->language->load('projects.messages');
        $this->language->load('validation.errors');

        if(isset($_POST['submit']) &&
            $this->isValid($this->_createActionRoles, $_POST) &&
            $this->requestHasValidToken()
        ) {
            $type->ProjectName = $this->filterString($_POST['ProjectName']);
            if($type->save()) {
                $this->messenger->add($this->language->get('message_save_success'));
                $this->redirect('/projects');
            } else {
                $this->messenger->add($this->language->get('message_save_success'), Messenger::APP_MESSAGE_ERROR);
            }
        }

        $this->_view();
    }

    public function deleteAction()
    {

        $id = $this->filterInt($this->_params[0]);
        $type = ProjectModel::getByPK($id);

        if($type === false) {
            $this->redirect('/projects');
        }

        $this->language->load('projects.messages');

        if($type->delete()) {
            $this->messenger->add($this->language->get('message_delete_success'));
        } else {
            $this->messenger->add($this->language->get('message_delete_success'), Messenger::APP_MESSAGE_ERROR);
        }

        $this->redirect('/projects');
    }

}