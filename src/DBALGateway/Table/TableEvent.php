<?php
namespace DBALGateway\Table;


use DBALGateway\Table\TableInterface;

/**
  *  Event object for all TableGateway events
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 0.0.1
  */
class TableEvent 
{
    /**
      *  @var TableInterface the table instance 
      */
    protected $table;
    
    /**
      *  @var mixed the result  
      */
    protected $result;

    /**
     * @var string the name of the table event
     */
    protected $eventName;
    
    /**
      *  Class Constructor
      *
      *  @access public
      *  @param TableInterface $table
      */
    public function __construct(TableInterface $table, string $eventName, $result = null)
    {
        $this->table  = $table;
        $this->result = $result;
        $this->eventName = $eventName;
    }
    
    /**
      *  Fetch the assigned TableGateway
      *
      *  @access public
      *  @return TableInterface
      */
    public function getTable()
    {
        return $this->table;
    }
    
    /**
      *  Return the result
      *
      *  @access public
      *  @return mixed
      */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Return the event name
     * 
     * @return the event Name From TableEvents::xxxxx
     */
    public function getTableEvent() : string
    {
      return $this->eventName;
    }
    
}
/* End of File */