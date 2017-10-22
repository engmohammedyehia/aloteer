<?php
namespace PHPMVC\Models;

class SubscriptionModel extends AbstractModel
{

    public $SubscriptionId;
    public $MemberId;
    public $Units;
    public $StartDate;
    public $Status;
    public $Created;

    public static $tableName = 'app_subscriptions';
    
    protected static $tableSchema = array(
        'SubscriptionId'   => self::DATA_TYPE_INT,
        'MemberId'         => self::DATA_TYPE_INT,
        'Units'            => self::DATA_TYPE_INT,
        'StartDate'        => self::DATA_TYPE_DATE,
        'Created'          => self::DATA_TYPE_DATE,
        'Status'           => self::DATA_TYPE_BOOL,
    );

    protected static $primaryKey = 'SubscriptionId';

    public static function getAll()
    {
        $sql = 'SELECT asup.*, am.name name, am.id ref FROM ' . self::$tableName . ' asup';
        $sql .= ' INNER JOIN ' . MemberModel::$tableName . ' am ON asup.MemberId = am.id';
        return self::get($sql);
    }
}