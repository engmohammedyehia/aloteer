<?php
namespace PHPMVC\Models;

class BankAccountModel extends AbstractModel
{

    public $AccountId;
    public $BankName;
    public $BankAccountIBAN;
    public $BankAccountOwner;
    public $BankAccountUsage;
    public $BranchId;

    public static $tableName = 'app_bank_accounts';

    protected static $tableSchema = array(
        'BankName'                  => self::DATA_TYPE_STR,
        'BankAccountIBAN'           => self::DATA_TYPE_STR,
        'BankAccountOwner'          => self::DATA_TYPE_STR,
        'BankAccountUsage'          => self::DATA_TYPE_STR,
        'BranchId'                  => self::DATA_TYPE_INT
    );

    protected static $primaryKey = 'AccountId';

    public static function getAll()
    {
        return self::get('
            SELECT t1.*, t2.BranchName FROM ' . self::$tableName . ' t1
            INNER JOIN ' . BranchModel::getModelTableName() . ' t2 ON 
            t2.BranchId = t1.BranchId 
        ');
    }
}