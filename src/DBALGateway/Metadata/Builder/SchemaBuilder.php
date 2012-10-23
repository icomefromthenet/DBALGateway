<?php
namespace DBALGateway\Metadata\Builder;

use Doctrine\DBAL\Connection;
use DBALGateway\Metadata\Schema;
use DBALGateway\Metadata\Node;

/**
  *  Used to build metadata schema nodes
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 0.0.1
  */
class SchemaBuilder extends AbstractBuilder
{
    /**
      *  @var string the schema name 
      */
    protected $schema_name;
    
    /**
      *  Class constructor 
      */
    public function __construct()
    {
        $this->node = new Schema();
    }
    
    /**
      *  Set Schema Name
      *
      *  @access public
      *  @param string $name
      *  @return SchemaBuilder
      */
    public function setSchemaName($name)
    {
        $this->schema_name = $name;
        
        return $this;
    }
    
    /**
      *  Adds a table builder
      *
      *  @return TableBuilder
      *  @access public
      */
    public function addTable()
    {
        $table = new TableBuilder();
        $table->setAdapter($this->adapter);
        $table->setParent($this);
        
        return $table;
    }
    
    
    public function addViewTable()
    {
        $table = new ViewTableBuilder();
        $table->setAdapter($this->adapter);
        $table->setParent($this);
        
        return $table;
    }
    
    /**
      *  Return the parent builder
      *
      *  @access public
      *  @return null
      */
    public function end()
    {
        $this->node[Node::ADAPTER_INDEX] = $this->adapter;
        $this->node->setLabel($this->schema_name);
        
        return $this->node;
    }
    
}
/* End of File */