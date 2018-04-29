<?php
namespace PHPMVC\Lib;

use PHPMVC\Models\MailModel;
use PHPMVC\Models\NotificationModel;
use PHPMVC\Models\UserSettingsModel;

final class Startup
{
    private $data = array();

    /**
     * @var SessionManager $session
     */
    private $session;

    /**
     * @var Authentication $auth;
     */
    private $auth;
    
    public function __construct(SessionManager $session, Authentication $authentication) {

        $this->session = $session;
        $this->auth = $authentication;

        if($this->auth->isAuthorized() === true) {
            $reflection = new \ReflectionClass(__CLASS__);
            $methods = $reflection->getMethods(\ReflectionMethod::IS_PRIVATE);

            if(!empty($methods)) {
                foreach ($methods as $method) {
                    call_user_func(array($this, $method->name));
                }
            }
        } else {
            $this->getHijriDate();
        }
    }
    
    private function getTotalMail()
    {
        if($this->auth->isAuthorized() === true) {
            $userId = $this->session->u->UserId;
            $total = MailModel::get('SELECT t1.*, CONCAT_WS(" ", t2.FirstName, t2.LastName) SenderName, t2.Image ProfileImage FROM ' . MailModel::getModelTableName() . ' t1 INNER JOIN app_users_profiles t2 ON t2.UserId = t1.senderId WHERE t1.seen = 0 AND t1.receiverId = ' . $userId . ' LIMIT 4');
            $this->mailTotal = ($total !== false) ? $total : 0;
        }
    }
    
    private function getTotalNotifications()
    {

        if($this->auth->isAuthorized() === true) {
            $userId = $this->session->u->UserId;
            $total = NotificationModel::get('SELECT * FROM app_notifications WHERE Seen = 0 AND UserId = ' . $userId . ' ORDER BY Created DESC LIMIT 5');
            $this->notificationsTotal = ($total !== false) ? $total : 0;
        }
    }

    private function getHijriDate()
    {
        $chequePath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        if((bool) preg_match('#' . preg_quote('/cheques') . '#i', $chequePath) === true ||
            (bool) preg_match('#' . preg_quote('/auth/login') . '#i', $chequePath) === true) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_URL,'https://api.aladhan.com/gToH?date='.date('d-m-Y'));
            $hijriDate=curl_exec($ch);
            curl_close($ch);
            $hijriDate = json_decode($hijriDate);
            $hijriDate = new \DateTime($hijriDate->data->hijri->date);
            $hijriCorrection = UserSettingsModel::getByKeyGeneral('CorrectHijriDate');
            if(false !== $hijriCorrection) {
                $intervalStr = 'P' . abs($hijriCorrection->TheValue) . 'D';
                $interval = new \DateInterval($intervalStr);
                $hijriDate->sub($interval);
            }
            $this->_hijri_ = $hijriDate->format('Y-m-d');
        }
    }

    public function __get($key)
    {
        if(array_key_exists($key, $this->data)) {
            return $this->data[$key];
        } else {
            trigger_error('No key ' . $key . ' found in startup data array', E_USER_NOTICE);
        }
    }
    
    public function __set($key, $value)
    {
        $this->data[$key] = $value;
    }
}