<?php
namespace PHPMVC\Controllers;

use PHPMVC\LIB\Helper;
use PHPMVC\LIB\InputFilter;
use PHPMVC\lib\Messenger;
use PHPMVC\Models\UserGroupPrivilegeModel;
use PHPMVC\Models\UserModel;
use PHPMVC\Models\UserProfileModel;
use PHPMVC\Models\UserSettingsModel;

class AuthController extends AbstractController
{
    use Helper;
    use InputFilter;

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
            $smsEnabled = $this->session->authwithsms === 'sms' ? true : false;
            $isAuthorized = UserModel::authenticate($_POST['ucname'], $_POST['ucpwd'], $smsEnabled);
            if($isAuthorized === false) {
                echo 3;
            } elseif ($isAuthorized instanceof UserModel) {
                $appDisabled = UserSettingsModel::getByKeyGeneral('DisableApp');
                $startHour = UserSettingsModel::getByKeyGeneral('StartHour');
                $endHour = UserSettingsModel::getByKeyGeneral('EndHour');
                $startHour = new \DateTime(date('Y-m-d ') . $startHour->TheValue);
                $endHour = new \DateTime(date('Y-m-d ') . $endHour->TheValue);
                $now = new \DateTime(date('Y-m-d H:i:s'));
                if((int) $appDisabled->TheValue === 2 && (int) $isAuthorized->GroupId !== 4) {
                    echo 2;
                } elseif((($now <= $startHour || $now >= $endHour) && (int) $isAuthorized->GroupId !== 4)) {
                    echo 4;
                } else {
                    if($this->session->authwithsms === 'sms') {
                        $this->language->load('auth.login');
                        $this->language->swapKey('text_sms_code', [$isAuthorized->SMSCode]);
                        $this->notifyBySMS($isAuthorized->PhoneNumber, $this->language->get('text_sms_code'));
                    }
                    $this->session->tu = $isAuthorized;
                    echo 1;
                }
            } elseif ($isAuthorized == 2) {
                echo 2;
            }
        }
    }

    public function checkSMSAction()
    {
        if(isset($_POST['ucname']) && isset($_POST['code'])) {
            $isAuthorized = UserModel::checkSMS($this->filterString($_POST['ucname']), $this->filterInt($_POST['code']));
            if($isAuthorized === 1) {
                echo 1;
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