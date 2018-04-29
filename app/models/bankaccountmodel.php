<?php
namespace PHPMVC\Models;

class BankAccountModel extends AbstractModel
{

    public $AccountId;
    public $BankName;
    public $BankAccountIBAN;
    public $BankAccountNumber;
    public $BankAccountOwner;
    public $BankAccountUsage;
    public $BankBranchId;

    public static $tableName = 'app_bank_accounts';

    protected static $tableSchema = array(
        'BankName'                  => self::DATA_TYPE_STR,
        'BankAccountIBAN'           => self::DATA_TYPE_STR,
        'BankAccountNumber'         => self::DATA_TYPE_STR,
        'BankAccountOwner'          => self::DATA_TYPE_STR,
        'BankAccountUsage'          => self::DATA_TYPE_STR,
        'BankBranchId'              => self::DATA_TYPE_INT
    );

    protected static $primaryKey = 'AccountId';

    public static function getAll()
    {
        return self::get('
            SELECT t1.*, t2.BankBranchName BranchName FROM ' . self::$tableName . ' t1
            INNER JOIN ' . BankBranchModel::getModelTableName() . ' t2 ON 
            t2.BankBranchId = t1.BankBranchId 
        ');
    }
}