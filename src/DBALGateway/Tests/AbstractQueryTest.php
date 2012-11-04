<?php
namespace DBALGateway\Tests;

use DBALGateway\Tests\Base\TestsWithFixture;
use DBALGateway\Table\ContainerFactoryInterface;
use DBALGateway\Table\AbstractTable;
use DBALGateway\Tests\Base\Mock\MockUserTableGateway;
use Doctrine\Common\Collections\ArrayCollection;
use DateTime;

class AbstractQueryTest extends TestsWithFixture
{
    
    public function testLimitAlias()
    {
        $mock_event = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $mock = new MockUserTableGateway('users',$this->getDoctrineConnection(),$mock_event,$this->getTableMetaData());
        
        $mock_query = $mock->newQueryBuilder();        
        $mock_query->select('u.id', 'p.id');
        $mock_query->from('users', 'u');
        $mock_query->limit(5);
        
        $this->assertEquals('SELECT u.id, p.id FROM users u LIMIT 5',$mock_query->getSql());
    }
    
    /**
      *  @expectedException DBALGateway\Exception
      *  @expectedExceptionMessage Query LIMIT must be and integer and greater than 0
      */
    public function testLimitBadParamException()
    {
        $mock_event = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $mock = new MockUserTableGateway('users',$this->getDoctrineConnection(),$mock_event,$this->getTableMetaData());
        
        $mock_query = $mock->newQueryBuilder(); 
        
        $mock_query->select('u.id', 'p.id');
        $mock_query->from('users', 'u');
        $mock_query->limit(-5);
        
        
    }
    
    
    public function testOffsetAlias()
    {
       $mock_event = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $mock = new MockUserTableGateway('users',$this->getDoctrineConnection(),$mock_event,$this->getTableMetaData());
        
        $mock_query = $mock->newQueryBuilder(); 
        
        $mock_query->select('u.id', 'p.id');
        $mock_query->from('users', 'u');
        $mock_query->offset(5);
        
        $this->assertEquals('SELECT u.id, p.id FROM users u OFFSET 5',$mock_query->getSql());
    }
    
    /**
      *  @expectedException DBALGateway\Exception
      *  @expectedExceptionMessage Query OFFSET must be and integer
      */
    public function testOffsetBadParamException()
    {
        $mock_event = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $mock = new MockUserTableGateway('users',$this->getDoctrineConnection(),$mock_event,$this->getTableMetaData());
        
        $mock_query = $mock->newQueryBuilder(); 
        
        $mock_query->select('u.id', 'p.id');
        $mock_query->from('users', 'u');
        $mock_query->offset('fff');
        
    }
    
}
/* End of Class */