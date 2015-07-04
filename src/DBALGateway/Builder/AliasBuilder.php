<?php
namespace DBALGateway\Builder;

/**
  *  Interface for entity builders
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 0.0.1
  */
abstract class AliasBuilder implements BuilderInterface, AliasInterface
{
    
    
    protected $sAlias;
    
    
    public function setTableQueryAlias($sAlias)
    {
        $this->sAlias = $sAlias;
    }
    
    public function getTableQueryAlias()
    {
        return $this->sAlias;     
    }
    
    
    /**
     * If alias exists fetch field using its alias name, if alias is empty
     * use the normal field name to return the value at that index
     * 
     * If index does not exist
     * 
     * @return mixed the field at the index
     * @param   array   $aResult    The result from the database query
     * @param   string  $sField     The no-alias field name
     * @param   string  $sAlias     The Alias assigned 
     */ 
    protected function getField(array &$aResult,$sField,$sAlias)
    {
        
        if(!empty($sAlias)) {
           return $aResult[$sAlias.'_'.$sField]; 
        } else {
            return $aResult[$sField];
        }
        
    }
    
    
    
    /**
      *  Convert data array into entity
      *
      *  @return mixed
      *  @param array $data
      *  @access public
      */
    abstract public function build($data);
    
    /**
      *  Convert and entity into a data array
      *
      *  @return array
      *  @access public
      */
    abstract public function demolish($entity);
    
}

/* End of File */