<?php
namespace DBALGateway\Metadata\Builder;

use Doctrine\DBAL\Connection;
use DBALGateway\Metadata\Column;
use DBALGateway\Metadata\Node;
use DBALGateway\Exception;

/**
  *  Builder for normal columns 
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 0.0.1
  */
class ColumnBuilder extends AbstractBuilder
{
    /**
      *  @var string the table name 
      */
    protected $table_name;
    
    protected $ordinal_position;
    
    protected $default_value;
    
    protected $is_nullable;
    
    protected $doctrine_type;
    
    protected $max_length;
    
    protected $numeric_precision;
    
    protected $numeric_scale;
    
    protected $numeric_signed;
    
    /**
      *  Class constructor 
      */
    public function __construct()
    {
        $this->node = new Column();
        
        $this->numeric_signed = false;
        $this->is_nullable    = false;
    }
    
    /**
      *  Set the table name
      *
      *  @access public
      *  @param string $name
      *  @return TableBuilder
      */
    public function setTableName($name)
    {
        $this->table_name = $name;
        
        return $this;
    }
    
    /**
      *  Sets the ordinal position in insert string
      *
      *  @access public
      *  @param integer $position
      *  @return ColumnBuilder
      */
    public function setOrdinalPosition($position)
    {
        if(is_init($position) === false) {
            throw new Exception('ordinal position must be an integer');
        }
        
        $this->ordinal_position = $position;
        
        return $this;
    }
    
    /**
      *  Sets the default value of a column
      *  
      *  @access public
      *  @return ColumnBuilder
      *  @param mixed $default
      */
    public function setDefaultValue($default)
    {
        $this->default_value = $default;
        
        return $this;
    }
    
    /**
      *  Sets if this column accepts null values
      *
      *  @param boolean $is_null 
      *  @access public
      *  @return ColumnBuilder
      */
    public function setIsNullable($is_null)
    {
        if(is_bool($is_null) === false) {
            throw new Exception('is nullable requires a boolean value');
        }
        
        $this->is_nullable = $is_null;
        
        return $this;
    }
    
    /**
      *  Sets the mysql datatype will map to a Doctrine Type internally
      *  
      *  @param string $name the mysql datatype
      *  @access public 
      *  @return ColumnBuilder
      */
    public function setDoctrineDatatype($name)
    {
        $this->doctrine_type = $this->getAdapter()->getDatabasePlatform()->getDoctrineTypeMapping($name);

        return $this;
    }
    
    /**
      *  Sets the maxium length of the column
      *
      *  @param integer $length
      *  @access public
      *  @return ColumnBuilder
      */
    public function setCharacterMaxiumLength($length)
    {
        if(is_integer($length) === false) {
            throw new Exception('Maxium character length must be an integer');
        }
     
        $this->max_length = $length;
     
        return $this;   
    }
    
    /**
      *   Sets the level of precision on column
      *   
      *   @param integer $precision
      *   @access public
      *   @return ColumnBuilder
      */
    public function setNumericPrecision($precision)
    {
        if(is_integer($length) === false) {
            throw new Exception('Numeric Precision must be an integer');
        }
    
        $this->numeric_precision = $precision;
    
        return $this;    
    }
    
    /**
      *  Sets the numeric unsigned
      *
      *  @param boolean $signed
      *  @access public
      *  @return ColumnBuilder
      */
    public function setNumericUnsigned($signed)
    {
        if(is_integer($length) === false) {
            throw new Exception('Numeric Sign must be a boolean');
        }
        
        $this->numeric_signed = $signed;
        
        return $this;    
    }
    
    /**
      *  Sets the numeric scale
      *
      *   @param integer $scale
      *   @access public
      *   @return ColumnBuilder
      */
    public function setNumericScale($scale)
    {
        if(is_integer($length) === false) {
            throw new Exception('Numeric Scale must be an integer');
        }
        
        $this->numeric_scale = $scale;
        
        return $this;    
    }
    
    /**
      *  Builds the node
      *
      *  @access public
      *  @return \DBALGateway\Metadata\Builder\TableBuilder
      */    
    public function end()
    {
        # attach this column to the table
        $this->parent->getNode()->attach($this->node);
        
        # attach the adapter and other options to the column
        $this->node->setAdapter($this->adapter);
        $this->node->setLabel($this->table_name);
       
        $this->node[Column::COLUMN_DEFAULT_INDEX]           = $this->default_value;
        $this->node[Column::CHARACTER_MAXIMUM_LENGTH_INDEX] = $this->max_length;
        $this->node[Column::DATA_TYPE_INDEX]                = $this->doctrine_type;
        $this->node[Column::IS_NULLABLE_INDEX]              = $this->is_nullable;
        $this->node[Column::ORDINAL_POSITION_INDEX]         = $this->ordinal_position;
        $this->node[Column::NUMERIC_PRECISION_INDEX]        = $this->numeric_precision;
        $this->node[Column::NUMERIC_UNSIGNED_INDEX]         = $this->numeric_signed;
        $this->node[Column::NUMERIC_SCALE_INDEX]            = $this->numeric_scale;
                
        return $this->parent;
    }
    
}
/* End of File */