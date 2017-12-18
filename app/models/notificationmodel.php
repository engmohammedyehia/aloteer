<?php
namespace PHPMVC\Models;

use PHPMVC\Lib\Database\DatabaseHandler;

class NotificationModel extends AbstractModel
{

    public $NotificationId;
    public $UserId;
    public $NotificationType;
    public $Content;
    public $Created;
    public $Seen;
    public $URL;

    protected static $tableName = 'app_notifications';

    protected static $tableSchema = array(
        'UserId'                => self::DATA_TYPE_INT,
        'NotificationType'      => self::DATA_TYPE_STR,
        'Content'               => self::DATA_TYPE_STR,
        'Created'               => self::DATA_TYPE_STR,
        'URL'                   => self::DATA_TYPE_STR,
        'Seen'                  => self::DATA_TYPE_BOOL
    );

    protected static $primaryKey = 'NotificationId';

    public static function sendNotification($users, $notificationType, $content, $url)
    {
        if(false === $users) return;
        $notification = new self();
        foreach ($users as $user) {
            $notification->NotificationId = null;
            $notification->UserId = $user->UserId;
            $notification->NotificationType = $notificationType;
            $notification->Content = $content;
            $notification->Created = date('Y-m-d H:i:s');
            $notification->Seen = 0;
            $notification->URL = $url;
            $notification->save();
        }
    }

    public static function reallAll(UserModel $user)
    {
        $sql = 'UPDATE ' . self::$tableName . ' SET Seen = 1 WHERE UserId = ' . $user->UserId;
        $stmt = DatabaseHandler::factory()->prepare($sql);
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public static function truncate(UserModel $user)
    {
        $sql = 'DELETE FROM ' . self::$tableName . ' WHERE UserId = ' . $user->UserId;
        $stmt = DatabaseHandler::factory()->prepare($sql);
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public static function getNotificationsForUser($user)
    {
        return self::get('SELECT * FROM ' . self::$tableName . ' WHERE UserId = ' . $user->UserId);
    }
}