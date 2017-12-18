<?php
namespace PHPMVC\Models;

use PHPMVC\Lib\Database\DatabaseHandler;

class UserModel extends AbstractModel
{
    public $UserId;
    public $Username;
    public $Password;
    public $Email;
    public $PhoneNumber;
    public $SubscriptionDate;
    public $LastLogin;
    public $GroupId;
    public $Status;
    public $BranchId;

    /**
     * @var UserProfileModel
     */
    public $profile;
    public $privileges;

    protected static $tableName = 'app_users';

    protected static $tableSchema = array(
        'UserId'            => self::DATA_TYPE_INT,
        'Username'          => self::DATA_TYPE_STR,
        'Password'          => self::DATA_TYPE_STR,
        'Email'             => self::DATA_TYPE_STR,
        'PhoneNumber'       => self::DATA_TYPE_STR,
        'SubscriptionDate'  => self::DATA_TYPE_DATE,
        'LastLogin'         => self::DATA_TYPE_STR,
        'GroupId'           => self::DATA_TYPE_INT,
        'BranchId'          => self::DATA_TYPE_INT,
        'Status'            => self::DATA_TYPE_INT,
    );

    protected static $primaryKey = 'UserId';

    public function cryptPassword($password)
    {
        $this->Password = crypt($password, APP_SALT);
    }

    // TODO:: FIX THE TABLE ALIASING
    public static function getUsers(UserModel $user)
    {
        return self::get(
        'SELECT au.*, aug.GroupName GroupName, ab.BranchName BranchName, aup.FirstName, aup.LastName FROM ' . self::$tableName . ' au INNER JOIN app_users_groups aug ON aug.GroupId = au.GroupId INNER JOIN app_branches ab ON ab.BranchId = au.BranchId INNER JOIN app_users_profiles aup ON aup.UserId = au.UserId WHERE au.UserId != ' . $user->UserId
        );
    }

    public static function getUsersByType($type, $branch = false)
    {
        return self::get(
            'SELECT au.*, aug.GroupName GroupName, ab.BranchName BranchName, CONCAT_WS(" ", aup.FirstName, aup.LastName) Name FROM ' . self::$tableName . ' au INNER JOIN app_users_groups aug ON aug.GroupId = au.GroupId INNER JOIN app_branches ab ON ab.BranchId = au.BranchId INNER JOIN app_users_profiles aup ON aup.UserId = au.UserId WHERE au.GroupId = ' . $type . ((false !== $branch) ? ' AND au.BranchId = ' . $branch : '')
        );
    }

    public static function userExists($username)
    {
        return self::get('
            SELECT * FROM ' . self::$tableName . ' WHERE Username = "' . $username . '"
        ');
    }

    public static function emailExists($email)
    {
        return self::get('
            SELECT * FROM ' . self::$tableName . ' WHERE Email = "' . $email . '"
        ');
    }

    public static function authenticate ($username, $password, $session)
    {
        $password = crypt($password, APP_SALT) ;
        $sql = 'SELECT *, (SELECT GroupName FROM app_users_groups WHERE app_users_groups.GroupId = ' . self::$tableName . '.GroupId) GroupName FROM ' . self::$tableName . ' WHERE Username = "' . $username . '" AND Password = "' .  $password . '"';
        $foundUser = self::getOne($sql);

        $appDisables = UserSettingsModel::getByKeyGeneral('DisableApp');

        if(false !== $foundUser) {
            if($foundUser->Status == 2) {
                return 2;
            }
            $foundUser->LastLogin = date('Y-m-d H:i:s');
            $foundUser->save();
            return $foundUser;
        }
        return false;
    }

    public function getUserRecords ()
    {
        return self::get(
            'SELECT UserId, 
                  (SELECT COUNT(*) FROM app_cheques WHERE UserId = ' . $this->UserId . ' LIMIT 1) c1, 
                  (SELECT COUNT(*) FROM app_mail WHERE receiverId = ' . $this->UserId . ' or senderId = ' . $this->UserId . ' LIMIT 1) c2, 
                  (SELECT COUNT(*) FROM app_notifications WHERE UserId = ' . $this->UserId . ') c3,
                  (SELECT COUNT(*) FROM app_transactions WHERE UserId = ' . $this->UserId . ') c4,
                  (SELECT COUNT(*) FROM app_transactions_audit_assignments WHERE UserId = ' . $this->UserId . ') c5,
                  (SELECT COUNT(*) FROM app_transactions_audit_assignments_results WHERE UserId = ' . $this->UserId . ') c6, 
                  (SELECT COUNT(*) FROM app_transactions_statuses WHERE UserId = ' . $this->UserId . ') c7,
                  (SELECT COUNT(*) FROM app_users_settings WHERE UserId = ' . $this->UserId . ') c8 
                  FROM app_users WHERE UserId = ' . $this->UserId
        );
    }

    public function superAdminDelete ()
    {

    }
}