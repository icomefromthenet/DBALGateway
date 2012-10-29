<?php
namespace DBALGateway\Metadata;

use Doctrine\DBAL\Schema\Table as DoctrineSchemaTable;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Schema\Column;
use Doctrine\DBAL\Schema\SchemaException;

/**
  *  Object adds VColumns to doctrine Schema\Table
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 0.0.1
  */
class Table extends DoctrineSchemaTable
{
    /**
      *  @var array the virtual columns 
      */
    protected $_vcolumns = array();
    
    
    /**
     * Add a virtual column to the table, useful for calculated columns
     * 
     * @param string $columnName
     * @param string $columnType
     * @param array $options
     * @return Column
     */
    public function addVirtualColumn($columnName, $typeName, array $options=array())
    {
        $column = new VColumn($columnName, Type::getType($typeName), $options);
        $this->_addVirtualColumn($column);
        return $column;
    }
    
    /**
      *  Adds a virtual column to collection
      *
      *  @access protected
      *  @var Column $column
      */
    protected function _addVirtualColumn(VColumn $column)
    {
        $columnName = $column->getName();
        $columnName = strtolower($columnName);

        if (isset($this->_vcolumns[$columnName])) {
            throw SchemaException::columnAlreadyExists($this->getName(), $columnName);
        }

        $this->_vcolumns[$columnName] = $column;
    }
    
     /**
     *  Return the virtual columns
     *
     *  @return VColumn[]
     *  @access public
     */
    public function getVirtualColumns()
    {
        return $this->_vcolumns;
    }
    
    /**
      *  Return the combined vcolumns and normal columns
      *
      *  @access public
      *  @return mixed
      */    
    public function getCombinedColumns()
    {
        return array_merge($this->getColumns(),$this->_vcolumns);
    }
    
}
/* End of File */