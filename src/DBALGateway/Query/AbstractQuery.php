<?php
namespace DBALGateway\Query;

use Doctrine\DBAL\Query\QueryBuilder;
use DBALGateway\Table\TableInterface;
use Doctrine\DBAL\Connection;
use DBALGateway\Exception as QueryException;

/**
  *  Base Class for all QueryObjects
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 0.0.1
  */
class AbstractQuery extends QueryBuilder implements QueryInterface
{
    /**
      *  @var TableInterface
      */
    protected $table_gateway;
 
 
    /**
      *  Class Constructor
      *
      *  @access public
      *  @param Connection $connection
      *  @param TableInterface $table
      */
    public function __construct(Connection $connection,TableInterface $table)
    {
        $this->table_gateway = $table;
        parent::__construct($connection);
    }
  
 
    /**
      *  Return the table gateway
      *
      *  @access public
      *  @return TableInterface
      */
    public function end()
    {
        return $this->table_gateway;
    }

    /**
      *  Return the assigned table gateway
      *
      *  @access public
      *  @return TableInterface
      */
    public function getGateway()
    {
        return $this->table_gateway;
    }
    
    /**
      *  Set the assigned table gateway
      *
      *  @access public
      *  @return QueryInterface
      *  @param TableInterface $table
      */
    public function setGateway(TableInterface $table)
    {
        $this->table_gateway;
    }

    
    /**
      *  Alias to the QueryBuilder::setMaxResults()
      *
      *  @access public
      *  @return AbstractQuery
      *  @param integer the limit
      */
    public function limit($limit)
    {
        if(is_integer($limit) === false || (int) $limit < 0) {
            throw new QueryException('Query LIMIT must be and integer and greater than 0');
        }
     
        $this->setMaxResults($limit);
        
        return $this;
    }
    
    /**
      *  Alias to the QueryBuilder::setFirstResult()
      *
      *  @access public
      *  @return AbstractQuery
      *  @param integer $offset defaults to 0
      */
    public function offset($offset = 0)
    {
        if(is_integer($offset) === false) {
            throw new QueryException('Query OFFSET must be and integer');
        }
        
        $this->setFirstResult($offset);
        
        return $this;
    }
    
    
}
/* End of Class */