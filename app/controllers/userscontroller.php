<?php
namespace PHPMVC\Controllers;

use PHPMVC\LIB\Helper;
use PHPMVC\LIB\InputFilter;
use PHPMVC\lib\Messenger;
use PHPMVC\Models\BranchModel;
use PHPMVC\Models\UserGroupModel;
use PHPMVC\Models\UserModel;
use PHPMVC\Models\UserProfileModel;

class UsersController extends AbstractController
{

    use InputFilter;
    use Helper;

    private $_createActionRoles =
    [
        'FirstName'     => 'req|alpha|between(3,10)',
        'LastName'      => 'req|alpha|between(3,10)',
        'Username'      => 'req|alphanum|between(3,12)',
        'Password'      => 'req|min(6)|eq_field(CPassword)',
        'CPassword'     => 'req|min(6)',
        'Email'         => 'req|email|eq_field(CEmail)',
        'CEmail'        => 'req|email',
        'PhoneNumber'   => 'alphanum|max(15)',
        'GroupId'       => 'req|int'
    ];

    private $_editActionRoles =
    [
        'PhoneNumber'   => 'alphanum|max(15)',
        'GroupId'       => 'req|int'
    ];

    public function defaultAction()
    {
        $this->language->load('template.common');
        $this->language->load('users.default');

        $this->_data['users'] = UserModel::getUsers($this->session->u);

        $this->_view();
    }

    public function createAction()
    {

        $this->language->load('template.common');
        $this->language->load('users.create');
        $this->language->load('users.labels');
        $this->language->load('users.messages');
        $this->language->load('validation.errors');

        $this->_data['groups'] = UserGroupModel::getAll();
        $this->_data['branches'] = BranchModel::getAll();

        if(isset($_POST['submit']) && $this->isValid($this->_createActionRoles, $_POST)) {

            $user = new UserModel();
            $user->Username = $this->filterString($_POST['Username']);
            $user->cryptPassword($_POST['Password']);
            $user->Email = $this->filterString($_POST['Email']);
            $user->PhoneNumber = $this->filterString($_POST['PhoneNumber']);
            $user->GroupId = $this->filterInt($_POST['GroupId']);
            $user->BranchId = $this->filterInt($_POST['BranchId']);
            $user->SubscriptionDate = date('Y-m-d');
            $user->LastLogin = date('Y-m-d H:i:s');
            $user->Status = 1;

            if(UserModel::userExists($user->Username)) {
                $this->messenger->add($this->language->get('message_user_exists'), Messenger::APP_MESSAGE_ERROR);
                $this->redirect('/users');
            }

            if($user->save()) {
                $userProfile = new UserProfileModel();
                $userProfile->UserId = $user->UserId;
                $userProfile->FirstName = $this->filterString($_POST['FirstName']);
                $userProfile->LastName = $this->filterString($_POST['LastName']);
                $userProfile->save(false);
                $this->messenger->add($this->language->get('message_create_success'));
            } else {
                $this->messenger->add($this->language->get('message_create_failed'), Messenger::APP_MESSAGE_ERROR);
            }
            $this->redirect('/users');
        }

        $this->_view();
    }

    public function editAction()
    {

        $id = $this->filterInt($this->_params[0]);
        $user = UserModel::getByPK($id);

        if($user === false || $this->session->u->UserId == $id) {
            $this->redirect('/users');
        }

        $profile = UserProfileModel::getBy(['UserId' => $user->UserId])->current();

        $this->_data['user'] = $user;
        $this->_data['profile'] = $profile;

        $this->language->load('template.common');
        $this->language->load('users.edit');
        $this->language->load('users.labels');
        $this->language->load('users.messages');
        $this->language->load('validation.errors');

        $this->_data['groups'] = UserGroupModel::getAll();
        $this->_data['branches'] = BranchModel::getAll();

        if(isset($_POST['submit']) && $this->isValid($this->_editActionRoles, $_POST)) {

            $user->PhoneNumber = $this->filterString($_POST['PhoneNumber']);
            $user->GroupId = $this->filterInt($_POST['GroupId']);
            $user->BranchId = $this->filterInt($_POST['BranchId']);

            if($user->save()) {
                $profile->FirstName = $this->filterString($_POST['FirstName']);
                $profile->LastName = $this->filterString($_POST['LastName']);
                $profile->save();
                $this->messenger->add($this->language->get('message_create_success'));
            } else {
                $this->messenger->add($this->language->get('message_create_failed'), Messenger::APP_MESSAGE_ERROR);
            }
            $this->redirect('/users');
        }

        $this->_view();
    }

    public function deleteAction()
    {
        $id = $this->filterInt($this->_params[0]);
        $user = UserModel::getByPK($id);

        if($user === false || $this->session->u->UserId == $id) {
            $this->redirect('/users');
        }

        $this->language->load('users.messages');

        $profile = UserProfileModel::getByPK($user->UserId);

        if($profile->delete() && $user->delete()) {
            $this->messenger->add($this->language->get('message_delete_success'));
        } else {
            $this->messenger->add($this->language->get('message_delete_failed'), Messenger::APP_MESSAGE_ERROR);
        }
        $this->redirect('/users');
    }

    public function resetPasswordAction()
    {
        $id = $this->filterInt($this->_params[0]);
        $user = UserModel::getByPK($id);

        if($user === false || $this->session->u->UserId == $id) {
            $this->redirect('/users');
        }

        $this->language->load('users.messages');

        $user->cryptPassword('123456');

        if($user->save()) {
            $this->messenger->add($this->language->get('message_reset_success'));
        } else {
            $this->messenger->add($this->language->get('message_reset_failed'), Messenger::APP_MESSAGE_ERROR);
        }

        $this->redirect('/users');

        $this->_view();
    }

    public function suspendAction()
    {
        $id = $this->filterInt($this->_params[0]);
        $user = UserModel::getByPK($id);

        if($user === false || $this->session->u->UserId == $id) {
            $this->redirect('/users');
        }

        $this->language->load('users.messages');

        $user->Status = 0;

        if($user->save()) {
            $this->messenger->add($this->language->get('message_suspend_success'));
        } else {
            $this->messenger->add($this->language->get('message_suspend_failed'), Messenger::APP_MESSAGE_ERROR);
        }

        $this->redirect('/users');

        $this->_view();
    }

    public function activateAction()
    {
        $id = $this->filterInt($this->_params[0]);
        $user = UserModel::getByPK($id);

        if($user === false || $this->session->u->UserId == $id) {
            $this->redirect('/users');
        }

        $this->language->load('users.messages');

        $user->Status = 1;

        if($user->save()) {
            $this->messenger->add($this->language->get('message_activated_success'));
        } else {
            $this->messenger->add($this->language->get('message_activated_failed'), Messenger::APP_MESSAGE_ERROR);
        }

        $this->redirect('/users');

        $this->_view();
    }

    public function checkUserExistsAjaxAction()
    {
        if(isset($_POST['Username']) && !empty($_POST['Username'])) {
            header('Content-type: text/plain');
            if(UserModel::userExists($this->filterString($_POST['Username'])) !== false) {
                echo 1;
            } else {
                echo 2;
            }
        }
    }
}