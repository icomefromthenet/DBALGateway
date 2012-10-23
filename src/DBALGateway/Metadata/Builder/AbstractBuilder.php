<?php
namespace DBALGateway\Metadata\Builder;


use Doctrine\DBAL\Connection;
use DBALGateway\Metadata\Node;

/**
  *  Base class for all builders
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 0.0.1
  */
class AbstractBuilder implements BuilderInterface
{
    
    /**
      *  @var DBALGateway\Metadata\Node the graph root
      */
    protected $node;
    
    /**
      *  @var BuilderInterface the graph parent
      */
    protected $parent;
    
    /**
      *  @var Doctrine\DBAL\Connection the db connection
      */
    protected $adapter;
    
    
    public function setAdapter(Connection $connection)
    {
        $this->adapter = $connection;
    }
    
    public function getAdapter()
    {   
        return $this->adapter;   
    }
    
    //------------------------------------------------------------------
    # Builder interface
    
    /**
      *  Return the parent builder alias to getParent()
      *
      *  @access public
      *  @return BuilderInterface
      */
    public function end()
    {
        return $this->parent;
    }
    
    /**
      *  Sets the parent node
      *
      *  @access public
      *  @param BuilderInterface $parent
      */
    public function setParent(BuilderInterface $parent)
    {
        $this->parent = $parent;
    }
    
    /**
      *  Return the parent node
      *
      *  @access public
      *  @return BuilderInterface
      */
    public function getParent()
    {
        return $this->parent;
    }
    
    /**
      *   Set the root node
      *
      *   @access public
      *   @param DBALGateway\Metadata\Node $root
      */
    public function setNode(Node $node)
    {
        $this->node = $node;
    }
    
    /**
      *  Return the root node
      *
      *  @access public
      *  @return DBALGateway\Metadata\Node
      */
    public function getNode()
    {
        return $this->node;
    }
    
    
}
/* End of File */

