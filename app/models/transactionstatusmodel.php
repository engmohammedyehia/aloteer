<?php
namespace PHPMVC\Models;

class TransactionStatusModel extends AbstractModel
{

    public $StatusId;
    public $TransactionId;
    public $UserId;
    public $StatusType;
    public $Created;
    public $Note;

    const STATUS_TRANSACTION_CREATED = 1;
    const STATUS_TRANSACTION_APPROVED_BY_MANAGER = 2;
    const STATUS_TRANSACTION_UNDER_REVIEW = 3;
    const STATUS_TRANSACTION_REVIEWED = 4;
    const STATUS_TRANSACTION_CHEQUE_ORDERED = 5;
    const STATUS_TRANSACTION_CHEQUE_PRINTED = 6;
    const STATUS_TRANSACTION_CEO_REVIEW = 7;
    const STATUS_TRANSACTION_CHEQUE_READY = 8;
    const STATUS_TRANSACTION_CHEQUE_READY_NO_COVERAGE = 9;
    const STATUS_TRANSACTION_CHEQUE_HANDED_TO_CLIENT = 10;
    const STATUS_TRANSACTION_SUSPENDED = 11;
    const STATUS_TRANSACTION_CLOSED = 12;

    public static $tableName = 'app_transactions_statuses';
    
    protected static $tableSchema = array(
        'TransactionId'         => self::DATA_TYPE_INT,
        'UserId'                => self::DATA_TYPE_INT,
        'StatusType'            => self::DATA_TYPE_INT,
        'Created'               => self::DATA_TYPE_STR,
        'Note'                  => self::DATA_TYPE_STR
    );

    protected static $primaryKey = 'StatusId';

    public static function getStatusesForTransaction(TransactionModel $transaction)
    {
        return self::get(
            'SELECT t1.*, CONCAT_WS(" ", t2.FirstName, t2.LastName) UserName 
                  FROM ' . self::$tableName . ' t1 INNER JOIN ' . UserProfileModel::getModelTableName() . ' t2
                  ON t2.UserId = t1.UserId WHERE t1.TransactionId = ' . $transaction->TransactionId
        );
    }
}