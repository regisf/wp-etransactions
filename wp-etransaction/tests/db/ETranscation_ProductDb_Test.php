<?php

require(__DIR__ . '/../../admin/db/ETransactions_ProductsDB.php');

use PHPUnit\Framework\TestCase;

class ETransaction_ProductDb_Test extends TestCase 
{
    public function test_get_instance() 
    {
        $expected = ETransactions_ProductDB::get_instance();
        $result = ETransactions_ProductDB::get_instance();

        $this->assertEquals($result, $expected);
    }
}
