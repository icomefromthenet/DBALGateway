<?php
namespace DBALGateway\Feature;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use DBALGateway\Table\TableEvents;
use DBALGateway\Table\TableEvent;
use DBALGateway\Exception as GatewayException;
use Doctrine\DBAL\Logging\SQLLogger;
use Psr\Log\LoggerInterface;
use \Closure;

/**
  *  Stream logger will write queries to a stream ie monolog
  *  
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 0.0.1
  */
class StreamQueryLogger implements EventSubscriberInterface , SQLLogger
{
    /**
      *  @var array[] continer for query history 
      */
    public $queries = array();
    
    /**
      *  @var integer the unix time query started 
      */
    protected $start;
    
    /**
      *  @var integer current index of the last query 
      */
    public $currentQuery = 0;
    
    /**
      *  @var LoggerInterface the logger instance
      */
    protected $logger;
    
    /**
      *  @var array[] the last query 
      */
    protected $last_query;
    
            
    static public function getSubscribedEvents()
    {
        return array(
            TableEvents::POST_INITIALIZE  => array('onPostInit', 0),
        );
    }
    
    
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
    

    /**
      *  Register the logger for this table, only use this
      *  method if you looking to debug a single table during dev
      *  this will overrite other loggers. Use a global setup for query
      *  logging e.g DBALGateway\Silex\Service.
      *
      *  @access public
      *  @return void
      *  @param TableEvent $event
      */
    public function onPostInit(TableEvent $event)
    {
        $event->getTable()->getAdapater()->getConfiguration()->setSQLLogger($this);    
    }
    
    /**
     * Mark when query is run
     *
     * @return void
     */
    public function startQuery($sql, array $params = null, array $types = null)
    {
        $this->start = microtime(true);
        $this->queries[$this->currentQuery] = array('sql' => $sql, 'params' => $params, 'types' => $types, 'executionMS' => 0);
    }

    /**
     * Mark the last started query as stopped. This can be used for timing of queries.
     *
     * @return void
     */
    public function stopQuery()
    {
        # set the elapsed time
        $this->queries[$this->currentQuery]['executionMS'] = microtime(true) - $this->start;
        
        # call the output format closure
        $this->logger->info($this->queries[$this->currentQuery]['sql'],array(
                                       'execution' => $this->queries[$this->currentQuery]['executionMS'],
                                       'params'    => $this->queries[$this->currentQuery]['params']
                                ));
        
        # save last query for one more iteration
        $this->last_query = $this->queries[$this->currentQuery];
        
        
    }
    
    //------------------------------------------------------------------
    # Accessors
    
    public function lastQuery()
    {
        return $this->last_query;
    }
    
    
    //------------------------------------------------------------------

    /**
      *   Class Destructor
      *   
      */
    public function __destruct()
    {
        unset($this->last_query);
        unset($this->logger);
        unset($this->queries);
    }
    
}
/* End of File */