<?php
namespace PHPMVC\Models;

class BankBranchModel extends AbstractModel
{

    public $BankBranchId;
    public $BankBranchName;
    public $BranchId;

    public static $tableName = 'app_bank_branches';

    protected static $tableSchema = array(
        'BankBranchName'            => self::DATA_TYPE_STR,
        'BranchId'                  => self::DATA_TYPE_INT
    );

    protected static $primaryKey = 'BankBranchId';

    public static function getAll()
    {
        return self::get('
            SELECT t1.*, t2.BranchName FROM ' . self::$tableName . ' t1
            INNER JOIN ' . BranchModel::getModelTableName() . ' t2 ON 
            t2.BranchId = t1.BranchId 
        ');
    }
}