<?php
namespace PHPMVC\Models;

class SignatureRequestModel extends AbstractModel
{

    public $RequestId;
    public $TransactionId;
    public $UserId;
    public $Approved;
    public $ApproveDate;

    public static $tableName = 'app_transactions_signatures_requests';
    
    protected static $tableSchema = array(
        'TransactionId'         => self::DATA_TYPE_INT,
        'UserId'                => self::DATA_TYPE_INT,
        'Approved'              => self::DATA_TYPE_BOOL,
        'ApproveDate'          => self::DATA_TYPE_STR
    );

    protected static $primaryKey = 'RequestId';

    public static function getMySignatures(UserModel $user)
    {
        return self::get(
            'SELECT t1.*, t2.TransactionTitle Transaction FROM ' . self::$tableName . ' t1
                  INNER JOIN ' . TransactionModel::getModelTableName() . ' t2 ON t2.TransactionId = t1.TransactionId
                  WHERE t1.UserId = ' . $user->UserId
        );
    }

    public static function getSignaturesForTransaction (TransactionModel $transaction)
    {
        return self::get(
            'SELECT t1.*, CONCAT_WS(" ", t2.FirstName, t2.LastName) EmpName, t2.Signature FROM ' . self::$tableName . ' t1
                  INNER JOIN ' . UserProfileModel::getModelTableName() . ' t2 ON t2.UserId = t1.UserId
                  WHERE t1.TransactionId = ' . $transaction->TransactionId
        );
    }
}