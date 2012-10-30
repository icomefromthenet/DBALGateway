<?php
namespace DBALGateway\Table;

use DBALGateway\Exception as GatewayException;
use DBALGateway\Table\TableEvent;
use DBALGateway\Table\TableEvents;
use DBALGateway\Container\DeleteContainer;
use DBALGateway\Container\InsertContainer;
use DBALGateway\Container\SelectContainer;
use DBALGateway\Container\UpdateContainer;
use DBALGateway\Builder\BuilderInterface;
use DBALGateway\Metadata\Table;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
  *  Base Class for table gateways
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 0.0.1
  */
class AbstractTable implements ContainerFactoryInterface, TableInterface
{
    /**
      *  @var integer 
      */
    protected $last_insert_id;
    
    /**
      *  @var string the table name 
      */
    protected $table_name;
    
    /**
      *  @var EventDispatcherInterface
      */
    protected $event_dispatcher;
    
    /**
      *  @var Doctrine\DBAL\Connection 
      */
    protected $adapter;
    
    /**
      *  @var  DBALGateway\Container\ContainerAbstract
      */
    protected $head;
    
    /**
      *   @var DBALGateway\Metadata\Table
      */
    protected $meta;
    
    /**
      *  @var Doctrine\DBAL\Collections\Collection
      */
    protected $collection_proto;
    
    /**
      *  @var DBALGateway\Builder\BuilderInterface
      */
    protected $entity_builder;
    
    /**
      *  Class Constructor 
      */
    public function __construct($table_name, Connection $adapter, EventDispatcherInterface $event, Table $meta = null, Collection $result_set = null, BuilderInterface $builder = null)
    {
        # Need a table name for the metadata feature.
        $this->table_name          = $table_name;
        $this->last_insert_id      = null;
        
        $this->setAdapater($adapter);
        $this->setEventDispatcher($event);
        
        if($meta !== null) {
            $this->setMetaData($meta);    
        }
        
        if($result_set !== null) {
            $this->setResultSet($result_set);    
        } else {
            $this->setResultSet(new ArrayCollection());
        }
        
        if($builder !== null) {
            $this->setEntityBuilder($builder);
        }
        
        $this->initilize();
        
        # table name and the meta-data name match?
        if($this->table_name !== $this->meta->getName()) {
            throw new GatewayException('The table name and the meta-data name do not match');
        }
        
    }
    
    
    
    //------------------------------------------------------------------
    # Table Interface
    
     /**
      *  Initilize the table gateway
      *
      *  @return TableInterface
      *  @access public
      */
    public function initilize()
    {
        $this->event_dispatcher->dispatch(TableEvents::PRE_INITIALIZE,new TableEvent($this));
        
        $this->event_dispatcher->dispatch(TableEvents::POST_INITIALIZE,new TableEvent($this));
        
        $this->clear();
        return $this;
    }
    
    /**
      *  Execute a select query, return many rows
      *  Requires a query be setup via the factory
      *
      *  
      *  @return mixed
      *  @access public
      */
    public function find()
    {
        $this->event_dispatcher->dispatch(TableEvents::PRE_SELECT,new TableEvent($this));
        
        $result = array();
        
        try {
            
            $stm = $this->head->getQuery()->execute();
            
            $result = clone $this->collection_proto;    
            
            while($data = $stm->fetch(\PDO::FETCH_ASSOC)) {
                
                $this->convertToPhp($data);
                
                if($this->entity_builder instanceof BuilderInterface) {
                    $data = $this->entity_builder->build($data);
                } 
                
                $result->add($data);
                
            }
            
        } catch(DBALException $e) {
            throw new GatewayException($e->getMessage());
        }
        
        
        $this->event_dispatcher->dispatch(TableEvents::POST_SELECT,new TableEvent($this,$result));
        
        $this->clear();
        return $result;
    }
    
     /**
      *  Execute a select query, return single row
      *  Requires a query be setup via the factory
      *  
      *  @return mixed
      *  @access public
      */
    public function findOne()
    {
        $this->event_dispatcher->dispatch(TableEvents::PRE_SELECT,new TableEvent($this));
        $result = null;
        
        try {
            
            $stm = $this->head->getQuery()->execute();

            if($result = $stm->fetch(\PDO::FETCH_ASSOC)) {
                $this->convertToPhp($result);
                
                if($this->entity_builder instanceof BuilderInterface) {
                    $data = $this->entity_builder->build($result);
                }     
            }
            
        } catch(DBALException $e) {
            throw new GatewayException($e->getMessage());
        }
        
        $this->event_dispatcher->dispatch(TableEvents::POST_SELECT,new TableEvent($this,$result));
        
        $this->clear();
        
        return $result;   
        
    }
    
    /**
      *  Execute an insert query, return single row
      *  Requires a query be setup via the factory
      *  
      *  @return integer
      *  @access public
      */
    public function insert()
    {
        $result = false;
        
        $this->event_dispatcher->dispatch(TableEvents::PRE_INSERT,new TableEvent($this));
        
        try {
            
            if($this->adapter->insert($this->meta->getName(), $this->head->getColumns(), $this->head->getTypeInfo()) > 0) {
                $result = true;
                $this->last_insert_id = $this->adapter->lastInsertId();     
            }
            
        } catch(DBALException $e) {
            throw new GatewayException($e->getMessage());
        }
              
        
        $this->event_dispatcher->dispatch(TableEvents::POST_INSERT,new TableEvent($this,$result));
        
        $this->clear();
        return $result;
    }
    
    /**
      *  Execute a delete query, return single row
      *  Requires a query be setup via the factory
      *  
      *  @return mixed
      *  @access public
      */
    public function delete()
    {
        $result = false;
     
        $this->event_dispatcher->dispatch(TableEvents::PRE_DELETE,new TableEvent($this));
        
        try {
            
            if($this->head->getQuery()->execute() > 0) {
                $result = true;
            }
            
        } catch(DBALException $e) {
            throw new GatewayException($e->getMessage());
        }
        
        $this->event_dispatcher->dispatch(TableEvents::POST_DELETE,new TableEvent($this,$result));
        
        $this->clear();
        return $result;
    }
    
    /**
      *  Execute a update query, return single row
      *  Requires a query be setup via the factory
      *  
      *  @return mixed
      *  @access public
      */
    public function update()
    {
        $result = null;
        
        $this->event_dispatcher->dispatch(TableEvents::PRE_UPDATE,new TableEvent($this));
        
        try {
            
            if($this->head->getQuery()->execute() > 0) {
                $result = true;
            }
            
            
        } catch(DBALException $e) {
            throw new GatewayException($e->getMessage());
        }
        
        $this->event_dispatcher->dispatch(TableEvents::POST_UPDATE,new TableEvent($this,$result));
        
        $this->clear();
        
        return $result;
    }
    
    /**
      *  Clears the query container;
      *
      *  @access public
      *  @return TableInterface
      */
    public function clear()
    {
        $this->head = null;
    }
    
    /**
      *  Return the query container
      *
      *  @access public
      *  @return DBALGateway\Container\AbstractContainer
      */
    public function head()
    {
        return $this->head;
    }
    
    
    /**
      *   Returns the doctrine DBAL connection
      *
      *   @access public
      *   @return Doctrine\DBAL\Connection
      */
    public function getAdapater()
    {
        return $this->adapter;
    }
    
    /**
      *  Sets the geteway with doctrine dbal
      *
      *  @access public
      *  @param Connection $conn
      *  @return TableInterface
      */
    public function setAdapater(Connection $conn)
    {
        $this->adapter = $conn;
        return $this;
    }
    
    /**
      *   Sets the table metadata
      *
      *   @access public
      *   @return TableInterface
      *   @param DBALGateway\Metadata\Table metadata
      */    
    public function setMetaData(Table $metadata)
    {
        $this->meta = $metadata;
        return $this;
    }
    
    /**
      *   Fetches the table metadata
      *
      *   @access public
      *   @return DBALGateway\Metadata\Table
      */
    public function getMetaData()
    {
        return $this->meta;
    }
    
    /**
      *   Sets the event dispatcher
      *
      *   @access public
      *   @return TableInterface
      *   @param EventDispatcherInterface $dispatcher
      */
    public function setEventDispatcher(EventDispatcherInterface $dispatcher)
    {
        $this->event_dispatcher = $dispatcher;
        
        return $this;
    }
    
    
    /**
      *   Return the event dispatcher
      *
      *   @access public
      *   @return EventDispatcherInterface
      */
    public function getEventDispatcher()
    {
        return $this->event_dispatcher;
    }
    
    
      /**
      *   Sets the result object that cloned on each select query
      *
      *   @access public
      *   @param Collection $col
      *   @return TableInterface
      */
    public function setResultSet(Collection $col)
    {
        $this->collection_proto = $col;
    }
    
    /**
      *  Return the prototype result collection
      *
      *  @access public
      *  @return Collection
      */
    public function getResultSet()
    {
        return $this->collection_proto;
    }
    
    /**
      *   Return the entity builder
      *
      *   @access public
      *   @return BuilderInterface $builder
      */
    public function getEntityBuilder()
    {
        return $this->entity_builder;
    }
    
    /**
      *  Sets the entity builder
      *
      *  @access public
      *  @param BuilderInterface $builder
      */
    public function setEntityBuilder(BuilderInterface $builder)
    {
        $this->entity_builder = $builder;
    }
    
    /**
      *  Create a new instance of the querybuilder
      *
      *  @access public
      *  @return QueryBuilder
      */
    public function newQueryBuilder()
    {
        throw new Exception('not implemented');
    }
    
    
    /**
      *  Will convert db values to php types
      *  Uses doctrine column types
      *
      *  @access public
      *  @return mixed
      *  @param array $result
      */
    public function convertToPhp(array &$result)
    {
        $platform = $this->adapter->getDatabasePlatform();
        $columns = $this->meta->getCombinedColumns();
        
        foreach($columns as $column) {
            $name = $column->getName();
            if(isset($result[$name]) === true) {
                $result[$name] = $column->getType()->convertToPHPValue($result[$name],$platform);
            }
        }
        
        return $result;
    }
    
    
    /**
      *  Fetch the last autoincrement id inserted
      *
      *  @access public
      *  @return integer | null
      */
    public function lastInsertId()
    {
        return $this->last_insert_id;
    }
    
    //------------------------------------------------------------------
    # Container Factory Interface
    
    
    /**
      *   Create a new select query container
      *   
      *   @access public
      *   @return SelectContainer
      */
    public function selectQuery()
    {
        $this->head = new SelectContainer($this,$this->newQueryBuilder());
        
        return $this->head;
    }
    
    
    /**
      *  Create a new delete query container
      *  
      *  @access public
      *  @return DeleteContainer
      */
    public function deleteQuery()
    {
        $this->head = new DeleteContainer($this,$this->newQueryBuilder());
        
        return $this->head;
    }
    
    /**
      *  Create a new insert query container
      *  
      *  @access public
      *  @return InsertContainer
      */
    public function insertQuery()
    {
        $this->head = new InsertContainer($this,null);
        
        return $this->head;
    }
    
    /**
      *   Create a new update query container
      *   
      *   @access public
      *   @return UpdateContainer
      */
    public function updateQuery()
    {
        $this->head = new UpdateContainer($this,$this->newQueryBuilder());
        
        return $this->head;
    }
    
    //------------------------------------------------------------------
    
    
}

/* End of File */