<?php
namespace PHPMVC\Models;

class TransactionTypeModel extends AbstractModel
{

    public $TransactionTypeId;
    public $TransactionType;

    public static $tableName = 'app_transactions_types';
    
    protected static $tableSchema = array(
        'TransactionType'       => self::DATA_TYPE_STR
    );

    protected static $primaryKey = 'TransactionTypeId';
}