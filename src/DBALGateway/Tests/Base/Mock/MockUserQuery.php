<?php
namespace DBALGateway\Tests\Base\Mock;

use DBALGateway\Query\AbstractQuery;
use DateTime;

class MockUserQuery extends AbstractQuery
{
    
    public function filterByUser($id)
    {
        $this->where('id = :id')->setParameter('id', $id, $this->getGateway()->getMetaData()->getColumn('id')->getType());
        return $this;
    }
    
    public function filterByUsername($name)
    {
        $this->where('username = :username')->setParameter('username', $id, $this->getGateway()->getMetaData()->getColumn('username')->getType());
        
        return $this;
    }
    
    
    public function filterByDateCreated(DateTime $created)
    {
        $this->where('dte_created = :dte_created')->setParameter('dte_created', $id, $this->getGateway()->getMetaData()->getColumn('dte_created')->getType());
        
        return $this;
    }
    
    public function filterByDateUpdated(DateTime $updated)
    {
        $this->where('dte_updated = :dte_updated')->setParameter('dte_updated', $id, $this->getGateway()->getMetaData()->getColumn('dte_updated')->getType());
        
        return $this;
    }
    
}
/* End of File */