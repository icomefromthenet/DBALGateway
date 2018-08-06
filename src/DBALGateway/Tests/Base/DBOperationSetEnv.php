<?php
namespace DBALGateway\Tests\Base;

use PHPUnit\Extensions\Database\Operation\IDatabaseOperation as PHPUnit_Extensions_Database_Operation_IDatabaseOperation;
use PHPUnit\Extensions\Database\DB\IDatabaseConnection as PHPUnit_Extensions_Database_DB_IDatabaseConnection;
use PHPUnit\Extensions\Database\DataSet\IDataSet as PHPUnit_Extensions_Database_DataSet_IDataSet;


class DBOperationSetEnv implements PHPUnit_Extensions_Database_Operation_IDatabaseOperation
{
    private $env;
    private $val;

    public function __construct($env,$val)
    {
        $this->env = $env;
        $this->val = $val;
    }

    public function execute(PHPUnit_Extensions_Database_DB_IDatabaseConnection $connection, PHPUnit_Extensions_Database_DataSet_IDataSet $dataSet)
    {
        $connection->getConnection()->query('SET '.$this->env.'='.$this->val);
    }
}
/* End of File */