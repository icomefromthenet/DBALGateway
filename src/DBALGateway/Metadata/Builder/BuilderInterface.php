<?php
namespace DBALGateway\Metadata\Builder;

use DBALGateway\Metadata\Node;

/**
  *  Common interface for all builders
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 0.0.1
  */
interface BuilderInterface
{
    /**
      *  Return the parent builder alias to getParent()
      *
      *  @access public
      *  @return BuilderInterface
      */
    public function end();
    
    /**
      *  Sets the parent node
      *
      *  @access public
      *  @param BuilderInterface $parent
      */
    public function setParent(BuilderInterface $parent);
    
    /**
      *  Return the parent node
      *
      *  @access public
      *  @return BuilderInterface
      */
    public function getParent();
    
    /**
      *   Set the root node
      *
      *   @access public
      *   @param Node $node
      */
    public function setNode(Node $node);
    
    /**
      *  Return the root node
      *
      *  @access public
      *  @return Node
      */
    public function getNode();
    
}

/* End of File */