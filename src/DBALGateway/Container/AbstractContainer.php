<?php
namespace DBALGateway\Container;

use DBALGateway\Table\TableInterface;
use DBALGateway\Query\AbstractQuery;

class AbstractContainer
{
    /**
      *  @var TableInterface the table instance 
      */
    protected $gateway;
    
    /**
      *  @var QueryBuilder the query class 
      */
    protected $query;
    
    
    /**
      *  Class Constructor
      *
      *  @param TableInterface $table
      *  @param QueryBuilder $query
      */
    public function __construct(TableInterface $gateway, AbstractQuery $query = null)
    {
        $this->gateway = $gateway;
        $this->query   = $query;
    }
    
   
    
    /**
      *  Starts the internal Query 
      */
    public function start()
    {
        return $this;
    }
    
    /**
      *  Return the interal QueryBuilder
      *
      *  @access public
      *  @return DBALGateway\AbstractQuery\QueryBuilder
      */
    public function getQuery()
    {
        return $this->query;
    }
    
}

/* End of File */