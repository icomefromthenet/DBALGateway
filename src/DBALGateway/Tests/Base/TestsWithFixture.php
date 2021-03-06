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
    
    use TestCaseTrait;

    
    //  ----------------------------------------------------------------------------
    
    /**
      *  @var PDO  only instantiate pdo once for test clean-up/fixture load
      *  @access private
      */ 
    static private $pdo = null;

    /**
      *  @var  \Doctrine\DBAL\Connection
      *  @access private
      */
    static private $doctrine_connection;
    
    /**
      *  @var PHPUnit_Extensions_Database_DB_IDatabaseConnection only instantiate once per test
      *  @access private
      */
    private $conn = null;
    
    
    final public function getConnection()
    {
        if ($this->conn === null) {
            if (self::$pdo == null) {
                self::$pdo = new PDO($GLOBALS['DB_DSN'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWD'] );
            }
            $this->conn = $this->createDefaultDBConnection(self::$pdo, $GLOBALS['DB_DBNAME']);
        }

        return $this->conn;
    }

    
    protected function getSetUpOperation()
    {
        return new Composite([
            new DBOperationSetEnv('foreign_key_checks',0),
            Factory::CLEAN_INSERT(),
            new DBOperationSetEnv('foreign_key_checks',1),
        ]);
    }
    
    
    public function getDataSet()
    {
        $aData  =  $this->createXMLDataSet(__DIR__ . DIRECTORY_SEPARATOR .'fixture.xml');
        
        return $aData;
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