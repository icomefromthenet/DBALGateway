<?php
namespace  DBALGateway\Query;

use DBALGateway\Table\TableInterface;

/**
  *  Interface that all queries need to implement
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 0.0.1
  */
interface QueryInterface
{
    /**
      *  Return the table gateway
      *
      *  @access public
      *  @return TableInterface
      */
    public function end();

    /**
      *  Return the assigned table gateway
      *
      *  @access public
      *  @return TableInterface
      */
    public function getGateway();
    
    /**
      *  Set the assigned table gateway
      *
      *  @access public
      *  @return QueryInterface
      *  @param TableInterface $table
      */
    public function setGateway(TableInterface $table);
    
    
    /**
     * Sets a default alias. 
     * 
     * @param   string    $sAlias the default alias to use
     * @return  void
     */
    public function setDefaultAlias($sAlias);
    
    /**
     * Return the default alias
     * 
     * @return  string  The default alias
     */ 
    public function getDefaultAlias();
    
    
}
/* End of File */