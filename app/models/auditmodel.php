<?php
namespace PHPMVC\Models;

class AuditModel extends AbstractModel
{

    public $AssignmentId;
    public $TransactionId;
    public $AssignedBy;
    public $UserId;
    public $Created;

    public static $tableName = 'app_transactions_audit_assignments';
    
    protected static $tableSchema = array(
        'TransactionId'         => self::DATA_TYPE_INT,
        'AssignedBy'            => self::DATA_TYPE_INT,
        'UserId'                => self::DATA_TYPE_INT,
        'Created'               => self::DATA_TYPE_STR
    );

    protected static $primaryKey = 'AssignmentId';

    public static function getAll()
    {
        return self::get(
            'SELECT t1.*, DATE_FORMAT(t1.Created, "%Y-%m-%d") Created, t2.TransactionTitle, 
                    CONCAT_WS(" ", t3.FirstName, t3.LastName) AssignedByName, 
                    CONCAT_WS(" ", t4.FirstName, t4.LastName) AssignedToName
                    FROM ' . self::$tableName . ' t1 
                    INNER JOIN ' . TransactionModel::getModelTableName() . ' t2
                    ON t2.TransactionId = t1.TransactionId
                    INNER JOIN ' . UserProfileModel::getModelTableName() . ' t3
                    ON t3.UserId = t1.AssignedBy
                    INNER JOIN ' . UserProfileModel::getModelTableName() . ' t4
                    ON t4.UserId = t1.UserId
                 '
        );
    }
}