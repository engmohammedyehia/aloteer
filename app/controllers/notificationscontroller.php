<?php
namespace PHPMVC\Controllers;

use PHPMVC\LIB\Helper;
use PHPMVC\LIB\InputFilter;
use PHPMVC\lib\Messenger;
use PHPMVC\Models\MailModel;
use PHPMVC\Models\NotificationModel;
use PHPMVC\Models\UserModel;
use PHPMVC\Models\UserProfileModel;

class NotificationsController extends AbstractController
{
    use InputFilter;
    use Helper;

    public function defaultAction()
    {
        $this->_data['notifications'] = NotificationModel::getNotificationsForUser($this->session->u);

        $this->language->load('template.common');
        $this->language->load('notifications.default');
        $this->language->load('notifications.notifications');

        $this->_view();
    }

    public function viewAction()
    {
        $id = $this->filterInt($this->_params[0]);
        $notification = NotificationModel::getByPK($id);

        if($notification === false || $notification->UserId != $this->session->u->UserId) {
            $this->redirect('/notifications');
        }

        if($notification->Seen == 0) {
            $notification->Seen = 1;
            $notification->save();
        }
        $this->redirect($notification->URL);
    }

    public function readAllAction()
    {
        if(NotificationModel::reallAll($this->session->u) === true)
            $this->redirect('/notifications');
    }

    public function truncateAction()
    {
        if(NotificationModel::truncate($this->session->u) === true)
            $this->redirect('/notifications');
    }
}