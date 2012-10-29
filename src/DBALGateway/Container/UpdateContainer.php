<?php
namespace DBALGateway\Container;

use DBALGateway\TableInterface;
use Doctrine\DBAL\Query\QueryBuilder;
use DBALGateway\Exception as GatewayException;

class UpdateContainer extends AbstractContainer
{

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
        if($this->gateway->getMetaData()->hasColumn($column) === false) {
            throw new GatewayException(sprintf('column name %s not found under table %s unable to add to update statement',$column,$this->gateway->getMetaData()->getName()));
        }
        
        $this->query->set($column,':'.$column);
        $this->query->createNamedParameter($value,$this->gateway->getMetaData()->getColumn($column)->getType(),':'.$column);
        
        return $this;
    }
    
    
    public function start()
    {
        $this->query->update($this->gateway->getMetaData()->getName());
        
        return $this;
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
    
}

/* End of File */