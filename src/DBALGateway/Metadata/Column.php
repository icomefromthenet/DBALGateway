<?php
namespace DBALGateway\Metadata;

use Doctrine\DBAL\Types\Type;

/**
  *  Object to represent a Column 
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 0.0.1
  */
class Column extends Node
{
    
    const ORDINAL_POSITION_INDEX = "ordinal_position";
    
    const COLUMN_DEFAULT_INDEX = "column_default";
    
    const IS_NULLABLE_INDEX = "is_nullable";
    
    const DATA_TYPE_INDEX   = "data_type";
    
    const CHARACTER_MAXIMUM_LENGTH_INDEX = "character_maximum_length";
    
    const NUMERIC_PRECISION_INDEX = "numeric_precision";
    
    const NUMERIC_UNSIGNED_INDEX = "numeric_unsigned";
    
    const NUMERIC_SCALE_INDEX = "numeric_scale";
    
    
    /**
      *  Class Constructor 
      */
    public function __construct()
    {
        $this[self::NUMERIC_UNSIGNED_INDEX] = false;
        $this[self::IS_NULLABLE_INDEX]      = false;
    }
    
        
    /**
      *   Return the ordinal position of the column
      *
      *   @access public  
      *   @return int $ordinalPosition
      */
    public function getOrdinalPosition()
    {
        return $this[self::ORDINAL_POSITION_INDEX];
    }

    /**
      *  Return the column default
      *
      *  @return mixed
      *  @access public
      */
    public function getColumnDefault()
    {
        return $this[self::COLUMN_DEFAULT_INDEX];
    }

    /**
      *  Return if the column is nullable
      *
      *  @access public
      *  @return boolean
      */
    public function getIsNullable()
    {
        return $this[self::IS_NULLABLE_INDEX];
    }

    /**
      *  Gets the Doctrine datatype
      *
      *  @return Doctrine\DBAL\Types\Type
      */
    public function getDataType()
    {
        return $this[self::DATA_TYPE_INDEX];
    }

    /**
      *  Gets the maximum length
      *
      *  @access public
      *  @return integer
      */
    public function getCharacterMaximumLength()
    {
        return $this[self::CHARACTER_MAXIMUM_LENGTH_INDEX];
    }
    

    /**
      *  Gets the numeric precision
      *
      *  @access public
      *  @return integer
      */
    public function getNumericPrecision()
    {
        return $this[self::NUMERIC_PRECISION_INDEX];
    }


    /**
      *  Return the numeric scale
      *
      *  @access public
      *  @return integer
      */
    public function getNumericScale()
    {
        return $this[self::NUMERIC_SCALE_INDEX];
    }

    /**
      *  Is the column unsigned
      *
      *  @access public
      *  @return boolean
      */
    public function getNumericUnsigned()
    {
        return $this[self::NUMERIC_UNSIGNED_INDEX];
    }

    
    //------------------------------------------------------------------

    public function findPrimaryKeys()
    {
        $return = array();
        
        foreach ($this->links as $linked_node) {
            if ($linked_node instanceof PKColumn ) {
                $return[] = $linked_node;
            }
        }
        
        return $return;
        
    }
    
    
    public function findForeignKeys()
    {
        $return = array();
        
        foreach ($this->links as $linked_node) {
            if ($linked_node instanceof FKColumn ) {
                $return[] = $linked_node;
            }
        }
        
        return $return;
        
    }
    
    
    public function findVirtualColumns()
    {
        $return = array();
        
        foreach ($this->links as $linked_node) {
            if ($linked_node instanceof VColumn ) {
                $return[] = $linked_node;
            }
        }
        
        return $return;
        
    }
    
    
}
/* End of File */