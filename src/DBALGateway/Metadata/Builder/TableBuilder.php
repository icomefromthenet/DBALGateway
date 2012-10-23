<?php
namespace DBALGateway\Metadata\Builder;

use Doctrine\DBAL\Connection;
use DBALGateway\Metadata\Table;

/**
  *  Object used to build Tables
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 0.0.1
  */
class TableBuilder extends AbstractBuilder
{
    /**
      *  @var string the table name 
      */
    protected $table_name;
    
    /**
      *  Class constructor 
      */
    public function __construct()
    {
        $this->node = new Table();
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
      *  Return a new column builder
      *  
      *  @return ColumnBuilder
      *  @access public
      */
    public function addColumn()
    {
        $table = new ColumnBuilder();
        $table->setAdapter($this->adapter);
        $table->setParent($this);
        
        return $table;
    }
    
    /**
      *  Return a new column builder
      *  
      *  @return PrimaryColumnBuilder
      *  @access public
      */
    public function addPrimaryColumn()
    {
        $table = new PrimaryColumnBuilder();
        $table->setAdapter($this->adapter);
        $table->setParent($this);
        
        return $table;
    }
    
    
    /**
      *  Return a new column builder
      *  
      *  @return ForeignColumnBuilder
      *  @access public
      */
    public function addForeignColumn()
    {
        $table = new ForeignColumnBuilder();
        $table->setAdapter($this->adapter);
        $table->setParent($this);
        
        return $table;
    }
    
    /**
      *  Return a new column builder
      *  
      *  @return VirtualColumnBuilder
      *  @access public
      */
    public function addVirtualColumn()
    {
        $table = new VirtualColumnBuilder();
        $table->setAdapter($this->adapter);
        $table->setParent($this);
        
        return $table;
    }
    
    
    public function end()
    {
        # attach this table to the schema
        $schema = $this->parent->getNode()->attach($this->node);
        
        # attach the adapter and other options to the table
        $this->node->setAdapter($this->adapter);
        $this->node->setLabel($this->table_name);
                
        return $this->parent;
    }
    
}
/* End of File */