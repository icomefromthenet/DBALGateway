<?php
namespace DBALGateway\Tests;

use DBALGateway\Tests\Base\TestsWithFixture;
use DBALGateway\Silex\Service as GatewayService;
use Silex\Application;
use Monolog\Monolog;
use Monolog\Handler\TestHandler;

class YourTest extends WebTestCase
{
    public function createApplication()
    {
        $app = new Application();

        # enable debug
        $app['debug'] = true;
        unset($app['exception_handler']);
        
        $monolog    = new Logger('SQLQuery');
        $handler    = new TestHandler();
        $monolog->pushHandler($handler);
        
        # create logger instance and subscribe to events
        $logger = new StreamQueryLogger($monolog);
        $event->addSubscriber($logger);
        
        $app->register(new GatewayService(), array(
            'doctrine' => $this->getDoctrineConnection(),
            'monolog'  => $logger,
        ));
        
        $app->get('/hello', function () use ($app) {
            $name = $app['request']->get('name');
        
            return $app['hello']($name);
        });
        
        return $app;
    }

    public function testFooBar()
    {
        
    }
}



