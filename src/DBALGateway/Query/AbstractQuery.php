<?php
namespace DBALGateway\Query;

use Doctrine\DBAL\Query\QueryBuilder;
use DBALGateway\Table\TableInterface;
use Doctrine\DBAL\Connection;

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

    
    
}
/* End of Class */