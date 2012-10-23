<?php
namespace DBALGateway\Metadata;

/**
  *  Object to represent a Table
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 0.0.1
  */
class Table extends Node
{
    /**
      *  @var string the index 
      */
    const TABLE_NAME = 'table_name';
    
    
    /**
      *  Return the table name
      *
      *  @access public
      *  @return string the name
      */
    public function getTableName()
    {
        return $this[self::TABLE_NAME];
    }
    
    
}
/* End of File */