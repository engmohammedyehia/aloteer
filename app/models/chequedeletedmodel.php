<?php
namespace PHPMVC\Models;

class ChequeDeletedModel extends AbstractModel
{

    public $ChequeId;
    public $TransactionId;
    public $AccountId;
    public $ClientId;
    public $UserId;
    public $Amount;
    public $AmountLiteral;
    public $Created;
    public $Status;
    public $HandedOverDate;
    public $BranchId;
    public $ClientName;
    public $Reason;
    public $ChequeNumber;

    public static $tableName = 'app_cheques_deleted';


    protected static $tableSchema = array(
        'TransactionId'             => self::DATA_TYPE_INT,
        'ClientId'                  => self::DATA_TYPE_INT,
        'UserId'                    => self::DATA_TYPE_INT,
        'AccountId'                 => self::DATA_TYPE_INT,
        'Amount'                    => self::DATA_TYPE_INT,
        'AmountLiteral'             => self::DATA_TYPE_STR,
        'Created'                   => self::DATA_TYPE_DATE,
        'Status'                    => self::DATA_TYPE_INT,
        'HandedOverDate'            => self::DATA_TYPE_DATE,
        'BranchId'                  => self::DATA_TYPE_INT,
        'ClientName'                => self::DATA_TYPE_STR,
        'Reason'                    => self::DATA_TYPE_STR,
        'ChequeNumber'              => self::DATA_TYPE_INT
    );

    protected static $primaryKey = 'ChequeId';

    public static function getCanceledCheques()
    {
        return self::get(
            'SELECT t1.*, t2.TransactionTitle, t4.BankName, CONCAT_WS(" ", t5.FirstName, t5.LastName) UserName
                  FROM ' . self::$tableName . ' t1
                  INNER JOIN ' . TransactionModel::getModelTableName() . ' t2 ON t2.TransactionId = t1.TransactionId
                  INNER JOIN ' . BankAccountModel::getModelTableName() . ' t4 ON t4.AccountId = t1.AccountId
                  INNER JOIN ' . UserProfileModel::getModelTableName() . ' t5 ON t5.UserId = t1.UserId'

        );
    }
}