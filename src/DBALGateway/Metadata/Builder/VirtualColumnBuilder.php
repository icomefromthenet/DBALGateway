<?php
namespace DBALGateway\Metadata\Builder;

use Doctrine\DBAL\Connection;
use DBALGateway\Metadata\VColumn;
use DBALGateway\Metadata\Node;

/**
  *  Object to build Virtual Columns
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 0.0.1
  */
class VirtualColumnBuilder extends ColumnBuilder
{
    /**
      *  Class constructor 
      */
    public function __construct()
    {
        $this->node = new VColumn();
    }
    
    
}
/* End of File */