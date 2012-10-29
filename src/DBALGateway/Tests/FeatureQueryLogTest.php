<?php
namespace DBALGateway\Tests;

use DBALGateway\Tests\Base\TestsWithFixture;
use DBALGateway\Table\AbstractTable;
use DBALGateway\Tests\Base\Mock\MockUserTableGateway;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use DBALGateway\Table\TableEvents;
use Symfony\Component\EventDispatcher\EventDispatcher;
use DBALGateway\Feature\BufferedQueryLogger;
use DBALGateway\Feature\StreamQueryLogger;
use Monolog\Logger;
use Monolog\Handler\TestHandler;

class FeatureQueryLogTest extends TestsWithFixture
{


    public function testBufferedQueryLogger()
    {
        $doctrine   = $this->getDoctrineConnection();
        $meta       = $this->getTableMetaData();
        $event      = new EventDispatcher();
        
        # create logger instance and subscribe to events
        $logger = new BufferedQueryLogger();
        $event->addSubscriber($logger);
        
        # create table (fire init event)
        $mock_table = new MockUserTableGateway('users',$doctrine,$event,$meta);
        
        # run query
        $mock_table
        ->selectQuery()
            ->start()
                ->filterByUser(1)
            ->end()
        ->findOne();
        
        # run another query
        $mock_table
         ->selectQuery()
            ->start()
                ->filterByUser(2)
            ->end()
        ->findOne();
        
        # params match in queyr log
        $this->assertEquals($logger->queries[0]['params']['id'],1);
        $this->assertEquals($logger->queries[1]['params']['id'],2);
        
        # run to max and see if list is circular
        
        $mock_table
         ->selectQuery()
            ->start()
                ->filterByUser(3)
            ->end()
        ->findOne();
        
        $mock_table
         ->selectQuery()
            ->start()
                ->filterByUser(4)
            ->end()
        ->findOne();
        
        $mock_table
         ->selectQuery()
            ->start()
                ->filterByUser(5)
            ->end()
        ->findOne();
        
        $mock_table
         ->selectQuery()
            ->start()
                ->filterByUser(6)
            ->end()
        ->findOne();
        
        # has the last restarted after 6th query (max = 5)        
        $this->assertEquals($logger->queries[0]['params']['id'],6);
        
        # test last query works
        $last_query = $logger->lastQuery();
        $this->assertEquals($last_query['params']['id'],6);
        $this->assertEquals($last_query['params']['id'],6);
        
        # test that max buffer porperty works
        $this->assertEquals(5,$logger->getMaxBuffer());
        
    }

    
    public function testStreamQueryLogger()
    {
        $doctrine   = $this->getDoctrineConnection();
        $meta       = $this->getTableMetaData();
        $event      = new EventDispatcher();
        
        
        $monolog    = new Logger('SQLQuery');
        $handler    = new TestHandler();
        $monolog->pushHandler($handler);
        
        
        # create logger instance and subscribe to events
        $logger = new StreamQueryLogger($monolog);
        $event->addSubscriber($logger);
        
        # create table (fire init event)
        $mock_table = new MockUserTableGateway('users',$doctrine,$event,$meta);
        
        $mock_table
         ->selectQuery()
            ->start()
                ->filterByUser(3)
            ->end()
        ->findOne();
        
        # assert that the log method was given information
        $this->assertTrue($handler->hasInfoRecords());
        
        $records = $handler->getRecords();
        
        # test the info record matches
        $this->assertArrayHasKey('execution',$records[0]['context']);
        $this->assertArrayHasKey('params',$records[0]['context']);
        
    }

}
/* End of File */