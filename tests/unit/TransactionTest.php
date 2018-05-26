<?php
require_once realpath(dirname(__FILE__)) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php';

use PHPUnit\Framework\TestCase;
use PHPMVC\Models\TransactionModel;

class TransactionTest extends TestCase
{
    public function setUp()
    {
        $this->transaction = new TransactionModel();
        $this->transaction->TransactionTitle = 'معاملة المدينة المنورة';
        $this->transaction->TransactionTypeId = 1;
        $this->transaction->ClientId = 1;
        $this->transaction->UserId = 1;
        $this->transaction->BranchId = 1;
        $this->transaction->TransactionSummary = 'ملخص المعاملة';
        $this->transaction->Created = 20180212;
        $this->transaction->UpdatedBy = 1;
        $this->transaction->Audited = 0;
    }

    public function test_that_getAll_returns_an_array_iterator_object()
    {
        $this->assertInstanceOf('ArrayIterator', TransactionModel::getAll());
    }
}