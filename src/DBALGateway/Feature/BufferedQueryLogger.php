<?php
namespace DBALGateway\Feature;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use DBALGateway\Table\TableEvents;
use DBALGateway\Table\TableEvent;
use DBALGateway\Exception as GatewayException;
use Doctrine\DBAL\Logging\SQLLogger;

/**
  *  In memory query logger , used if your looking to record the last run query
  *  and don't want to log to external file. Unlink the doctrine counterpart this
  *  class has a buffer setting to save memory.
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 0.0.1
  */
class BufferedQueryLogger implements EventSubscriberInterface , SQLLogger
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
      *  @var integer 
      */
    protected $max = 5;
    
    /**
      *  @var current index of the last query 
      */
    public $currentQuery = 0;
    
    static public function getSubscribedEvents()
    {
        return array(
            TableEvents::POST_INITIALIZE  => array('onPostInit', 0),
        );
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
        if($this->currentQuery >= $this->max) {
            $this->currentQuery = 0;
        }
        
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
        $this->queries[$this->currentQuery++]['executionMS'] = microtime(true) - $this->start;
    }
    
    //------------------------------------------------------------------
    # Accessors
    
    public function lastQuery()
    {
        return $this->queries[--$this->currentQuery];
    }
    
    public function setMaxBuffer($count)
    {
        if($count < 1) {
            throw new \RuntimeException('QueryLogger max buffer count must be > 1');
        }
        
        $this->max = 1;
    }
    
    public function getMaxBuffer()
    {
        return $this->max;
    }
    
    //------------------------------------------------------------------

    /**
      *   Class Destructor
      *   
      */
    public function __destruct()
    {
        unset($this->queries);
    }
    
}
/* End of File */