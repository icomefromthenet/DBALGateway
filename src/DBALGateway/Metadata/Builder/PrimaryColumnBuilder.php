<?php
namespace DBALGateway\Metadata\Builder;

use Doctrine\DBAL\Connection;
use DBALGateway\Metadata\PKColumn;
use DBALGateway\Metadata\Node;

/**
  *  Object to build Primary Key Columns
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 0.0.1
  */
class PrimaryColumnBuilder extends ColumnBuilder
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