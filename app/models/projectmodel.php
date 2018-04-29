<?php
namespace PHPMVC\Models;

class ProjectModel extends AbstractModel
{

    public $ProjectId;
    public $ProjectName;

    public static $tableName = 'app_projects';
    
    protected static $tableSchema = array(
        'ProjectName'        => self::DATA_TYPE_STR
    );

    protected static $primaryKey = 'ProjectId';
}