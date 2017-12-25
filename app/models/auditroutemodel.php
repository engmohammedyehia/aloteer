<?php
namespace PHPMVC\Models;

class AuditRouteModel extends AbstractModel
{

    public $RouteId;
    public $BranchId;
    public $UserId;
    public $Enabled = 1;

    public static $tableName = 'app_audit_routes';
    
    protected static $tableSchema = array(
        'BranchId'       => self::DATA_TYPE_INT,
        'UserId'         => self::DATA_TYPE_INT,
        'Enabled'        => self::DATA_TYPE_BOOL

    );

    protected static $primaryKey = 'RouteId';

    public static function getAll()
    {
        return self::get(
            'SELECT t1.*, CONCAT_WS(" ", t2.FirstName, t2.LastName) EmpName, t3.BranchName Branch
                  FROM ' . self::$tableName . ' t1 INNER JOIN ' . UserProfileModel::getModelTableName(). ' t2 ON
                  t2.UserId = t1.UserId INNER JOIN ' . BranchModel::getModelTableName() . ' t3 ON
                  t3.BranchId = t1.BranchId'
        );
    }
}