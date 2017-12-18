<?php
namespace PHPMVC\Lib;

use PHPMVC\Models\MailModel;
use PHPMVC\Models\NotificationModel;

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