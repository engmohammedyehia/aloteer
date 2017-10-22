<?php
namespace PHPMVC\Models;

class TransactionFileModel extends AbstractModel
{

    public $FileId;
    public $TransactionId;
    public $FilePath;
    public $FileTitle;

    public static $tableName = 'app_transactions_files';
    
    protected static $tableSchema = array(
        'TransactionId'         => self::DATA_TYPE_INT,
        'FilePath'              => self::DATA_TYPE_STR,
        'FileTitle'             => self::DATA_TYPE_STR
    );

    protected static $primaryKey = 'FileId';
}