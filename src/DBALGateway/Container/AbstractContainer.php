<?php
namespace DBALGateway\Container;

use DBALGateway\Table\TableInterface;
use DBALGateway\Query\AbstractQuery;
use DBALGateway\Query\QueryInterface;

abstract class AbstractContainer
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
     * @var a query alias
     */ 
    protected $sAlias;
    
    
    /**
      *  Class Constructor
      *
      *  @param TableInterface $table
      *  @param QueryBuilder $query
      */
    public function __construct(TableInterface $gateway, AbstractQuery $query = null, $sAlias = '')
    {
        $this->gateway = $gateway;
        $this->query   = $query;
        $this->sAlias = (string) $sAlias;
        
        # inject the alias into query builder
        if($query instanceof QueryInterface) {
            $this->query->setDefaultAlias($sAlias);
        }
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
    
    /**
     * Return the assigned query alias
     * 
     * @return string the query alias assigned to his builder
     */ 
    public function getQueryAlias()
    {
        return $this->sAlias;
    }
    
    /**
     * Convert a normal field into alias field if the alias field is not empty
     * 
     * @param string    $sField The field to convert
     * @param string    $sAlias The Optional alis
     * @return string the alias field
     */ 
    public function convertToAliasField($sAlias = '',$sField)
    {
       $sAliasField = $sField;
       if(false === empty($sAlias)) {
           $sAliasField = $sAlias . '.' .$sField;
       }
       
       return $sAliasField;
        
    }
    
    /**
     * Map columns with alias
     */ 
    public function bindAliasToColumns($sAlias, array $aColumns) 
    {
        $alias = $sAlias;
        
        if(false === empty($alias)) {
        
            foreach($aColumns as &$sColname) {
                $sColname = $alias.'.'.$sColname .' AS '.$alias .'_'. $sColname; 
            }
        
        }
        
        return $aColumns;
    }
    
    /**
     * Convert aliased field into normal column 
     * 
     * @return  string the field 
     * @param   string  $sAlias
     * @param   string  $sField
     */ 
    public function extractAliasField($sAlias, $sField)
    {
        $sNormalField = $sField;
        $iStartAlias = strpos($sField,$sAlias.'.');
        $iAliasLength = strlen($sAlias.'.');
     
        if(0 === $iStartAlias) {
            $sNormalField = substr($sField,$iStartAlias+$iAliasLength);
        }
        
        return $sNormalField;
    }
    
}
/* End of File */