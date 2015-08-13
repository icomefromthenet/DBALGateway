<?php
namespace DBALGateway\Container;

use DBALGateway\TableInterface;
use Doctrine\DBAL\Query\QueryBuilder;

class DeleteContainer extends AbstractContainer
{
    /**
      *  Starts the internal Query 
      */
    public function start()
    {
        $this->query->delete($this->gateway->getMetaData()->getName(),$this->gateway->getTableQueryAlias());
        return $this->query;
    }
        
}
/* End of File */