<?php
namespace DBALGateway\Tests\Base\Mock;

use DBALGateway\Builder\AliasBuilder;
use DBALGateway\Builder\BuilderInterface;

class MockBuilder extends AliasBuilder implements BuilderInterface
{
    
    /**
      *  Convert data array into entity
      *
      *  @return mixed
      *  @param array $data
      *  @access public
      */
    public function build($data)
    {
        $sAlias = $this->getTableQueryAlias();
        
        $oClass = new \stdClass();
        
        $oClass->id = $this->getField($data,'id',$sAlias);
        $oClass->field = $this->getField($data,'field',$sAlias);
        
        return $oClass;
    }
    
    /**
      *  Convert and entity into a data array
      *
      *  @return array
      *  @access public
      */
    public function demolish($entity)
    {
        
    }
    
    
}
/* End of class */