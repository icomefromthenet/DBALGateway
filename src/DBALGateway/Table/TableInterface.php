<?php
namespace DBALGateway\Table;

use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Table;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use DBALGateway\Builder\BuilderInterface;


/**
  *  Interface for table gateways
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 0.0.1
  */
interface TableInterface
{
    /**
      *  Initilize the table gateway
      *
      *  @return TableInterface
      *  @access public
      */
    public function initilize();
    
    /**
      *  Execute a select query, return many rows
      *  Requires a query be setup via the factory
      *
      *  
      *  @return mixed
      *  @access public
      */
    public function find();
    
     /**
      *  Execute a select query, return single row
      *  Requires a query be setup via the factory
      *  
      *  @return mixed
      *  @access public
      */
    public function findOne();
    
    /**
      *  Execute an insert query, return single row
      *  Requires a query be setup via the factory
      *  
      *  @return mixed
      *  @access public
      */
    public function insert();
    
    /**
      *  Execute a delete query, return single row
      *  Requires a query be setup via the factory
      *  
      *  @return mixed
      *  @access public
      */
    public function delete();
    
    /**
      *  Execute a update query, return single row
      *  Requires a query be setup via the factory
      *  
      *  @return mixed
      *  @access public
      */
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
      *   Sets the table metadata
      *
      *   @access public
      *   @return TableInterface
      *   @param Doctrine\DBAL\Schema\Table metadata
      */    
    public function setMetaData(Table $metadata);
    
    /**
      *   Fetches the table metadata
      *
      *   @access public
      *   @return DBALGateway\Metadata\Table
      */
    public function getMetaData();
    
    /**
      *   Sets the event dispatcher
      *
      *   @access public
      *   @return TableInterface
      *   @param EventDispatcher $dispatcher
      */
    public function setEventDispatcher(EventDispatcherInterface $dispatcher);
    
    
    /**
      *   Return the event dispatcher
      *
      *   @access public
      *   @return EventDispatcher
      */
    public function getEventDispatcher();
    
    /**
      *   Sets the result object that cloned on each select query
      *
      *   @access public
      *   @param Collection $col
      *   @return TableInterface
      */
    public function setResultSet(Collection $col);
    
    /**
      *  Return the prototype result collection
      *
      *  @access public
      *  @return Collection
      */
    public function getResultSet();
    
    
    /**
      *   Return the entity builder
      *
      *   @access public
      *   @return BuilderInterface $builder
      */
    public function getEntityBuilder();
    
    /**
      *  Sets the entity builder
      *
      *  @access public
      *  @param BuilderInterface $builder
      */
    public function setEntityBuilder(BuilderInterface $builder);
    
    
    /**
      *  Create a new instance of the querybuilder
      *
      *  @access public
      *  @return QueryBuilder
      */
    public function newQueryBuilder();
    
    
    /**
      *  Will convert db values to php types
      *  Uses doctrine column types
      *
      *  @access public
      *  @return mixed
      *  @param array $result
      */
    public function convertToPhp(array &$result);
    
    /**
      *  Fetch the last autoincrement id inserted
      *
      *  @access public
      *  @return integer | null
      */
    public function lastInsertId();
    
    /**
      *  Return the rows affected by last query
      *
      *  @access public
      *  @return integer the rows affected
      */
    public function rowsAffected();
    
}
/* End of File */