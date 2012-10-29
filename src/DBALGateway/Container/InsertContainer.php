<?php
namespace DBALGateway\Container;

use DBALGateway\TableInterface;
use Doctrine\DBAL\Query\QueryBuilder;
use DBALGateway\Exception as GatewayException;

class InsertContainer extends AbstractContainer
{
    
    /**
      *  @array the columns 
      */
    protected $data = array();
    
    /**
      *  @var a type array 
      */
    protected $type = array();
    
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
            throw new GatewayException(sprintf('column name %s not found under table %s unable to add to insert statement',$column,$this->gateway->getMetaData()->getName()));
        }
        
        $this->data[$column] = $value;
        $this->type[] = $this->gateway->getMetaData()->getColumn($column)->getType();
        
        return $this;
    }
    
    /**
      *  Return the assigned data
      *
      *  @access public
      *  @return $column[] = $value
      */
    public function getColumns()
    {
        return $this->data;
    }
    
    /**
      *  Return they type info for each added column
      *
      *  @access public
      *  @return Doctrine\DBAL\Types\Type[]
      */
    public function getTypeInfo()
    {
        return $this->type;
    }
    
    
    /**
      *  Returns the gateway so query can be executed
      *
      *  @access public
      *  @return DBALGateway\Table\TableInterface
      */
    public function end()
    {
        return $this->gateway;    
    }
    
    
    
}

/* End of File */