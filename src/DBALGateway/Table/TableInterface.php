<?php
namespace DBALGateway\Table;

use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Connection;
use DBALGateway\Metadata\Table as TableMeta;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
  *  Interface for table gateways
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 0.0.1
  */
interface TableInterface
{
    
    public function initilize();
    
    
    public function find();
    
    
    public function findOne();
    
    
    public function insert();
    
    
    public function delete();
    
    
    public function update();
    
    /**
      *  Clears the query container;
      *
      *  @access public
      *  @return TableInterface
      */
    public function clear();
    
    /**
      *  Return the query container
      *
      *  @access public
      *  @return DBALGateway\Container\AbstractContainer
      */
    public function head();
    
    
    /**
      *   Returns the doctrine DBAL connection
      *
      *   @access public
      *   @return Doctrine\DBAL\Connection
      */
    public function getAdapater();
    
    /**
      *  Sets the geteway with doctrine dbal
      *
      *  @access public
      *  @param Connection $conn
      *  @return TableInterface
      */
    public function setAdapater(Connection $conn);
    
    /**
      *   
      */    
    public function setMetadata(TableMeta $meta);
    
    /**
      *   
      */
    public function getMetadata();
    
    /**
      *   
      */
    public function setEventDispatcher(EventDispatcher $dispatcher);
    
    
    /**
      *   
      */
    public function getEventDispatcher();
    
    
    
    /**
      *  Create a new instance of the querybuilder
      *
      *  @access public
      *  @return QueryBuilder
      */
    public function newQueryBuilder();
    
}
/* End of File */