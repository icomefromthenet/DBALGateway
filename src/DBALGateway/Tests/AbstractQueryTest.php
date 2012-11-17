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
    
    public function testDeleteQueryWithOffset()
    {
        $mock_event = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $mock = new MockUserTableGateway('users',$this->getDoctrineConnection(),$mock_event,$this->getTableMetaData());
        
        $mock_query = $mock->newQueryBuilder(); 
        
        $mock_query->delete('users', 'u')->where('u.id = :user_id')->setParameter(':user_id', 1)->offset(100);
        
        $this->assertRegExp('/DELETE FROM users u WHERE u.id = :user_id OFFSET 100/',$mock_query->getSql());
    }
    
    
    public function testDeleteQueryWithLimit()
    {
        $mock_event = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $mock = new MockUserTableGateway('users',$this->getDoctrineConnection(),$mock_event,$this->getTableMetaData());
        
        $mock_query = $mock->newQueryBuilder(); 
        
        $mock_query->delete('users', 'u')->where('u.id = :user_id')->setParameter(':user_id', 1)->limit(100);
        
        $this->assertRegExp('/DELETE FROM users u WHERE u.id = :user_id LIMIT 100/',$mock_query->getSql());
    }
    
    
    public function testDeleteQueryWithLimitOffset()
    {
        $mock_event = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $mock = new MockUserTableGateway('users',$this->getDoctrineConnection(),$mock_event,$this->getTableMetaData());
        
        $mock_query = $mock->newQueryBuilder(); 
        
        $mock_query->delete('users', 'u')->where('u.id = :user_id')->setParameter(':user_id', 1)->limit(100)->offset(6);
        
        $this->assertRegExp('/DELETE FROM users u WHERE u.id = :user_id LIMIT 100 OFFSET 6/',$mock_query->getSql());
    }
    
     public function testUpdateQueryWithOffset()
    {
        $mock_event = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $mock = new MockUserTableGateway('users',$this->getDoctrineConnection(),$mock_event,$this->getTableMetaData());
        
        $mock_query = $mock->newQueryBuilder(); 
        
        $mock_query->update('users', 'u')->set('u.password', md5('password'))->offset(100);
        
        $this->assertRegExp('/UPDATE users u SET u.password = 5f4dcc3b5aa765d61d8327deb882cf99 OFFSET 100/',$mock_query->getSql());
    }
    
    
    public function testUpdateQueryWithLimit()
    {
        $mock_event = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $mock = new MockUserTableGateway('users',$this->getDoctrineConnection(),$mock_event,$this->getTableMetaData());
        
        $mock_query = $mock->newQueryBuilder(); 
        
       $mock_query->update('users', 'u')->set('u.password', md5('password'))->limit(100);
        
        $this->assertRegExp('/UPDATE users u SET u.password = 5f4dcc3b5aa765d61d8327deb882cf99 LIMIT 100/',$mock_query->getSql());
    }
    
    
    public function testUpdateQueryWithLimitOffset()
    {
        $mock_event = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $mock = new MockUserTableGateway('users',$this->getDoctrineConnection(),$mock_event,$this->getTableMetaData());
        
        $mock_query = $mock->newQueryBuilder(); 
        
       $mock_query->update('users', 'u')->set('u.password', md5('password'))->offset(100)->limit(6);
        
        $this->assertRegExp('/UPDATE users u SET u.password = 5f4dcc3b5aa765d61d8327deb882cf99 LIMIT 6 OFFSET 100/',$mock_query->getSql());
    }
    
    
    public function testUpdateOrderBy()
    {
        $mock_event = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $mock = new MockUserTableGateway('users',$this->getDoctrineConnection(),$mock_event,$this->getTableMetaData());
        
        $mock_query = $mock->newQueryBuilder(); 
        
        $mock_query->update('users', 'u')->set('u.password', md5('password'))->orderBy('u.ome');
        
        $this->assertRegExp('/UPDATE users u SET u.password = 5f4dcc3b5aa765d61d8327deb882cf99 ORDER BY u.ome ASC/',$mock_query->getSql());
    }
    
     public function testDeleteOrderBy()
    {
        $mock_event = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $mock = new MockUserTableGateway('users',$this->getDoctrineConnection(),$mock_event,$this->getTableMetaData());
        
        $mock_query = $mock->newQueryBuilder(); 
        
        $mock_query->delete('users', 'u')->where('u.id = :user_id')->setParameter(':user_id', 1)->orderBy('u.ome');
        
        $this->assertRegExp('/DELETE FROM users u WHERE u.id = :user_id ORDER BY u.ome ASC/',$mock_query->getSql());
    }
    
}
/* End of Class */