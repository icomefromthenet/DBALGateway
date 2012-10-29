<?php
namespace DBALGateway\Tests\Base\Mock;

use DBALGateway\Table\AbstractTable;

class MockUserTableGateway extends AbstractTable
{
    /**
      *  Create a new instance of the querybuilder
      *
      *  @access public
      *  @return QueryBuilder
      */
    public function newQueryBuilder()
    {
        return new MockUserQuery($this->adapter,$this);
    }

}
/* End of File */