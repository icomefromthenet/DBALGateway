<?php
namespace DBALGateway\Tests;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use DBALGateway\Tests\Base\TestsWithFixture;
use DBALGateway\Table\GatewayProxyCollection;
use DBALGateway\Tests\Base\Mock\MockUserTableGateway;


class ProxyCollectionTest extends TestsWithFixture
{
    
    public function testGatewayProxyCollection()
    {
        $oSchema = $this->getTestScheam();
        $oProxy  = new GatewayProxyCollection($oSchema);
        
        $mock_event = $this->createMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $mock_table = new MockUserTableGateway('users',$this->getDoctrineConnection(),$mock_event,$this->getTableMetaData());
        
        
        $this->assertEquals($oSchema,$oProxy->getSchema());
        
        $oProxy->addGateway('my_users',function() use($mock_table) {
                return $mock_table;
        
        });
        
        $this->assertTrue($oProxy->gatewayExistsAt('my_users'));
        $this->assertFalse($oProxy->gatewayExistsAt('users'));
        $this->assertEquals($mock_table,$oProxy->getGateway('my_users'));
        $this->assertEquals(1,$oProxy->getIterator()->count());
        
        
        
    }
    
    /**
     * @expectedException DBALGateway\Exception 
     * @expectedExceptionMessage The key my_users already exists
     */ 
    public function testFailesToAddGatewayAgain()
    {
        $oSchema = $this->getTestScheam();
        $oProxy  = new GatewayProxyCollection($oSchema);
        
        $mock_event = $this->createMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $mock_table = new MockUserTableGateway('users',$this->getDoctrineConnection(),$mock_event,$this->getTableMetaData());
    
        
        $this->assertEquals($oSchema,$oProxy->getSchema());
        
        $oProxy->addGateway('my_users',function() use($mock_table) {
                return $mock_table;
        
        });
        $oProxy->addGateway('my_users',function() use($mock_table) {
                return $mock_table;
        
        });
        
    }
    
    
    public function testAwareCollectionAbstract()
    {
        $mock_event = $this->createMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $oSchema = $this->getTestScheam();
        $oProxy  = new GatewayProxyCollection($oSchema);
        
        
       $mock_table = new MockUserTableGateway('users',$this->getDoctrineConnection(),$mock_event,$this->getTableMetaData());
      
       $mock_table->setGatewayCollection($oProxy);
       $this->assertEquals($oProxy,$mock_table->getGatewayCollection());
        
    }
    
   
}
/* End of File */