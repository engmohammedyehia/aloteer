<?php
namespace PHPMVC\Models;

class BankAccountModel extends AbstractModel
{

    public $AccountId;
    public $BankName;
    public $BankAccountNumber;
    public $BankAccountIBAN;

    public static $tableName = 'app_bank_accounts';

    protected static $tableSchema = array(
        'BankName'                  => self::DATA_TYPE_STR,
        'BankAccountNumber'         => self::DATA_TYPE_STR,
        'BankAccountIBAN'           => self::DATA_TYPE_STR
    );

    protected static $primaryKey = 'AccountId';
}