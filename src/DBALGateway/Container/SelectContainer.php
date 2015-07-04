<?php
namespace DBALGateway\Container;

use DBALGateway\TableInterface;
use Doctrine\DBAL\Query\QueryBuilder;

class SelectContainer extends AbstractContainer
{
    
    
    /**
     * Map columns with alias
     */ 
    protected function bindAliasToColumns(array $aColumns) 
    {
        
        $alias =$this->gateway->getTableQueryAlias();
        
        if(false === empty($alias)) {
        
            foreach($aColumns as &$sColname) {
                $sColname = $alias.'.'.$sColname .' AS '.$alias .'_'. $sColname; 
            }
        
        }
        
        return $aColumns;
    }
    
    
    
    
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
            $alias = empty($this->gateway->getTableQueryAlias()) ? null : $this->gateway->getTableQueryAlias();
            $this->query->select($this->bindAliasToColumns(array_keys($this->gateway->getMetaData()->getColumns())));
            $this->query->from($this->gateway->getMetaData()->getName(),$alias);
            $this->bound = true;    
        }
        
        return $this->query;
    }
    
    
    public function where()
    {
        if($this->bound === false) {
            $alias = empty($this->gateway->getTableQueryAlias()) ? null : $this->gateway->getTableQueryAlias();
            $this->query->select($this->bindAliasToColumns(array_keys($this->gateway->getMetaData()->getColumns())));
            $this->query->from($this->gateway->getMetaData()->getName(),$alias);
            $this->bound = true;    
        }
        
        return $this->query;
    }
    
}

/* End of File */
