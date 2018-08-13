<?php
namespace PHPMVC\Models;

class ChequeModel extends AbstractModel
{

    public $ChequeId;
    public $TransactionId;
    public $AccountId;
    public $ClientId;
    public $UserId;
    public $Amount;
    public $AmountLiteral;
    public $Created;
    public $CreatedJ;
    public $Status;
    public $HandedOverDate;
    public $HandedOverDateJ;
    public $BranchId;
    public $ClientName;
    public $Reason;
    public $ChequeNumber;
    public $handedToTheFirstBeneficier;

    public static $tableName = 'app_cheques';

    const CHEQUE_ORDER_CREATED                      = 1;
    const CHEQUE_ORDER_PRINTING                     = 2;
    const CHEQUE_ORDER_PRINTED                      = 3;
    const CHEQUE_ORDER_READY_BALANCE_COVERED        = 4;
    const CHEQUE_ORDER_READY_BALANCE_NOT_COVERED    = 5;
    const CHEQUE_ORDER_HANDED_TO_CLIENT             = 6;
    const CHEQUE_ORDER_CLEARED                      = 7;

    protected static $tableSchema = array(
        'TransactionId'             => self::DATA_TYPE_INT,
        'ClientId'                  => self::DATA_TYPE_INT,
        'UserId'                    => self::DATA_TYPE_INT,
        'AccountId'                 => self::DATA_TYPE_INT,
        'Amount'                    => self::DATA_TYPE_INT,
        'AmountLiteral'             => self::DATA_TYPE_STR,
        'Created'                   => self::DATA_TYPE_DATE,
        'CreatedJ'                  => self::DATA_TYPE_DATE,
        'Status'                    => self::DATA_TYPE_INT,
        'HandedOverDate'            => self::DATA_TYPE_DATE,
        'HandedOverDateJ'           => self::DATA_TYPE_DATE,
        'BranchId'                  => self::DATA_TYPE_INT,
        'ClientName'                => self::DATA_TYPE_STR,
        'Reason'                    => self::DATA_TYPE_STR,
        'ChequeNumber'              => self::DATA_TYPE_INT,
        'handedToTheFirstBeneficier'=> self::DATA_TYPE_BOOL
    );

    protected static $primaryKey = 'ChequeId';

    public static function getAll()
    {
        return self::get(
            'SELECT t1.*, t2.TransactionTitle, t4.BankName, CONCAT_WS(" ", t5.FirstName, t5.LastName) UserName, (SELECT BankBranchName From ' . BankBranchModel::getModelTableName() . ' WHERE BankBranchId = (SELECT BankBranchId FROM ' . BankAccountModel::getModelTableName() .  ' WHERE AccountId = t1.AccountId)) BankBranchName
                  FROM ' . self::$tableName . ' t1
                  INNER JOIN ' . TransactionModel::getModelTableName() . ' t2 ON t2.TransactionId = t1.TransactionId
                  INNER JOIN ' . BankAccountModel::getModelTableName() . ' t4 ON t4.AccountId = t1.AccountId
                  INNER JOIN ' . UserProfileModel::getModelTableName() . ' t5 ON t5.UserId = t1.UserId
                  WHERE t1.Status = ' . self::CHEQUE_ORDER_CREATED

        );
    }

    public static function getPrintingCheques()
    {
        return self::get(
            'SELECT t1.*, t2.TransactionTitle, t4.BankName, CONCAT_WS(" ", t5.FirstName, t5.LastName) UserName, t10.BranchName, 
                  (SELECT BankBranchName From ' . BankBranchModel::getModelTableName() . ' WHERE BankBranchId = (SELECT BankBranchId FROM ' . BankAccountModel::getModelTableName() .  ' WHERE AccountId = t1.AccountId)) BankBranchName
                  FROM ' . self::$tableName . ' t1
                  INNER JOIN ' . TransactionModel::getModelTableName() . ' t2 ON t2.TransactionId = t1.TransactionId
                  INNER JOIN ' . BankAccountModel::getModelTableName() . ' t4 ON t4.AccountId = t1.AccountId
                  INNER JOIN ' . UserProfileModel::getModelTableName() . ' t5 ON t5.UserId = t1.UserId
                  INNER JOIN ' . BranchModel::getModelTableName() . ' t10 ON t10.BranchId = t1.BranchId
                  WHERE t1.Status = ' . self::CHEQUE_ORDER_PRINTING

        );
    }

    public static function getPrintedCheques()
    {
        return self::get(
            'SELECT t1.*, t2.TransactionTitle, t4.BankName, CONCAT_WS(" ", t5.FirstName, t5.LastName) UserName, t10.BranchName, 
                  (SELECT BankBranchName From ' . BankBranchModel::getModelTableName() . ' WHERE BankBranchId = (SELECT BankBranchId FROM ' . BankAccountModel::getModelTableName() .  ' WHERE AccountId = t1.AccountId)) BankBranchName
                  FROM ' . self::$tableName . ' t1
                  INNER JOIN ' . TransactionModel::getModelTableName() . ' t2 ON t2.TransactionId = t1.TransactionId
                  INNER JOIN ' . BankAccountModel::getModelTableName() . ' t4 ON t4.AccountId = t1.AccountId
                  INNER JOIN ' . UserProfileModel::getModelTableName() . ' t5 ON t5.UserId = t1.UserId
                  INNER JOIN ' . BranchModel::getModelTableName() . ' t10 ON t10.BranchId = t1.BranchId
                  WHERE (t1.Status = ' . self::CHEQUE_ORDER_PRINTED . ' OR t1.Status = ' . self::CHEQUE_ORDER_READY_BALANCE_COVERED . ' OR t1.Status = ' . self::CHEQUE_ORDER_READY_BALANCE_NOT_COVERED . ')'

        );
    }

    public static function getHandedOverToClientCheques()
    {
        return self::get(
            'SELECT t1.*, t2.TransactionTitle, t4.BankName, CONCAT_WS(" ", t5.FirstName, t5.LastName) UserName, t10.BranchName, 
                  (SELECT BankBranchName From ' . BankBranchModel::getModelTableName() . ' WHERE BankBranchId = (SELECT BankBranchId FROM ' . BankAccountModel::getModelTableName() .  ' WHERE AccountId = t1.AccountId)) BankBranchName
                  FROM ' . self::$tableName . ' t1
                  INNER JOIN ' . TransactionModel::getModelTableName() . ' t2 ON t2.TransactionId = t1.TransactionId
                  INNER JOIN ' . BankAccountModel::getModelTableName() . ' t4 ON t4.AccountId = t1.AccountId
                  INNER JOIN ' . UserProfileModel::getModelTableName() . ' t5 ON t5.UserId = t1.UserId
                  INNER JOIN ' . BranchModel::getModelTableName() . ' t10 ON t10.BranchId = t1.BranchId
                  WHERE t1.Status = ' . self::CHEQUE_ORDER_HANDED_TO_CLIENT

        );
    }

    public static function getClearedCheques()
    {
        return self::get(
            'SELECT t1.*, t2.TransactionTitle, t4.BankName, CONCAT_WS(" ", t5.FirstName, t5.LastName) UserName, t10.BranchName, 
                  (SELECT BankBranchName From ' . BankBranchModel::getModelTableName() . ' WHERE BankBranchId = (SELECT BankBranchId FROM ' . BankAccountModel::getModelTableName() .  ' WHERE AccountId = t1.AccountId)) BankBranchName
                  FROM ' . self::$tableName . ' t1
                  INNER JOIN ' . TransactionModel::getModelTableName() . ' t2 ON t2.TransactionId = t1.TransactionId
                  INNER JOIN ' . BankAccountModel::getModelTableName() . ' t4 ON t4.AccountId = t1.AccountId
                  INNER JOIN ' . UserProfileModel::getModelTableName() . ' t5 ON t5.UserId = t1.UserId
                  INNER JOIN ' . BranchModel::getModelTableName() . ' t10 ON t10.BranchId = t1.BranchId
                  WHERE t1.Status = ' . self::CHEQUE_ORDER_CLEARED

        );
    }

    public static function getObsoletedCheques()
    {
        return self::get(
            'SELECT t1.*, t2.TransactionTitle, t4.BankName, CONCAT_WS(" ", t5.FirstName, t5.LastName) UserName, t10.BranchName,
                  (SELECT BankBranchName From ' . BankBranchModel::getModelTableName() . ' WHERE BankBranchId = (SELECT BankBranchId FROM ' . BankAccountModel::getModelTableName() .  ' WHERE AccountId = t1.AccountId)) BankBranchName
                  FROM ' . self::$tableName . ' t1
                  INNER JOIN ' . TransactionModel::getModelTableName() . ' t2 ON t2.TransactionId = t1.TransactionId
                  INNER JOIN ' . BankAccountModel::getModelTableName() . ' t4 ON t4.AccountId = t1.AccountId
                  INNER JOIN ' . UserProfileModel::getModelTableName() . ' t5 ON t5.UserId = t1.UserId
                  INNER JOIN ' . BranchModel::getModelTableName() . ' t10 ON t10.BranchId = t1.BranchId
                  WHERE t1.Status = ' . self::CHEQUE_ORDER_READY_BALANCE_COVERED . ' AND (MONTH(CURRENT_DATE) - MONTH(t1.CreatedJ)) >= 6'

        );
    }

    public static function getNotCoveredCheques()
    {
        $cheques = self::get(
            'SELECT * FROM ' . self::$tableName . ' WHERE Status = ' . self::CHEQUE_ORDER_READY_BALANCE_NOT_COVERED
        );
        return false !== $cheques ? count($cheques) : 0;
    }

    public static function getMonthlyIssuedChequesPerBranch($branchId, $month)
    {
        return self::get('
              SELECT COUNT(*) as c FROM ' . self::$tableName . ' WHERE month(CreatedJ) = ' . $month . ' AND year(CreatedJ) = ' . date('Y') . ' AND BranchId = ' . $branchId . ' AND Status = ' . self::CHEQUE_ORDER_HANDED_TO_CLIENT
        )->current()->c;
    }
}