<?php
namespace DBALGateway\Tests;

use DBALGateway\Tests\Base\TestsWithFixture;
use DBALGateway\Table\AbstractTable;
use DBALGateway\Tests\Base\Mock\MockUserTableGateway;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use DBALGateway\Table\TableEvents;

class TableGatewayTest extends TestsWithFixture
{
    
    public function testAbstractTableProperties()
    {
        $doctrine   = $this->getDoctrineConnection();
        $meta       = $this->getTableMetaData();
        $result_set = new ArrayCollection();
        
        $mock_builder = $this->getMockBuilder('DBALGateway\Builder\BuilderInterface')->getMock();
        $mock_event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $mock_table = $this->getMockBuilder('DBALGateway\Table\AbstractTable')
                            ->setMethods(['newQueryBuilder'])
                            ->setConstructorArgs(['users',$doctrine,$mock_event,$meta,$result_set,$mock_builder])
                            ->getMock();
        
        $this->assertEquals($mock_event,$mock_table->getEventDispatcher());
        $this->assertEquals($doctrine,$mock_table->getAdapater());
        $this->assertEquals($doctrine,$mock_table->getAdapter());
        $this->assertEquals($meta,$mock_table->getMetaData());
        $this->assertEquals($mock_builder,$mock_table->getEntityBuilder());
        $this->assertEquals($result_set,$mock_table->getResultSet());
        $this->assertEquals($mock_table->rowsAffected(),0);
        
    }
    
    
    public function testAbstractTableDefaultProperties()
    {
        $doctrine   = $this->getDoctrineConnection();
        $meta       = $this->getTableMetaData();
        
        $mock_event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $mock_table = $this->getMockBuilder('DBALGateway\Table\AbstractTable')
                           ->setMethods(['newQueryBuilder'])
                           ->setConstructorArgs(['users',$doctrine,$mock_event,$meta])
                           ->getMock();
        
        $this->assertEquals(null,$mock_table->getEntityBuilder());
        $this->assertInstanceOf('Doctrine\Common\Collections\Collection',$mock_table->getResultSet());
        
    }
    
    
    public function testConvertPHP()
    {
        $doctrine   = $this->getDoctrineConnection();
        $meta       = $this->getTableMetaData();
        
        $mock_event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $mock_table = $this->getMockBuilder('DBALGateway\Table\AbstractTable')
                           ->setMethods(['newQueryBuilder'])
                           ->setConstructorArgs(['users',$doctrine,$mock_event,$meta])
                           ->getMock();
        
        $meta->addVirtualColumn('mycolumn','integer',array("unsigned" => true));
        $meta->addVirtualColumn('b_column','boolean');
        
        $date = new DateTime();
        $date = $date->format('Y-m-d h:m:s');
        
        $data = array(
                      'id'          => '1',
                      'username'    => 'username',
                      'dte_created' => $date,
                      'dte_updated' => $date,
                      'mycolumn'    => '1',
                      'first_name'  => 'myfname',
                      'last_name'   => 'mylname',
                      'b_column'   => 0,
                      'no_column'  => 'none',
                      );
        
        $mock_table->convertToPhp($data);
        
        # converts normal columns
        $this->assertInternalType('integer',$data['id']);
        $this->assertEquals(1,$data['id']);
        
        $this->assertInternalType('string',$data['username']);
        $this->assertEquals('username',$data['username']);
        
        $this->assertInternalType('string',$data['first_name']);
        $this->assertEquals('myfname',$data['first_name']);
        
        $this->assertInternalType('string',$data['last_name']);
        $this->assertEquals('mylname',$data['last_name']);
        
        $this->assertInstanceOf('\DateTime',$data['dte_updated']);
        $this->assertInstanceOf('\DateTime',$data['dte_created']);
        
        # converts vcolumns        
        $this->assertInternalType('integer',$data['mycolumn']);
        $this->assertEquals(1,$data['mycolumn']);
        
        $this->assertInternalType('boolean',$data['b_column']);
        $this->assertEquals(false,$data['b_column']);
        
        # no conversion on extras
         $this->assertInternalType('string',$data['no_column']);
    }
    
    
    public function testQueryAliasProperty()
    {
        $doctrine   = $this->getDoctrineConnection();
        $meta       = $this->getTableMetaData();
        
        $mock_event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $mock_table = $this->getMockBuilder('DBALGateway\Table\AbstractTable')
                        ->setMethods(['newQueryBuilder'])
                        ->setConstructorArgs(['users',$doctrine,$mock_event,$meta])
                        ->getMock();
      
        $sAlias = 'a';
      
        $mock_table->setTableQueryAlias($sAlias);
        
        $this->assertEquals($sAlias,$mock_table->getTableQueryAlias());
        
        
    }
    
    
    public function testInsertQuery()
    {
        $mock_event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $mock_event->expects($this->exactly(4))
                   ->method('dispatch')
                   ->with($this->logicalOr(
                                           $this->equalTo(TableEvents::PRE_INITIALIZE),
                                           $this->equalTo(TableEvents::POST_INITIALIZE),
                                           $this->equalTo(TableEvents::PRE_INSERT),
                                           $this->equalTo(TableEvents::POST_INSERT)),$this->isInstanceOf('\DBALGateway\Table\TableEvent'));
        
        $mock_table = new MockUserTableGateway('users',$this->getDoctrineConnection(),$mock_event,$this->getTableMetaData());
        
        $username = 'myuser';
        $new_date = new DateTime();
        $fname    = 'myfname';
        $lname    = 'mylname';
        
        $success = $mock_table->insertQuery()
            ->start()
                ->addColumn('username',$username)
                ->addColumn('dte_created',$new_date)
                ->addColumn('first_name',$fname)
                ->addColumn('last_name',$lname)
                ->addColumn('dte_updated',$new_date)
            ->end()    
        ->insert();
        
        $this->assertTrue($success);
        $this->assertNotEmpty($mock_table->lastInsertId());
        $this->assertEquals($mock_table->rowsAffected(),1);
    }
    
    
    public function testDeleteQuery()
    {
        $mock_event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $mock_event->expects($this->exactly(4))
                   ->method('dispatch')
                   ->with($this->logicalOr(
                                           $this->equalTo(TableEvents::PRE_INITIALIZE),
                                           $this->equalTo(TableEvents::POST_INITIALIZE),
                                           $this->equalTo(TableEvents::PRE_DELETE),
                                           $this->equalTo(TableEvents::POST_DELETE)),$this->isInstanceOf('\DBALGateway\Table\TableEvent'));
        
        
        $mock_table = new MockUserTableGateway('users',$this->getDoctrineConnection(),$mock_event,$this->getTableMetaData());
      
      
       $aResults = $this->getDoctrineConnection()->fetchAll('select * from users');
       
       
        
        $success = $mock_table->deleteQuery()
            ->start()
                ->filterByUser(1)
            ->end()    
        ->delete();
        
        $this->assertTrue($success);
        $this->assertEquals($mock_table->rowsAffected(),1);
    }
    

    public function testUpdateQuery()
    {
        $mock_event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        
        $mock_event->expects($this->exactly(4))
                   ->method('dispatch')
                   ->with($this->logicalOr(
                                           $this->equalTo(TableEvents::PRE_INITIALIZE),
                                           $this->equalTo(TableEvents::POST_INITIALIZE),
                                           $this->equalTo(TableEvents::PRE_UPDATE),
                                           $this->equalTo(TableEvents::POST_UPDATE)),$this->isInstanceOf('\DBALGateway\Table\TableEvent'));
        
        $mock_table = new MockUserTableGateway('users',$this->getDoctrineConnection(),$mock_event,$this->getTableMetaData());
        
        $username = 'myuser';
        $new_date = new DateTime();
        $fname    = 'myfname';
        $lname    = 'mylname';
        
        $success = $mock_table->updateQuery()
            ->start()
                ->addColumn('username',$username)
                ->addColumn('dte_created',$new_date)
                ->addColumn('first_name',$fname)
                ->addColumn('last_name',$lname)
                ->addColumn('dte_updated',$new_date)
            ->where()
                ->filterByUser(1)
            ->end()    
        ->update();
        
        $this->assertTrue($success);
        $this->assertEquals($mock_table->rowsAffected(),1);
        
    }
    
    
    public function testSelectFind()
    {
        
        $mock_event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $mock_event->expects($this->exactly(4))
                   ->method('dispatch')
                   ->with($this->logicalOr(
                                           $this->equalTo(TableEvents::PRE_INITIALIZE),
                                           $this->equalTo(TableEvents::POST_INITIALIZE),
                                           $this->equalTo(TableEvents::PRE_SELECT),
                                           $this->equalTo(TableEvents::POST_SELECT)),$this->isInstanceOf('\DBALGateway\Table\TableEvent'));
        
        
        $mock_table = new MockUserTableGateway('users',$this->getDoctrineConnection(),$mock_event,$this->getTableMetaData());
        
        $result = $mock_table->selectQuery()
                ->start()
                    ->filterByUser(1)
                ->end()
            ->find();
        
        $this->assertEquals($result[0]['id'],1);
        $this->assertInternalType('integer',$result[0]['id']);
        $this->assertInstanceOf('\DateTime',$result[0]['dte_created']);
        $this->assertInstanceOf('\DateTime',$result[0]['dte_updated']);
        
    }
    
    public function testSelectFindNoObjects()
    {
        
        $mock_event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $mock_table = new MockUserTableGateway('users',$this->getDoctrineConnection(),$mock_event,$this->getTableMetaData());
        
        $result = $mock_table->selectQuery()
                ->start()
                    ->filterByUser(10000)
                ->end()
            ->find();
        
        $this->assertCount(0,$result);
    }
    
    
    public function testSelectFindWithBuilder()
    {
        $mock_event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $mock_builder = $this->getMockBuilder('DBALGateway\Builder\BuilderInterface')->getMock();
        $mock_table = new MockUserTableGateway('users',$this->getDoctrineConnection(),$mock_event,$this->getTableMetaData(),null,$mock_builder);
          
        
        $mock_builder->expects($this->once())
                     ->method('build')
                     ->will($this->returnArgument(0));
        
        $result = $mock_table->selectQuery()
                ->start()
                    ->filterByUser(1)
                ->end()
            ->find();
        
        $this->assertEquals($result[0]['id'],1);
        $this->assertInternalType('integer',$result[0]['id']);
        $this->assertInstanceOf('\DateTime',$result[0]['dte_created']);
        $this->assertInstanceOf('\DateTime',$result[0]['dte_updated']);
        
    }
    
    public function testSelectFindWithBuilderAnResultSet()
    {
        $mock_event      = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $mock_builder    = $this->getMockBuilder('DBALGateway\Builder\BuilderInterface')->getMock();
        $mock_collection = new \Doctrine\Common\Collections\ArrayCollection;
        $mock_table      = new MockUserTableGateway('users',$this->getDoctrineConnection(),$mock_event,$this->getTableMetaData(),$mock_collection,$mock_builder);
        
        $mock_builder->expects($this->once())
                     ->method('build')
                     ->will($this->returnArgument(0));
        
        $result = $mock_table->selectQuery()
                ->start()
                    ->filterByUser(1)
                ->end()
            ->find();
        
        $this->assertEquals($result[0]['id'],1);
        $this->assertInternalType('integer',$result[0]['id']);
        $this->assertInstanceOf('\DateTime',$result[0]['dte_created']);
        $this->assertInstanceOf('\DateTime',$result[0]['dte_updated']);
        
    }
    
    public function testSelectFindWithBuilderAnResultSetAnAlias()
    {
        $mock_event      = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $mock_builder    = $this->getMockBuilder('DBALGateway\Builder\BuilderInterface')->getMock();
        $mock_collection = new \Doctrine\Common\Collections\ArrayCollection;
        $mock_table      = new MockUserTableGateway('users',$this->getDoctrineConnection(),$mock_event,$this->getTableMetaData(),$mock_collection,$mock_builder);
        
        $mock_table->setTableQueryAlias('a');
        
        $mock_builder->expects($this->once())
                     ->method('build')
                     ->will($this->returnArgument(0));
        
        $result = $mock_table->selectQuery()
                ->start()
                    ->filterByUser(1)
                ->end()
            ->find();
        
        $this->assertEquals($result[0]['a_id'],1);
        $this->assertInternalType('integer',$result[0]['a_id']);
        $this->assertInstanceOf('\DateTime',$result[0]['a_dte_created']);
        $this->assertInstanceOf('\DateTime',$result[0]['a_dte_updated']);
        
    }
    
    public function testSelectFindOne()
    {
        
        $mock_event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        
        $mock_event->expects($this->exactly(4))
                   ->method('dispatch')
                   ->with($this->logicalOr(
                                           $this->equalTo(TableEvents::PRE_INITIALIZE),
                                           $this->equalTo(TableEvents::POST_INITIALIZE),
                                           $this->equalTo(TableEvents::PRE_SELECT),
                                           $this->equalTo(TableEvents::POST_SELECT)),$this->isInstanceOf('\DBALGateway\Table\TableEvent'));
                   
        $mock_table = new MockUserTableGateway('users',$this->getDoctrineConnection(),$mock_event,$this->getTableMetaData());
        
        $result = $mock_table->selectQuery()
            ->start()
                ->filterByUser(1)
            ->end()
            ->findOne();
        
        $this->assertEquals($result['id'],1);
        $this->assertInternalType('integer',$result['id']);
        $this->assertInstanceOf('\DateTime',$result['dte_created']);
        $this->assertInstanceOf('\DateTime',$result['dte_updated']);
        
    }
    
    
    public function testSelectFindOneWithBuilder()
    {
        
        $mock_event   = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $mock_builder = $this->getMockBuilder('DBALGateway\Builder\BuilderInterface')->getMock();
        
        $mock_builder->expects($this->once())
                     ->method('build')
                     ->will($this->returnArgument(0));
        
        $mock_table = new MockUserTableGateway('users',$this->getDoctrineConnection(),$mock_event,$this->getTableMetaData(),null,$mock_builder);
        
        $result = $mock_table->selectQuery()
            ->start()
                ->filterByUser(1)
            ->end()
            ->findOne();
            
            
        
    }
    
    public function testSelectFindOneWithBuilderNoObject()
    {
        
        $mock_event   = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $mock_builder = $this->getMockBuilder('DBALGateway\Builder\BuilderInterface')->getMock();
        
        $mock_table = new MockUserTableGateway('users',$this->getDoctrineConnection(),$mock_event,$this->getTableMetaData(),null,$mock_builder);
        
        $result = $mock_table->selectQuery()
            ->start()
                ->filterByUser(10000)
            ->end()
            ->findOne();
        
        $this->assertEquals(null,$result);        
    }

}
/* End of File */