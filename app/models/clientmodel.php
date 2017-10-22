<?php
namespace PHPMVC\Models;

class ClientModel extends AbstractModel
{

    public $id;
    public $name;
    public $id_type;
    public $id_number;
    public $mobile;
    public $phone;
    public $fax;
    public $email;
    public $pobox;
    public $city;
    public $zip_code;
    public $created;
    
    public static $tableName = 'app_clients';
    
    protected static $tableSchema = array(
        'id'                    => self::DATA_TYPE_INT,
        'name'                  => self::DATA_TYPE_STR,
        'id_type'               => self::DATA_TYPE_INT,
        'id_number'             => self::DATA_TYPE_STR,
        'mobile'                => self::DATA_TYPE_STR,
        'phone'                 => self::DATA_TYPE_STR,
        'fax'                   => self::DATA_TYPE_STR,
        'email'                 => self::DATA_TYPE_STR,
        'pobox'                 => self::DATA_TYPE_STR,
        'city'                  => self::DATA_TYPE_STR,
        'zip_code'              => self::DATA_TYPE_STR,
        'created'               => self::DATA_TYPE_DATE
    );

    protected static $primaryKey = 'id';

    public static function getAll()
    {
        return parent::get('
            SELECT t1.*, (SELECT COUNT(*) FROM ' . TransactionModel::getModelTableName() . ' t2 WHERE t2.ClientId = t1.id) transactionsCount FROM ' . self::getModelTableName() . ' t1 
        ');
    }
}