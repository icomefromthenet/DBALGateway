<?php
namespace DBALGateway\Container;

use DBALGateway\TableInterface;
use Doctrine\DBAL\Query\QueryBuilder;
use DBALGateway\Exception as GatewayException;

class InsertContainer extends AbstractContainer
{
    public function where()
    {
        throw new GatewayException('where method not implemented during insert');
    }
}

/* End of File */