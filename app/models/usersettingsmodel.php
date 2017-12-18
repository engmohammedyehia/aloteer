<?php
namespace PHPMVC\Models;

class UserSettingsModel extends AbstractModel
{

    public $SettingId;
    public $TheKey;
    public $TheValue;
    public $UserId;

    protected static $tableName = 'app_users_settings';

    protected static $tableSchema = array(
        'TheKey'        => self::DATA_TYPE_STR,
        'TheValue'      => self::DATA_TYPE_STR,
        'UserId'        => self::DATA_TYPE_INT
    );

    protected static $primaryKey = 'SettingId';
    
    public static function getByKey(string $theKey, UserModel $user)
    {
        return self::getOne('SELECT * FROM ' . self::$tableName . ' WHERE TheKey = "' . $theKey . '" AND UserId = ' . $user->UserId);
    }

    public static function getUserSettings(UserModel $user)
    {
        return self::get('SELECT * FROM ' . self::$tableName . ' WHERE UserId = ' . $user->UserId, [], \PDO::FETCH_ASSOC);
    }

    public static function getByKeyGeneral(string $theKey)
    {
        return self::getOne('SELECT * FROM ' . self::$tableName . ' WHERE TheKey = "' . $theKey . '"');
    }

}