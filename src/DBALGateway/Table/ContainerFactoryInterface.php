<?php
namespace DBALGateway\Table;

use Doctrine\DBAL\Query\QueryBuilder;
use DBALGateway\Container\DeleteContainer;
use DBALGateway\Container\InsertContainer;
use DBALGateway\Container\UpdateContainer;
use DBALGateway\Container\SelectContainer;

/**
  *  Factory methods for containers (select,insert,update,delete)
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 0.0.1
  */
interface ContainerFactoryInterface
{
    
    /**
      *   Create a new select query container
      *   
      *   @access public
      *   @return SelectContainer
      */
    public function selectQuery();
    
    
    /**
      *  Create a new delete query container
      *  
      *  @access public
      *  @return DeleteContainer
      */
    public function deleteQuery();
    
    /**
      *  Create a new insert query container
      *  
      *  @access public
      *  @return InsertContainer
      */
    public function insertQuery();
    
    /**
      *   Create a new update query container
      *   
      *   @access public
      *   @return UpdateContainer
      */
    public function updateQuery();
    
    
}
/* End of File */