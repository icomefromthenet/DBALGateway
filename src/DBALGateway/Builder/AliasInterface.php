<?php
namespace DBALGateway\Builder;

/**
  *  Interface for alias entity builders provide alias properties
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 0.0.1
  */
interface AliasInterface
{
    /**
     * Return the table query alias.
     * 
     * @return string the assigned alias or empty string
     */ 
    public function getTableQueryAlias();
    
    
    /**
     * Fetch the table query alias
     * 
     * @return void
     * @param string    $sAlias The query alias
     */ 
    public function setTableQueryAlias($sAlias);
    
    
}

/* End of File */