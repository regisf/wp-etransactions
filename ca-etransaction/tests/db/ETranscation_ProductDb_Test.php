<?php

require(__DIR__ . '/../../admin/db/productsdb.php');

use PHPUnit\Framework\TestCase;

class ETransaction_ProductDb_Test extends TestCase 
{
    public function test_get_instance() 
    {
        $expected = ProductDB::get_instance();
        $result = ProductDB::get_instance();

        $this->assertEquals($result, $expected);
    }
}
