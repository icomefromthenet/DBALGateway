<?php
namespace DBALGateway\Feature;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use DBALGateway\Table\TableEvents;
use DBALGateway\Table\TableEvent;
use DBALGateway\Exception as GatewayException;

class QueryLogger implements EventSubscriberInterface
{
    
    static public function getSubscribedEvents()
    {
        return array(
            TableEvents::PRE_INITIALIZE  => array('onPreInit', 0),
        );
    }

    public function onPreInit(TableEvent $event)
    {
        
    }
    
}
/* End of File */