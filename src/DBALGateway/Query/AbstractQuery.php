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
abstract class AbstractQuery extends QueryBuilder implements QueryInterface
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
    
    /**
     * Get the complete SQL string formed by the current specifications of this QueryBuilder.
     * !!!Overidden here to add support for limits and offset to delete and update methods!!!
     *
     * @return string The sql query string.
     */
    public function getSQL()
    {
        # force use of the cache query, (don't change as parent sets STATE to clean)
        if($this->getState() === self::STATE_CLEAN) {
            return parent::getSQL();
        }

        $sql = parent::getSQL();
        switch ($this->getType()) {
            case self::DELETE:
            case self::UPDATE:
                
                # process orderby
                $order = $this->getQueryPart('orderBy');
                if(count($order) !== 0) {
                    $sql .=' ORDER BY ' . implode(', ', $order);
                }
                
                if($this->getMaxResults() !== null || $this->getFirstResult() !== null) {
                    $sql = $this->getConnection()->getDatabasePlatform()->modifyLimitQuery($sql, $this->getMaxResults(), $this->getFirstResult());
                }
            break;
        }

        return $sql;
    }
    
    
}
/* End of Class */