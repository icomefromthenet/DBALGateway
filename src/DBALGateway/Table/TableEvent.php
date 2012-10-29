<?php
namespace DBALGateway\Table;

use Symfony\Component\EventDispatcher\Event;
use DBALGateway\Table\TableInterface;

/**
  *  Event object for all TableGateway events
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 0.0.1
  */
class TableEvent extends Event
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
      *  Class Constructor
      *
      *  @access public
      *  @param TableInterface $table
      */
    public function __construct(TableInterface $table, $result = null)
    {
        $this->table  = $table;
        $this->result = $result;
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
    
}
/* End of File */