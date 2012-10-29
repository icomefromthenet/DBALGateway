<?php
namespace DBALGateway\Silex;

use Silex\Application;
use Silex\ServiceProviderInterface;

class Service implements ServiceProviderInterface
{
    
    public function register(Application $app)
    {
        $app['hello'] = $app->protect(function ($name) use ($app) {
            $default = $app['hello.default_name'] ? $app['hello.default_name'] : '';
            $name = $name ?: $default;

            return 'Hello '.$app->escape($name);
        });
    }

    public function boot(Application $app)
    {
    
    }
    
}
/* End of File */