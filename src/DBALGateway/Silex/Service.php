<?php
namespace DBALGateway\Silex;

use Silex\Application;
use Silex\ServiceProviderInterface;

class Service implements ServiceProviderInterface
{
    
    public function register(Application $app)
    {
        # register the streamed query logger
        $app['dbal_gateway.logger'] = $app->share(function ($name) use ($app) {
            $monolog = $app['monolog'];
            return new \DBALGateway\Feature\StreamQueryLogger($monolog);
        });
        
    }

    public function boot(Application $app)
    {
        # boot the stream query logger
        $event   = $app['db']->getConfiguration()->setSQLLogger($app['dbal_gateway.logger']);
    }
    
}
/* End of File */