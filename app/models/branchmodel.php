<?php
namespace PHPMVC\Models;

class BranchModel extends AbstractModel
{

    public $BranchId;
    public $BranchName;
    public $Color;

    public static $tableName = 'app_branches';
    
    protected static $tableSchema = array(
        'BranchName'        => self::DATA_TYPE_STR,
        'Color'             => self::DATA_TYPE_STR
    );

    protected static $primaryKey = 'BranchId';
}