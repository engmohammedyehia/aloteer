<?php
namespace PHPMVC\Models;

class AuditAssignmentResultModel extends AbstractModel
{

    public $AuditConditionId;
    public $ConditionId;
    public $TransactionId;
    public $AuditId;
    public $Created;
    public $UserId;

    public static $tableName = 'app_transactions_audit_assignments_results';
    
    protected static $tableSchema = array(
        'ConditionId'           => self::DATA_TYPE_INT,
        'TransactionId'         => self::DATA_TYPE_INT,
        'AuditId'               => self::DATA_TYPE_INT,
        'UserId'                => self::DATA_TYPE_INT,
        'Created'               => self::DATA_TYPE_STR,

    );

    protected static $primaryKey = 'AuditConditionId';

    public static function transactionIsSatisfied (TransactionModel $transaction)
    {
        $results = self::get(
        ' SELECT t1.ConditionId, t2.ConditionId, 
              (SELECT COUNT(*) FROM app_transactions_conditions WHERE TransactionTypeId = ' . $transaction->TransactionTypeId . ' AND Required = 1) requiredConditions 
              FROM app_transactions_conditions t1 
              RIGHT JOIN app_transactions_audit_assignments_results t2 
              ON t1.ConditionId = t2.ConditionId 
              WHERE t2.TransactionId = ' . $transaction->TransactionId . ' AND t1.Required = 1'
        );
        $oneItem = $results[0];
        if((int) count($results) === (int) $oneItem->requiredConditions) {
            return true;
        }
        return false;
    }

    public static function getPreviousConditions(UserModel $user, TransactionModel $transaction)
    {
        return self::getColumn(
            'SELECT * FROM ' . self::$tableName . ' WHERE UserId = ' . $user->UserId . ' AND TransactionId = ' . $transaction->TransactionId
        , 1);
    }
}