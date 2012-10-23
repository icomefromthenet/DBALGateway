<?php
namespace DBALGateway\Metadata;

/**
  *  Object to represent a Schema 
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 0.0.1
  */
class Schema extends Node
{
    /**
      *  @var string the index 
      */
    const SCHEMA_NAME = 'schema_name';
    
    /**
      *  Return the schema name 
      */
    public function getSchemaName()
    {
        return $this[self::SCHEMA_NAME];
    }
    
    
    
    
}
/* End of File */