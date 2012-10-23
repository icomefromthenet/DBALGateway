<?php
namespace DBALGateway\Metadata\Builder;

use Doctrine\DBAL\Connection;
use DBALGateway\Metadata\ViewTable;

/**
  *  Object used to build View Tables
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 0.0.1
  */
class ViewTableBuilder extends TableBuilder
{
    /**
      *  Class constructor 
      */
    public function __construct()
    {
        $this->node = new ViewTable();
    }
    
}
/* End of File */