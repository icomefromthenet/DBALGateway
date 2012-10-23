<?php
namespace DBALGateway\Container;

use DBALGateway\TableInterface;
use Doctrine\DBAL\Query\QueryBuilder;

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
      *  @array the columns 
      */
    protected $data;
    
    /**
      *  Class Constructor
      *
      *  @param TableInterface $table
      *  @param QueryBuilder $query
      */
    public function __construct(TableInterface $gateway,QueryBuilder $query)
    {
        $this->gateway = $gateway;
        $this->query   = $query;
        $this->data    = array();
    }
    
    /**
      *  Adds a column to the internal collection
      *
      *  @access public
      *  @return AbstractContainer
      *  @param string $column name of the column
      *  @param mixed $value the value to save
      */
    public function addColumn($column,$value)
    {
        $this->data[$column] = $value;
        
        return $this;
    }
    
    /**
      *  Return the assigned data
      *
      *  @access public
      *  @return mixed $data
      */
    public function getColumns()
    {
        return $this->data;
    }
    
    
    /**
      *  Return the query builder
      *
      *  @access public
      *  @return QueryBuilder
      */
    public function where()
    {
        return $this->query;    
    }
    
    /**
      *  Execute the query
      *
      *  @access public
      *  @return mixed
      */
    public function end()
    {
        return $this->gateway;    
    }
    
    /**
      *  Starts the internal Query 
      */
    public function start()
    {
        return $this;
    }
    
}

/* End of File */