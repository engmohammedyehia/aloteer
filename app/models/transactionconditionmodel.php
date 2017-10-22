<?php
namespace PHPMVC\Models;

class TransactionConditionModel extends AbstractModel
{

    public $ConditionId;
    public $ConditionTitle;
    public $TransactionTypeId;
    public $Required;

    public static $tableName = 'app_transactions_conditions';
    
    protected static $tableSchema = array(
        'ConditionTitle'        => self::DATA_TYPE_STR,
        'TransactionTypeId'     => self::DATA_TYPE_INT,
        'Required'              => self::DATA_TYPE_INT
    );

    protected static $primaryKey = 'ConditionId';

    public static function getAll()
    {
        return self::get('
            SELECT t1.*, t2.TransactionType FROM ' . self::$tableName . ' t1 INNER JOIN 
            ' . TransactionTypeModel::$tableName . ' t2 ON t2.TransactionTypeId = t1.TransactionTypeId
        ');
    }
}