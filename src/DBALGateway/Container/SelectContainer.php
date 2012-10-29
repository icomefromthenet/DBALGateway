<?php
namespace DBALGateway\Container;

use DBALGateway\TableInterface;
use Doctrine\DBAL\Query\QueryBuilder;

class SelectContainer extends AbstractContainer
{
    
    /**
      *  @var boolean has query been bound to a table  
      */
    protected $bound = false;
    
    /**
      *  Starts the internal Query 
      */
    public function start()
    {
        if($this->bound === false) {
            $this->query->select(array_keys($this->gateway->getMetaData()->getColumns()));
            $this->query->from($this->gateway->getMetaData()->getName(),null);
            $this->bound = true;    
        }
        
        return $this->query;
    }
    
    
    public function where()
    {
        if($this->bound === false) {
            $this->query->select(array_keys($this->gateway->getMetaData()->getColumns()));
            $this->query->from($this->gateway->getMetaData()->getName());
            $this->bound = true;    
        }
        
        return $this->query;
    }
    
}

/* End of File */