<?php
namespace PHPMVC\Controllers;

use PHPMVC\LIB\Helper;
use PHPMVC\lib\Messenger;
use PHPMVC\Models\UserGroupPrivilegeModel;
use PHPMVC\Models\UserModel;
use PHPMVC\Models\UserProfileModel;
use PHPMVC\Models\UserSettingsModel;

class AuthController extends AbstractController
{
    use Helper;

    public function loginAction()
    {
        $this->language->load('auth.login');

        $this->_template->swapTemplate(
        [
            ':view' => ':action_view'
        ]);

        if(!isset($_COOKIE['menu_opened'])) {
            setcookie('menu_opened', 'true', (time()+60*60*24*180), '/', str_replace('www', '', $_SERVER['HTTP_HOST']));
        }

        $appDisabled = UserSettingsModel::getByKeyGeneral('DisableApp');

        if((int) $appDisabled->TheValue === 2) {
            $appDisabledMessage = UserSettingsModel::getByKeyGeneral('DisableAppMessage');
            $this->_data['disabled'] = $appDisabledMessage->TheValue;
        }

        $this->_view();
    }

    public function authenticateAction()
    {
        if(isset($_POST['ucname']) && isset($_POST['ucpwd'])) {
            $isAuthorized = UserModel::authenticate($_POST['ucname'], $_POST['ucpwd'], $this->session);
            if($isAuthorized === false) {
                echo 3;
            } elseif ($isAuthorized instanceof UserModel) {
                $appDisabled = UserSettingsModel::getByKeyGeneral('DisableApp');
                if((int) $appDisabled->TheValue === 2 && (int) $isAuthorized->GroupId !== 4) {
                    echo 2;
                } else {
                    $this->session->tu = $isAuthorized;
                    echo 1;
                }
            } elseif ($isAuthorized == 2) {
                echo 2;
            }
        }
    }

    public function loadProfileAction()
    {
        if(isset($_POST['ucname'])) {
            $user = $this->session->tu;
            $user->profile = UserProfileModel::getByPK($user->UserId);
            if(false === $this->session->tu->profile) {
                echo 2;
            } else {
                $this->session->tu = $user;
                echo 1;
            }
        }
    }

    public function loadPrivilegesAction()
    {
        if(isset($_POST['ucname'])) {
            $user = $this->session->tu;
            $user->privileges = UserGroupPrivilegeModel::getPrivilegesForGroup($user->GroupId);
            if(false === $this->session->tu->privileges) {
                echo 2;
            } else {
                $this->session->tu = $user;
                echo 1;
            }
        }
    }

    public function doLoginAction()
    {
        if(isset($this->session->tu) && $this->session->tu instanceof UserModel) {
            $this->session->u = $this->session->tu;
            unset($this->session->tu);
            echo 1;
        } else {
            echo 2;
        }
    }

    public function logoutAction()
    {
        // TODO: check the cookie deletion
        $this->session->kill();
        $this->redirect('/auth/login');
    }
}