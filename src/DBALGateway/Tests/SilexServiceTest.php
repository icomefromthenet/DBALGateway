<?php
namespace DBALGateway\Tests;

use DBALGateway\Tests\Base\TestsWithFixture;
use DBALGateway\Silex\Service as GatewayService;
use Silex\Application;
use Silex\Provider\MonologServiceProvider;
use Monolog\Monolog;
use Monolog\Handler\TestHandler;
use DBALGateway\Tests\Base\Mock\MockUserTableGateway;
use DateTime;

class SilexServiceTest extends TestsWithFixture
{
    
    /**
    * PHPUnit setUp for setting up the application.
    *
    * Note: Child classes that define a setUp method must call
    * parent::setUp().
    */
    public function setUp()
    {
        $this->app = $this->createApplication();
        
        parent::setUp();
    }

    
    public function createApplication()
    {
        $app = new Application();

        # enable debug
        $app['debug'] = true;
        unset($app['exception_handler']);
        
        # register monolog
        $app->register(new MonologServiceProvider(), array(
            'monolog.logfile' => '/var/tmp/silex_development.log',
        ));
                
        # assign the db to the expected namespace in pimple
        $app['db'] = $this->getDoctrineConnection();
        
        # Register the gateway service
        $app->register(new GatewayService(), array());
        
        return $app;
    }

    public function testStreamWritesToLog()
    {
        
        $this->app->boot();
        
        # assert logger was loaded
        $this->assertInstanceOf('DBALGateway\Feature\StreamQueryLogger',$this->app['dbal_gateway.logger']);
        
        $table = new  MockUserTableGateway('users',$this->app['db'],$this->app['dispatcher'],$this->getTableMetaData());      
        $logger = $this->app['dbal_gateway.logger'];
        
        $username = 'myuser';
        $new_date = new DateTime();
        $fname    = 'myfname';
        $lname    = 'mylname';
        
        $success = $table->insertQuery()
             ->start()
                ->addColumn('username',$username)
                ->addColumn('dte_created',$new_date)
                ->addColumn('first_name',$fname)
                ->addColumn('last_name',$lname)
                ->addColumn('dte_updated',$new_date)
            ->end()    
        ->insert();
        
        $this->assertTrue($success);
        
        $last_query = $logger->lastQuery();
        $this->assertEquals('INSERT INTO users (username, dte_created, first_name, last_name, dte_updated) VALUES (?, ?, ?, ?, ?)',$last_query['sql']);
    }
}
/* End of File */



