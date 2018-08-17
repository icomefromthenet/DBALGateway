<?php
namespace DBALGateway\Tests;

use DBALGateway\Tests\Base\TestsWithFixture;
use DBALGateway\Table\ContainerFactoryInterface;
use DBALGateway\Table\AbstractTable;
use DBALGateway\Tests\Base\Mock\MockUserTableGateway;
use DateTime;

class ContainerFactoryTest extends TestsWithFixture
{
    
    public function testImplementsFactoryInterface()
    {
        $mock_event = $this->createMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $mock = new MockUserTableGateway('users',$this->getDoctrineConnection(),$mock_event,$this->getTableMetaData());
        
        $this->assertInstanceOf('DBALGateway\Table\ContainerFactoryInterface',$mock);
        
    }
    
    
    public function testMethodRetunsInsertContainer()
    {
        $mock_event = $this->createMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $mock = new MockUserTableGateway('users',$this->getDoctrineConnection(),$mock_event,$this->getTableMetaData());
        
        $this->assertInstanceOf('DBALGateway\Container\InsertContainer',$mock->insertQuery());
    }
    
    public function testMethodRetunsUpdateContainer()
    {
        $mock_event = $this->createMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $mock = new MockUserTableGateway('users',$this->getDoctrineConnection(),$mock_event,$this->getTableMetaData());
        
        $this->assertInstanceOf('DBALGateway\Container\UpdateContainer',$mock->updateQuery());
    }
    
    public function testMethodRetunsDeleteContainer()
    {
        $mock_event = $this->createMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $mock = new MockUserTableGateway('users',$this->getDoctrineConnection(),$mock_event,$this->getTableMetaData());
        
        $this->assertInstanceOf('DBALGateway\Container\DeleteContainer',$mock->deleteQuery());
    }
    
    public function testMethodRetunsSelectContainer()
    {
        $mock_event = $this->createMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $mock = new MockUserTableGateway('users',$this->getDoctrineConnection(),$mock_event,$this->getTableMetaData());
        
        $this->assertInstanceOf('DBALGateway\Container\SelectContainer',$mock->selectQuery());
    }
    
    
    public function testAbstractContainerProperties()
    {
        $mock_event = $this->createMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $mock_table = new MockUserTableGateway('users',$this->getDoctrineConnection(),$mock_event,$this->getTableMetaData());
        $mock_query = $this->createMock('DBALGateway\Query\AbstractQuery',array(),array($this->getDoctrineConnection(),$mock_table));
        
        $mock_container = $this->getMockBuilder('DBALGateway\Container\AbstractContainer')
         ->setConstructorArgs([$mock_table,$mock_query])
         ->setMethods(null)
         ->getMock();
        
        $this->assertEquals($mock_query,$mock_container->getQuery());
        $this->assertEquals($mock_container,$mock_container->start());
        
    }
    
    
    public function testInsertContainerProperties()
    {
        $mock_event = $this->createMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $mock_table = new MockUserTableGateway('users',$this->getDoctrineConnection(),$mock_event,$this->getTableMetaData());
        
        $insert_container = $mock_table->insertQuery();
        
        $this->assertEquals(null,$insert_container->getQuery());
        $this->assertEquals($insert_container,$insert_container->start());
        $this->assertEquals($mock_table,$insert_container->end()); 
    }
    
    /**
      *  @expectedException DBALGateway\Exception
      *  @expectedExceptionMessage column name bad_column not found under table users unable to add to insert statement
      */
    public function testInsertContainerExceptionAtMissingColumn()
    {
        $mock_event = $this->createMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $mock_table = new MockUserTableGateway('users',$this->getDoctrineConnection(),$mock_event,$this->getTableMetaData());
        
        $insert_container = $mock_table->insertQuery();
        $insert_container->addColumn('bad_column',null);
    }
    
    public function testInsertContainerAddColumn()
    {
        $mock_event = $this->createMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $mock_table = new MockUserTableGateway('users',$this->getDoctrineConnection(),$mock_event,$this->getTableMetaData());
        
        $username = 'myuser';
        $new_date = new DateTime();
        $fname    = 'myfname';
        $lname    = 'mylname';
        
        $insert_container = $mock_table->insertQuery();
        $insert_container->addColumn('username',$username);
        $insert_container->addColumn('dte_created',$new_date);
        $insert_container->addColumn('first_name',$fname);
        $insert_container->addColumn('last_name',$lname);
        $insert_container->addColumn('dte_updated',$new_date);
        
        
        $this->assertEquals(array(
                                  'username'    => $username,
                                  'dte_created' => $new_date,
                                  'first_name'  => $fname,
                                  'last_name'   => $lname,
                                  'dte_updated' => $new_date
                                  ),$insert_container->getColumns());
        
        $meta_data = $this->getTableMetaData();
        
        $this->assertEquals(array(
                                  0 => $meta_data->getColumn('username')->getType(),
                                  1 => $meta_data->getColumn('dte_created')->getType(),
                                  2 => $meta_data->getColumn('first_name')->getType(),
                                  3 => $meta_data->getColumn('last_name')->getType(),
                                  4 => $meta_data->getColumn('dte_updated')->getType()
                                  ),$insert_container->getTypeInfo());
        
    }
    
    
    public function testUpdateContainerProperties()
    {
        $mock_event = $this->createMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $mock_table = new MockUserTableGateway('users',$this->getDoctrineConnection(),$mock_event,$this->getTableMetaData());
        
        $update_container = $mock_table->updateQuery();
        
        $this->assertInstanceOf('DBALGateway\Tests\Base\Mock\MockUserQuery',$update_container->getQuery());
        $this->assertEquals($update_container,$update_container->start());
        
    }
    
    
    /**
      *  @expectedException DBALGateway\Exception
      *  @expectedExceptionMessage column name bad_column not found under table users unable to add to update statement
      */
    public function testUpdateContainerExceptionAtMissingColumn()
    {
        $mock_event = $this->createMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $mock_table = new MockUserTableGateway('users',$this->getDoctrineConnection(),$mock_event,$this->getTableMetaData());
        
        $update_container = $mock_table->updateQuery()->start()->addColumn('bad_column',null);
    }
    
    
    public function testUpdateContainerAddColumn()
    {
        $mock_event = $this->createMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $mock_table = new MockUserTableGateway('users',$this->getDoctrineConnection(),$mock_event,$this->getTableMetaData());
        
        $username = 'myuser';
        $new_date = new DateTime();
        $fname    = 'myfname';
        $lname    = 'mylname';
        
        $mock_table->setTableQueryAlias('a');     
        
        $query = $mock_table->updateQuery()
            ->start()
                ->addColumn('username',$username)
                ->addColumn('dte_created',$new_date)
                ->addColumn('first_name',$fname)
                ->addColumn('last_name',$lname)
                ->addColumn('dte_updated',$new_date)
            ->where(); 
       
        # test if alias was injected in AbstractQuery Constructor
        $this->assertEquals('',$query ->getDefaultAlias());
       
        $this->assertEquals('UPDATE users SET username = :username, dte_created = :dte_created, first_name = :first_name, last_name = :last_name, dte_updated = :dte_updated', $query->getSql());
        
    }
    
    
    public function testDeleteContainerProperties()
    {
        $mock_event = $this->createMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $mock_table = new MockUserTableGateway('users',$this->getDoctrineConnection(),$mock_event,$this->getTableMetaData());
        
        $delete_container = $mock_table->deleteQuery();
        
        $this->assertInstanceOf('DBALGateway\Tests\Base\Mock\MockUserQuery',$delete_container->start());
        
    }
    
    
    public function testDeleteContainerQuery()
    {
        
        $mock_event = $this->createMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $mock_table = new MockUserTableGateway('users',$this->getDoctrineConnection(),$mock_event,$this->getTableMetaData());
        
        $mock_table->setTableQueryAlias('a');     
        
        $delete_query = $mock_table->deleteQuery()
            ->start()
                ->filterByUser(1);
            
        
        # test if alias was injected in AbstractQuery Constructor
        $this->assertEquals('',$delete_query->getDefaultAlias());
            
        $this->assertEquals('DELETE FROM users WHERE id = :id',$delete_query->getSql());
        
    }
    
    
    public function testSelectQueryProperties()
    {
        $mock_event = $this->createMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $mock_table = new MockUserTableGateway('users',$this->getDoctrineConnection(),$mock_event,$this->getTableMetaData());
        
        $select_container = $mock_table->selectQuery();
        
        $this->assertInstanceOf('DBALGateway\Tests\Base\Mock\MockUserQuery',$select_container->start());
    }
    
    
    public function testSelectQuery()
    {
        $mock_event = $this->createMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $mock_table = new MockUserTableGateway('users',$this->getDoctrineConnection(),$mock_event,$this->getTableMetaData());
        
        $select_query = $mock_table->selectQuery()
            ->start()
                ->filterByUser(1);
                
        $this->assertEquals('SELECT id, username, first_name, last_name, dte_created, dte_updated FROM users  WHERE id = :id',$select_query->getSql());
        
    }
    
    public function testSelectQueryWithAlias()
    {
        $mock_event = $this->createMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $mock_table = new MockUserTableGateway('users',$this->getDoctrineConnection(),$mock_event,$this->getTableMetaData());
        
        $mock_table->setTableQueryAlias('a');
        
        $select_query = $mock_table->selectQuery()
            ->start()
                ->filterByUser(1);
        
        # test if alias was injected in AbstractQuery Constructor
        $this->assertEquals('a',$select_query->getDefaultAlias());
                
        $this->assertEquals('SELECT a.id AS a_id, a.username AS a_username, a.first_name AS a_first_name, a.last_name AS a_last_name, a.dte_created AS a_dte_created, a.dte_updated AS a_dte_updated FROM users a WHERE id = :id',$select_query->getSql());
        
    }
    
    
    public function testSelectQueryExtraHelpers()
    {
        $mock_event = $this->createMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $mock_table = new MockUserTableGateway('users',$this->getDoctrineConnection(),$mock_event,$this->getTableMetaData());
        
        $mock_table->setTableQueryAlias('a');
        
        $oSelectContainer = $mock_table->selectQuery();
        
        $this->assertEquals('alias',$oSelectContainer->extractAliasField('a','a.alias'));
        $this->assertEquals('alias',$oSelectContainer->extractAliasField('','alias'));
        $this->assertEquals('alias',$oSelectContainer->extractAliasField(null,'alias'));
           
        $this->assertEquals('a.alias',$oSelectContainer->convertToAliasField('a','alias'));   
        $this->assertEquals('alias',$oSelectContainer->convertToAliasField('','alias'));   
        
        $this->assertEquals('a',$oSelectContainer->getQueryAlias());   
    }
}
/* End of Class */