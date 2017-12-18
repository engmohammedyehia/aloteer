<?php
namespace PHPMVC\Controllers;

use PHPMVC\lib\FileUpload;
use PHPMVC\LIB\Helper;
use PHPMVC\LIB\InputFilter;
use PHPMVC\lib\Messenger;
use PHPMVC\Models\BranchModel;
use PHPMVC\Models\UserGroupModel;
use PHPMVC\Models\UserModel;
use PHPMVC\Models\UserProfileModel;
use PHPMVC\Models\UserSettingsModel;

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

            if(UserModel::emailExists($user->Email)) {
                $this->messenger->add($this->language->get('message_email_exists'), Messenger::APP_MESSAGE_ERROR);
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

        $records = $user->getUserRecords()->current();

        if ($records->c1 > 0 || $records->c2 > 0 || $records->c3 > 0 || $records->c4 > 0 || $records->c5 > 0 || $records->c6 > 0 || $records->c7 > 0 || $records->c8 > 0) {
            $this->messenger->add($this->language->get('message_user_has_records'), Messenger::APP_MESSAGE_ERROR);
            $this->redirect('/users');
        }

        $profile = UserProfileModel::getByPK($user->UserId);

        if($profile->delete() && $user->delete()) {
            $this->messenger->add($this->language->get('message_delete_success'));
        } else {
            $this->messenger->add($this->language->get('message_delete_failed'), Messenger::APP_MESSAGE_ERROR);
        }
        $this->redirect('/users');
    }

    public function _forceDeleteAction()
    {
        $id = $this->filterInt($this->_params[0]);
        $user = UserModel::getByPK($id);

        if($user === false || $this->session->u->UserId == $id) {
            $this->redirect('/users');
        }

        $this->language->load('users.messages');

        if($user->superAdminDelete()) {
            $this->messenger->add($this->language->get('message_delete_success'));
        } else {
            $this->messenger->add($this->language->get('message_delete_failed'), Messenger::APP_MESSAGE_ERROR);
        }
        $this->redirect('/users');
    }

    public function profileAction()
    {
        $profile = $this->_data['profile'] = $this->session->u->profile;
        $user = $this->_data['user'] = $this->session->u;

        $this->language->load('template.common');
        $this->language->load('users.profile');
        $this->language->load('users.labels');
        $this->language->load('users.messages');
        $this->language->load('validation.errors');

        if(isset($_POST['submit']) && $this->requestHasValidToken()) {

            $profile->FirstName = $this->filterString($_POST['FirstName']);
            $profile->LastName = $this->filterString($_POST['LastName']);
            $profile->Address = $this->filterString($_POST['Address']);
            $profile->DOB = $this->filterString($_POST['DOB']);
            $user->PhoneNumber = $this->filterString($_POST['PhoneNumber']);

            if(!empty($_FILES['Image']['name'])) {
                $uploader = new FileUpload($_FILES['Image']);
                try {
                    $uploader->remove($profile->Image);
                    $uploader->upload();
                    $profile->Image = $uploader->getFileName();
                } catch (\Exception $e) {
                    $this->messenger->add($e->getMessage(), Messenger::APP_MESSAGE_ERROR);
                }
            }

            if(!$uploader->hasError && $profile->save() && $user->save()) {
                $user->profile = $profile;
                $this->session->u = $user;
                $this->messenger->add($this->language->get('message_profile_saved_success'));
                $this->redirect('/users/profile');
            } else {
                $this->messenger->add($this->language->get('message_profile_saved_failed'), Messenger::APP_MESSAGE_ERROR);
            }
        }

        $this->_view();
    }

    public function changePasswordAction()
    {
        $user = $this->_data['user'] = $this->session->u;

        $this->language->load('template.common');
        $this->language->load('users.changepassword');
        $this->language->load('users.labels');
        $this->language->load('users.messages');
        $this->language->load('validation.errors');

        if(isset($_POST['submit']) && $this->requestHasValidToken()) {
            $newPassword = $_POST['Password'];
            $newPasswordConfirmed = $_POST['PasswordConfirm'];
            $oldPassword = crypt($_POST['OldPassword'], APP_SALT);
            if($oldPassword !== $user->Password) {
                $this->messenger->add($this->language->get('message_old_password_wrong'), Messenger::APP_MESSAGE_ERROR);
            } elseif($newPassword !== $newPasswordConfirmed) {
                $this->messenger->add($this->language->get('message_password_no_match'), Messenger::APP_MESSAGE_ERROR);
            } else {
                $user->Password = crypt($newPassword, APP_SALT);
                $user->save();
                $this->session->u = $user;
                $this->messenger->add($this->language->get('message_change_success'));
                $this->redirect('/users/changepassword');
            }
        }

        $this->_view();
    }

    public function settingsAction()
    {

        $this->language->load('template.common');
        $this->language->load('users.settings');
        $this->language->load('users.labels');
        $this->language->load('users.messages');
        $this->language->load('validation.errors');

        $userSettings = UserSettingsModel::getUserSettings($this->session->u);
        $userSettingsFiltered = [];

        if(false !== $userSettings) {
            foreach ($userSettings as $userSetting) {
                $userSettingsFiltered[$userSetting['TheKey']] = $userSetting['TheValue'];
            }
        }

        $this->_data['userSettings'] = $userSettingsFiltered;
        $doneSaving = false;

        if(isset($_POST['submit']) && $this->requestHasValidToken()) {
            foreach ($_POST as $key => $value) {
                if($key === 'token' || $key === 'submit') continue;
                $settings = UserSettingsModel::getByKey($key, $this->session->u);
                if(false === $settings) {
                    $settings = new UserSettingsModel();
                    $settings->TheKey = $key;
                    $settings->UserId = $this->session->u->UserId;
                }
                $settings->TheValue = $_POST[$key] === '' ? '' : $this->filterString($value);
                if($settings->save()) {
                    if($doneSaving === true) continue;
                    $this->messenger->add($this->language->get('message_settings_saved_success'));
                    $doneSaving = true;
                } else {
                    $this->messenger->add($this->language->get('message_settings_saved_failed'), Messenger::APP_MESSAGE_ERROR);
                }
            }
            $this->redirect('/users/settings');
        }

        $this->_view();
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