<?php
namespace PHPMVC\Controllers;

use PHPMVC\LIB\Helper;
use PHPMVC\LIB\InputFilter;
use PHPMVC\lib\Messenger;
use PHPMVC\Models\MailModel;
use PHPMVC\Models\UserModel;
use PHPMVC\Models\UserProfileModel;

class MailController extends AbstractController
{
    use InputFilter;
    use Helper;

    public function defaultAction()
    {
        $mailSQL = 'SELECT am.*, au.Username as sender FROM app_mail as am ';
        $mailSQL .= 'LEFT JOIN app_users as au ON am.senderId = au.UserId ';
        $mailSQL .= 'WHERE am.receiverId = ' . $this->session->u->UserId . ' ';
        $mailSQL .= 'ORDER BY am.created DESC';
        $this->_data['mail'] = MailModel::get($mailSQL);

        $this->language->load('template.common');
        $this->language->load('mail.default');

        $this->_view();
    }

    public function sentAction()
    {
        $mailSQL = 'SELECT am.*, au.Username as receiver FROM app_mail as am ';
        $mailSQL .= 'LEFT JOIN app_users as au ON am.receiverId = au.UserId ';
        $mailSQL .= 'WHERE am.senderId = ' . $this->session->u->UserId . ' ';
        $mailSQL .= 'ORDER BY am.created DESC';

        $this->_data['mail'] = MailModel::get($mailSQL);

        $this->language->load('template.common');
        $this->language->load('mail.sent');

        $this->_view();
    }

    public function newAction()
    {
        $this->_data['allowedUsers'] = UserModel::getUsers($this->session->u);

        $this->language->load('template.common');
        $this->language->load('mail.common');
        $this->language->load('mail.new');
        $this->language->load('mail.messages');

        if(isset($_POST['submit']) &&
            $this->requestHasValidToken()
        ) {

            $receiversList = $_POST['receiverId'];

            if(empty($receiversList) || $_POST['content'] === '') {
                $this->redirect('/mail');
            }

            $content = $this->filterString($_POST['content']);
            $title = $this->filterString($_POST['title']);
            $mail = null;

            foreach($receiversList as $receiver) {
                $mail = new MailModel;
                $mail->senderId = $this->session->u->UserId;
                $receiverObj = UserModel::getByPK($this->filterString($receiver));
                if($receiverObj === false)  {
                    $failedMail = new MailModel();
                    $failedMail->senderId = $this->session->u->UserId;
                    $failedMail->receiverId = $this->session->u->UserId;
                    $failedMail->created = date('Y-m-d H:i:s');
                    $failedMail->content = $this->lang->get('receiver_not_found_message', array($receiver));
                    $failedMail->title = $this->lang->get('receiver_not_found_title');
                    $failedMail->seen = 0;
                    $failedMail->save();
                    continue;
                }
                $mail->receiverId = $receiverObj->UserId;
                $mail->created = date('Y-m-d H:i:s');
                $mail->content = $content;
                $mail->title = $title;
                $mail->seen = 0;
                $mail->save();
            }
            $this->messenger->add($this->language->get('mail_sent_success'));
            $this->redirect('/mail');
        }

        $this->_view();
    }

    public function viewAction()
    {

        $id = $this->filterInt($this->_params[0]);
        $mail = MailModel::getByPK($id);

        if($mail === false) {
            $this->redirect('/mail');
        }

        $this->_data['mail'] = $mail;
        $this->_data['sender'] = UserProfileModel::getByPK($mail->senderId);

        if($this->session->u->UserId === $mail->senderId) {
            goto sender;
        }

        if($mail->receiverId != $this->session->u->UserId) {
            $this->redirect('/mail');
        }

        if($mail->seen == 0) {
            $mail->seen = 1;
            $mail->save();
            $this->redirect('/mail/view/'.$mail->id);
        }

        sender:
        $this->language->load('template.common');
        $this->language->load('mail.common');
        $this->language->load('mail.view');

        $this->_view();
    }

    public function forwardAction()
    {
        $id = $this->filterInt($this->_params[0]);
        $mail = MailModel::getByPK($id);

        if($mail === false || $mail->receiverId != $this->session->u->UserId) {
            $this->redirect('/mail');
        }

        $this->_data['mtitle'] = $mail->title;
        $this->_data['content'] = $mail->content;


        $this->_data['allowedUsers'] = UserModel::getUsers($this->session->u);

        $this->language->load('template.common');
        $this->language->load('mail.common');
        $this->language->load('mail.forward');
        $this->language->load('mail.messages');

        if(isset($_POST['submit']) &&
            $this->requestHasValidToken()
        ) {

            $receiversList = $_POST['receiverId'];

            if(empty($receiversList)) {
                $this->redirect('/mail');
            }

            $content = $this->filterString($_POST['content']);
            $title = $this->filterString($_POST['title']);
            $mail = null;

            foreach($receiversList as $receiver) {
                $mail = new MailModel;
                $mail->senderId = $this->session->u->UserId;
                $receiverObj = UserModel::getByPK($this->filterString($receiver));
                if($receiverObj === false)  {
                    $failedMail = new MailModel();
                    $failedMail->senderId = $this->session->u->UserId;
                    $failedMail->receiverId = $this->session->u->UserId;
                    $failedMail->created = date('Y-m-d H:i:s');
                    $failedMail->content = $this->lang->get('receiver_not_found_message', array($receiver));
                    $failedMail->title = $this->lang->get('receiver_not_found_title');
                    $failedMail->seen = 0;
                    $failedMail->save();
                    continue;
                }
                $mail->receiverId = $receiverObj->UserId;
                $mail->created = date('Y-m-d H:i:s');
                $mail->content = $content;
                $mail->title = $title;
                $mail->seen = 0;
                $mail->save();
            }

            $this->messenger->add($this->language->get('mail_sent_success'));
            $this->redirect('/mail');
        }

        $this->_view();
    }

    public function replyAction()
    {
        $id = $this->filterInt($this->_params[0]);
        $mail = MailModel::getByPK($id);

        if($mail === false || $mail->receiverId != $this->session->u->UserId) {
            $this->redirect('/mail');
        }

        $this->_data['mail'] = $mail;

        $this->language->load('template.common');
        $this->language->load('mail.common');
        $this->language->load('mail.reply');

        if(isset($_POST['submit']) &&
            $this->requestHasValidToken()
        ) {

            $newmail = new MailModel();
            $newmail->senderId = $this->session->u->UserId;
            $newmail->receiverId = $mail->senderId;
            $newmail->created = date('Y-m-d H:i:s');
            $newmail->content = $this->filterString($_POST['content']);
            $newmail->title = $this->filterString($_POST['title']);
            $newmail->seen = 0;
            $newmail->save();

            $this->messenger->add($this->language->get('mail_sent_success'));
            $this->redirect('/mail');
        }

        $this->_view();
    }

    public function deleteAction()
    {
        if(!$this->requestHasValidToken()) {
            $this->redirect('/mail');
        }

        $id = $this->filterInt($this->_params[0]);
        $mail = MailModel::getByPK($id);

        if($mail === false || $mail->receiverId != $this->session->u->UserId) {
            $this->redirect('/mail');
        }

        $this->language->load('mail.messages');
        if($mail->delete()) {
            $this->messenger->add($this->language->get('mail_delete_success'));
        } else {
            $this->messenger->add($this->language->get('mail_delete_failed'), Messenger::APP_MESSAGE_ERROR);
        }
        $this->redirect('/mail');
    }
}