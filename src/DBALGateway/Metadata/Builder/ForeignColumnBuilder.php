<?php
namespace DBALGateway\Metadata\Builder;

use Doctrine\DBAL\Connection;
use DBALGateway\Metadata\FKColumn;
use DBALGateway\Metadata\Node;

/**
  *  Object to build Foreign Key Columns
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 0.0.1
  */
class ForeignColumnBuilder extends ColumnBuilder
{
    /**
      *  Class constructor 
      */
    public function __construct()
    {
        $this->node = new FKColumn();
    }
    
    
}
/* End of File */