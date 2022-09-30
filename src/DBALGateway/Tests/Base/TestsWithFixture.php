<?php
namespace DBALGateway\Tests\Base;

use PDO;
use PHPUnit\DbUnit\Operation\Composite;
use PHPUnit\Framework\TestCase;
use PHPUnit\DbUnit\Operation\Factory;

use DBALGateway\Tests\Base\DBOperationSetEnv;
use DBALGateway\Metadata\Table;
use DBALGateway\Metadata\Schema;
use PHPUnit\DbUnit\TestCaseTrait;


class TestsWithFixture extends TestCase
{
    
    //  ----------------------------------------------------------------------------
  

    /**
      *  @var  \Doctrine\DBAL\Connection
      *  @access private
      */
    static private $doctrine_connection;
    


    public function setUp() : void
    {
        $sFilePath = realpath(__DIR__.'/../../../../sql/fixture.sql');

        
        //check if fixture file is found
        if(!is_file($sFilePath)) {
            throw new \RuntimeException('Unable to find sql fixture');
        }


        exec("mysql db < $sFilePath ");

    }

    
    /**
    * Gets a db connection to the test database
    *
    * @access public
    * @return \Doctrine\DBAL\Connection
    */
    public function getDoctrineConnection()
    {
        if(self::$doctrine_connection === null) {
        
            $config = new \Doctrine\DBAL\Configuration();
            
            $connectionParams = array(
                'dbname'   => $GLOBALS['DB_DBNAME'],
                'user'     => $GLOBALS['DB_USER'],
                'password' => $GLOBALS['DB_PASSWD'],
                'host'     => $GLOBALS['DB_HOST'],
                'driver'   => 'pdo_mysql',
            );
        
           self::$doctrine_connection = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);
        }
        
        return self::$doctrine_connection;
        
    }
    
    
    public function getTableMetaData()
    {
        return $this->getTestScheam()->getTable('users');
    }
    
    public function getTestScheam()
    {
        $oSchema = new Schema();
        
        $oTable = $oSchema->createTable('users');
        
        $oTable->addColumn('id',"integer", array("unsigned" => true));
        $oTable->addColumn('username', "string", array("length" => 32));
        $oTable->addColumn('first_name', "string", array("length" => 45));
        $oTable->addColumn('last_name',"string", array("length" => 45));
        $oTable->addColumn('dte_created','datetime');
        $oTable->addColumn('dte_updated','datetime');
        $oTable->setPrimaryKey(array("id"));
        
        return $oSchema;
        
    }
    
    protected $app;

  

    
}
/* End of File */