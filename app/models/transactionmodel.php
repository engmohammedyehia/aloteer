<?php
namespace PHPMVC\Models;

class TransactionModel extends AbstractModel
{

    public $TransactionId;
    public $TransactionTitle;
    public $TransactionTypeId;
    public $ClientId;
    public $UserId;
    public $BranchId;
    public $ProjectId;
    public $TransactionSummary;
    public $Created;
    public $UpdatedBy;
    public $Audited = 0;
    public $Payment;

    public static $tableName = 'app_transactions';
    
    protected static $tableSchema = array(
        'TransactionTitle'          => self::DATA_TYPE_STR,
        'TransactionTypeId'         => self::DATA_TYPE_INT,
        'ClientId'                  => self::DATA_TYPE_INT,
        'UserId'                    => self::DATA_TYPE_INT,
        'UpdatedBy'                 => self::DATA_TYPE_INT,
        'BranchId'                  => self::DATA_TYPE_INT,
        'ProjectId'                 => self::DATA_TYPE_INT,
        'TransactionSummary'        => self::DATA_TYPE_STR,
        'Created'                   => self::DATA_TYPE_DATE,
        'Audited'                   => self::DATA_TYPE_BOOL,
        'Payment'                   => self::DATA_TYPE_INT
    );

    protected static $primaryKey = 'TransactionId';

    public static function getAll($branch = false)
    {
        return self::get(
            'SELECT t1.*, 
                  t2.TransactionType thetype, 
                  t3.name ClientName, 
                  CONCAT_WS(" ", t4.FirstName, t4.LastName) UserName, 
                  t5.BranchName, 
                  CONCAT_WS(" ", t6.FirstName, t6.LastName) UpdatingUser,
                  (SELECT st1.StatusType FROM ' . TransactionStatusModel::getModelTableName() . ' st1 WHERE st1.TransactionId = t1.TransactionId ORDER BY StatusId DESC LIMIT 1) StatusType,
                  (SELECT st1.Note FROM ' . TransactionStatusModel::getModelTableName() . ' st1 WHERE st1.TransactionId = t1.TransactionId ORDER BY StatusId DESC LIMIT 1) StatusNote,
                  (SELECT st1.StatusType FROM ' . TransactionStatusModel::getModelTableName() . ' st1 WHERE st1.StatusType = ' . TransactionStatusModel::STATUS_TRANSACTION_CEO_REVIEW . ' LIMIT 1) CEOApproved,
                  (SELECT app_cheques.Status FROM app_cheques WHERE app_cheques.TransactionId = t1.TransactionId ORDER BY app_cheques.ChequeId DESC LIMIT 1) ChequePrinted,
                  (SELECT CONCAT_WS(" ", FirstName, LastName) FROM ' . UserProfileModel::getModelTableName() . ' st2 WHERE st2.UserId = (SELECT st3.UserId FROM ' . TransactionStatusModel::getModelTableName() . ' st3 WHERE st3.TransactionId = t1.TransactionId ORDER BY StatusId DESC LIMIT 1)) StatusUser,
                  (SELECT StatusType FROM ' . TransactionStatusModel::getModelTableName() .  ' WHERE TransactionId = t1.TransactionId ORDER BY StatusId DESC LIMIT 1),
                  (SELECT 1 FROM ' . ChequeModel::getModelTableName() .  ' WHERE TransactionId = t1.TransactionId LIMIT 1) ChequeOrdered,
                  (SELECT 1 FROM ' . SignatureRequestModel::getModelTableName() .  ' WHERE TransactionId = t1.TransactionId LIMIT 1) SignatureRequest,
                  (SELECT ChequeNumber FROM ' . ChequeModel::getModelTableName() .  ' WHERE TransactionId = t1.TransactionId LIMIT 1) ChequeNumber
                  FROM ' . self::$tableName . ' t1' .
            ' INNER JOIN ' . TransactionTypeModel::getModelTableName() . ' t2 ON t2.TransactionTypeId = t1.TransactionTypeId' .
            ' INNER JOIN ' . ClientModel::getModelTableName() . ' t3 ON t3.id = t1.ClientId' .
            ' INNER JOIN ' . UserProfileModel::getModelTableName() . ' t4 ON t4.UserId = t1.UserId' .
            ' INNER JOIN ' . BranchModel::getModelTableName() . ' t5 ON t5.BranchId = t1.BranchId' .
            ' LEFT JOIN ' . UserProfileModel::getModelTableName() . ' t6 ON t6.UserId = t1.UpdatedBy' .
            (($branch !== false) ? (' WHERE t1.BranchId = ' . $branch ) : '') .
            ' ORDER BY t1.Created DESC'
        );
    }

    public static function getAllForAuditors($branch)
    {
        return self::get(
            'SELECT t1.*, 
                  t2.TransactionType thetype, 
                  t3.name ClientName, 
                  CONCAT_WS(" ", t4.FirstName, t4.LastName) UserName, 
                  t5.BranchName, 
                  CONCAT_WS(" ", t6.FirstName, t6.LastName) UpdatingUser,
                  (SELECT st1.StatusType FROM ' . TransactionStatusModel::getModelTableName() . ' st1 WHERE st1.TransactionId = t1.TransactionId ORDER BY StatusId DESC LIMIT 1) StatusType,
                  (SELECT st1.Note FROM ' . TransactionStatusModel::getModelTableName() . ' st1 WHERE st1.TransactionId = t1.TransactionId ORDER BY StatusId DESC LIMIT 1) StatusNote,
                  (SELECT st1.StatusType FROM ' . TransactionStatusModel::getModelTableName() . ' st1 WHERE st1.StatusType = ' . TransactionStatusModel::STATUS_TRANSACTION_CEO_REVIEW . ' LIMIT 1) CEOApproved,
                  (SELECT app_cheques.Status FROM app_cheques WHERE app_cheques.TransactionId = t1.TransactionId ORDER BY app_cheques.ChequeId DESC LIMIT 1) ChequePrinted,
                  (SELECT CONCAT_WS(" ", FirstName, LastName) FROM ' . UserProfileModel::getModelTableName() . ' st2 WHERE st2.UserId = (SELECT st3.UserId FROM ' . TransactionStatusModel::getModelTableName() . ' st3 WHERE st3.TransactionId = t1.TransactionId ORDER BY StatusId DESC LIMIT 1)) StatusUser,
                  (SELECT StatusType FROM ' . TransactionStatusModel::getModelTableName() .  ' WHERE TransactionId = t1.TransactionId ORDER BY StatusId DESC LIMIT 1),
                  (SELECT 1 FROM ' . ChequeModel::getModelTableName() .  ' WHERE TransactionId = t1.TransactionId LIMIT 1) ChequeOrdered,
                  (SELECT 1 FROM ' . SignatureRequestModel::getModelTableName() .  ' WHERE TransactionId = t1.TransactionId LIMIT 1) SignatureRequest,
                  (SELECT ChequeNumber FROM ' . ChequeModel::getModelTableName() .  ' WHERE TransactionId = t1.TransactionId LIMIT 1) ChequeNumber
                  FROM ' . self::$tableName . ' t1' .
            ' INNER JOIN ' . TransactionTypeModel::getModelTableName() . ' t2 ON t2.TransactionTypeId = t1.TransactionTypeId' .
            ' INNER JOIN ' . ClientModel::getModelTableName() . ' t3 ON t3.id = t1.ClientId' .
            ' INNER JOIN ' . UserProfileModel::getModelTableName() . ' t4 ON t4.UserId = t1.UserId' .
            ' INNER JOIN ' . BranchModel::getModelTableName() . ' t5 ON t5.BranchId = t1.BranchId' .
            ' LEFT JOIN ' . UserProfileModel::getModelTableName() . ' t6 ON t6.UserId = t1.UpdatedBy' .
            ' WHERE t1.BranchId = ' . $branch .
            ' HAVING StatusType >= ' . TransactionStatusModel::STATUS_TRANSACTION_APPROVED_BY_MANAGER .
            ' ORDER BY t1.Created DESC'
        );
    }

    public static function getAllForAccountants($userId)
    {
        return self::get(
            'SELECT t1.*, 
                  t2.TransactionType thetype, 
                  t3.name ClientName, 
                  CONCAT_WS(" ", t4.FirstName, t4.LastName) UserName, 
                  t5.BranchName, 
                  CONCAT_WS(" ", t6.FirstName, t6.LastName) UpdatingUser,
                  (SELECT st1.StatusType FROM ' . TransactionStatusModel::getModelTableName() . ' st1 WHERE st1.TransactionId = t1.TransactionId ORDER BY StatusId DESC LIMIT 1) StatusType,
                  (SELECT st1.Note FROM ' . TransactionStatusModel::getModelTableName() . ' st1 WHERE st1.TransactionId = t1.TransactionId ORDER BY StatusId DESC LIMIT 1) StatusNote,
                  (SELECT st1.StatusType FROM ' . TransactionStatusModel::getModelTableName() . ' st1 WHERE st1.StatusType = ' . TransactionStatusModel::STATUS_TRANSACTION_CEO_REVIEW . ' LIMIT 1) CEOApproved,
                  (SELECT app_cheques.Status FROM app_cheques WHERE app_cheques.TransactionId = t1.TransactionId ORDER BY app_cheques.ChequeId DESC LIMIT 1) ChequePrinted,
                  (SELECT CONCAT_WS(" ", FirstName, LastName) FROM ' . UserProfileModel::getModelTableName() . ' st2 WHERE st2.UserId = (SELECT st3.UserId FROM ' . TransactionStatusModel::getModelTableName() . ' st3 WHERE st3.TransactionId = t1.TransactionId ORDER BY StatusId DESC LIMIT 1)) StatusUser,
                  (SELECT StatusType FROM ' . TransactionStatusModel::getModelTableName() .  ' WHERE TransactionId = t1.TransactionId ORDER BY StatusId DESC LIMIT 1),
                  (SELECT 1 FROM ' . ChequeModel::getModelTableName() .  ' WHERE TransactionId = t1.TransactionId LIMIT 1) ChequeOrdered,
                  (SELECT 1 FROM ' . SignatureRequestModel::getModelTableName() .  ' WHERE TransactionId = t1.TransactionId LIMIT 1) SignatureRequest,
                  (SELECT ChequeNumber FROM ' . ChequeModel::getModelTableName() .  ' WHERE TransactionId = t1.TransactionId LIMIT 1) ChequeNumber
                  FROM ' . self::$tableName . ' t1' .
            ' INNER JOIN ' . TransactionTypeModel::getModelTableName() . ' t2 ON t2.TransactionTypeId = t1.TransactionTypeId' .
            ' INNER JOIN ' . ClientModel::getModelTableName() . ' t3 ON t3.id = t1.ClientId' .
            ' INNER JOIN ' . UserProfileModel::getModelTableName() . ' t4 ON t4.UserId = t1.UserId' .
            ' INNER JOIN ' . BranchModel::getModelTableName() . ' t5 ON t5.BranchId = t1.BranchId' .
            ' LEFT JOIN ' . UserProfileModel::getModelTableName() . ' t6 ON t6.UserId = t1.UpdatedBy' .
            ' WHERE t1.UserId = ' . $userId .
            ' HAVING StatusType >= ' . TransactionStatusModel::STATUS_TRANSACTION_APPROVED_BY_MANAGER .
            ' ORDER BY t1.Created DESC'
        );
    }

    public static function getAllForReview($auditor = false, $branch = false)
    {
        return self::get(
            'SELECT t1.*, 
                  t2.TransactionType thetype, 
                  t3.name ClientName, 
                  CONCAT_WS(" ", t4.FirstName, t4.LastName) UserName, 
                  t5.BranchName, 
                  CONCAT_WS(" ", t6.FirstName, t6.LastName) UpdatingUser,
                  (SELECT st1.StatusType FROM ' . TransactionStatusModel::getModelTableName() . ' st1 WHERE st1.TransactionId = t1.TransactionId ORDER BY StatusId DESC LIMIT 1) StatusType,
                  (SELECT st1.Note FROM ' . TransactionStatusModel::getModelTableName() . ' st1 WHERE st1.TransactionId = t1.TransactionId ORDER BY StatusId DESC LIMIT 1) StatusNote,
                  (SELECT st1.StatusType FROM ' . TransactionStatusModel::getModelTableName() . ' st1 WHERE st1.StatusType = ' . TransactionStatusModel::STATUS_TRANSACTION_CEO_REVIEW . ' LIMIT 1) CEOApproved,
                  (SELECT app_cheques.Status FROM app_cheques WHERE app_cheques.TransactionId = t1.TransactionId ORDER BY app_cheques.ChequeId DESC LIMIT 1) ChequePrinted,
                  (SELECT CONCAT_WS(" ", FirstName, LastName) FROM ' . UserProfileModel::getModelTableName() . ' st2 WHERE st2.UserId = (SELECT st3.UserId FROM ' . TransactionStatusModel::getModelTableName() . ' st3 WHERE st3.TransactionId = t1.TransactionId ORDER BY StatusId DESC LIMIT 1)) StatusUser,
                  (SELECT StatusType FROM ' . TransactionStatusModel::getModelTableName() .  ' WHERE TransactionId = t1.TransactionId ORDER BY StatusId DESC LIMIT 1),
                  (SELECT AssignmentId FROM ' . AuditModel::getModelTableName() .  ' WHERE TransactionId = t1.TransactionId ORDER BY Created DESC LIMIT 1) AuditOrder,
                  (SELECT UserId FROM ' . AuditModel::getModelTableName() .  ' WHERE TransactionId = t1.TransactionId ORDER BY Created DESC LIMIT 1) AuditorId,
                  (SELECT BranchId FROM ' . TransactionModel::getModelTableName() .  ' WHERE TransactionId = t1.TransactionId) BranchId    
                  FROM ' . self::$tableName . ' t1' .
            ' INNER JOIN ' . TransactionTypeModel::getModelTableName() . ' t2 ON t2.TransactionTypeId = t1.TransactionTypeId' .
            ' INNER JOIN ' . ClientModel::getModelTableName() . ' t3 ON t3.id = t1.ClientId' .
            ' INNER JOIN ' . UserProfileModel::getModelTableName() . ' t4 ON t4.UserId = t1.UserId' .
            ' INNER JOIN ' . BranchModel::getModelTableName() . ' t5 ON t5.BranchId = t1.BranchId' .
            ' LEFT JOIN ' . UserProfileModel::getModelTableName() . ' t6 ON t6.UserId = t1.UpdatedBy' .
            ' HAVING AuditOrder IS NOT NULL AND t1.Audited = 0' .
            ($auditor !== false ? ' AND AuditorId = ' . $auditor : '') .
            ($branch !== false ? ' AND BranchId = ' . $branch : '')
        );
    }

    public static function getAllForReviewed($auditor = false, $branch = false)
    {
        return self::get(
            'SELECT t1.*, 
                  t2.TransactionType thetype, 
                  t3.name ClientName, 
                  CONCAT_WS(" ", t4.FirstName, t4.LastName) UserName, 
                  t5.BranchName, 
                  CONCAT_WS(" ", t6.FirstName, t6.LastName) UpdatingUser,
                  (SELECT st1.StatusType FROM ' . TransactionStatusModel::getModelTableName() . ' st1 WHERE st1.TransactionId = t1.TransactionId ORDER BY StatusId DESC LIMIT 1) StatusType,
                  (SELECT st1.Note FROM ' . TransactionStatusModel::getModelTableName() . ' st1 WHERE st1.TransactionId = t1.TransactionId ORDER BY StatusId DESC LIMIT 1) StatusNote,
                  (SELECT st1.StatusType FROM ' . TransactionStatusModel::getModelTableName() . ' st1 WHERE st1.StatusType = ' . TransactionStatusModel::STATUS_TRANSACTION_CEO_REVIEW . ' LIMIT 1) CEOApproved,
                  (SELECT app_cheques.Status FROM app_cheques WHERE app_cheques.TransactionId = t1.TransactionId ORDER BY app_cheques.ChequeId DESC LIMIT 1) ChequePrinted,
                  (SELECT CONCAT_WS(" ", FirstName, LastName) FROM ' . UserProfileModel::getModelTableName() . ' st2 WHERE st2.UserId = (SELECT st3.UserId FROM ' . TransactionStatusModel::getModelTableName() . ' st3 WHERE st3.TransactionId = t1.TransactionId ORDER BY StatusId DESC LIMIT 1)) StatusUser,
                  (SELECT StatusType FROM ' . TransactionStatusModel::getModelTableName() .  ' WHERE TransactionId = t1.TransactionId ORDER BY StatusId DESC LIMIT 1),
                  (SELECT AssignmentId FROM ' . AuditModel::getModelTableName() .  ' WHERE TransactionId = t1.TransactionId ORDER BY Created DESC LIMIT 1) AuditOrder,
                  (SELECT UserId FROM ' . AuditModel::getModelTableName() .  ' WHERE TransactionId = t1.TransactionId ORDER BY Created DESC LIMIT 1) AuditorId,
                  (SELECT BranchId FROM ' . TransactionModel::getModelTableName() .  ' WHERE TransactionId = t1.TransactionId) BranchId    
                  FROM ' . self::$tableName . ' t1' .
            ' INNER JOIN ' . TransactionTypeModel::getModelTableName() . ' t2 ON t2.TransactionTypeId = t1.TransactionTypeId' .
            ' INNER JOIN ' . ClientModel::getModelTableName() . ' t3 ON t3.id = t1.ClientId' .
            ' INNER JOIN ' . UserProfileModel::getModelTableName() . ' t4 ON t4.UserId = t1.UserId' .
            ' INNER JOIN ' . BranchModel::getModelTableName() . ' t5 ON t5.BranchId = t1.BranchId' .
            ' LEFT JOIN ' . UserProfileModel::getModelTableName() . ' t6 ON t6.UserId = t1.UpdatedBy' .
            ' HAVING AuditOrder IS NOT NULL AND t1.Audited = 1' .
            ($auditor !== false ? ' AND AuditorId = ' . $auditor : '') .
            ($branch !== false ? ' AND BranchId = ' . $branch : '')
        );
    }

    public static function getAllClosed($branch = false)
    {
        return self::get(
            'SELECT t1.*, 
                  t2.TransactionType thetype, 
                  t3.name ClientName, 
                  CONCAT_WS(" ", t4.FirstName, t4.LastName) UserName, 
                  t5.BranchName, 
                  CONCAT_WS(" ", t6.FirstName, t6.LastName) UpdatingUser,
                  (SELECT st1.StatusType FROM ' . TransactionStatusModel::getModelTableName() . ' st1 WHERE st1.TransactionId = t1.TransactionId ORDER BY StatusId DESC LIMIT 1) StatusType,
                  (SELECT st1.Note FROM ' . TransactionStatusModel::getModelTableName() . ' st1 WHERE st1.TransactionId = t1.TransactionId ORDER BY StatusId DESC LIMIT 1) StatusNote,
                  (SELECT st1.StatusType FROM ' . TransactionStatusModel::getModelTableName() . ' st1 WHERE st1.StatusType = ' . TransactionStatusModel::STATUS_TRANSACTION_CEO_REVIEW . ' LIMIT 1) CEOApproved,
                  (SELECT app_cheques.Status FROM app_cheques WHERE app_cheques.TransactionId = t1.TransactionId ORDER BY app_cheques.ChequeId DESC LIMIT 1) ChequePrinted,
                  (SELECT CONCAT_WS(" ", FirstName, LastName) FROM ' . UserProfileModel::getModelTableName() . ' st2 WHERE st2.UserId = (SELECT st3.UserId FROM ' . TransactionStatusModel::getModelTableName() . ' st3 WHERE st3.TransactionId = t1.TransactionId ORDER BY StatusId DESC LIMIT 1)) StatusUser,
                  (SELECT StatusType FROM ' . TransactionStatusModel::getModelTableName() .  ' WHERE TransactionId = t1.TransactionId ORDER BY StatusId DESC LIMIT 1),
                  (SELECT 1 FROM ' . ChequeModel::getModelTableName() .  ' WHERE TransactionId = t1.TransactionId LIMIT 1) ChequeOrdered,
                  (SELECT 1 FROM ' . SignatureRequestModel::getModelTableName() .  ' WHERE TransactionId = t1.TransactionId LIMIT 1) SignatureRequest,
                  (SELECT ChequeNumber FROM ' . ChequeModel::getModelTableName() .  ' WHERE TransactionId = t1.TransactionId LIMIT 1) ChequeNumber
                  FROM ' . self::$tableName . ' t1' .
            ' INNER JOIN ' . TransactionTypeModel::getModelTableName() . ' t2 ON t2.TransactionTypeId = t1.TransactionTypeId' .
            ' INNER JOIN ' . ClientModel::getModelTableName() . ' t3 ON t3.id = t1.ClientId' .
            ' INNER JOIN ' . UserProfileModel::getModelTableName() . ' t4 ON t4.UserId = t1.UserId' .
            ' INNER JOIN ' . BranchModel::getModelTableName() . ' t5 ON t5.BranchId = t1.BranchId' .
            ' LEFT JOIN ' . UserProfileModel::getModelTableName() . ' t6 ON t6.UserId = t1.UpdatedBy' .
            (($branch !== false) ? (' WHERE t1.BranchId = ' . $branch ) : '') .
            ' HAVING StatusType = 14 ORDER BY t1.Created DESC'
        );
    }

    public static function getTransactionsForMonths($branch = false)
    {
        $count = [];
        for ($i = 1; $i < 13; $i++) {
            $count[] = self::get('
              SELECT COUNT(*) as c FROM ' . self::$tableName . ' WHERE month(Created) = ' . $i . ' AND year(Created) = ' . date('Y') .
                ($branch === false ? '' : ' AND BranchId = ' . $branch)
            )->current()->c;
        }
        return $count;
    }
}