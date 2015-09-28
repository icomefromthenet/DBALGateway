<?php
namespace DBALGateway\Tests\Base\Mock;

use DBALGateway\Table\SchemaAwareTable;

class MockUserTableGateway extends SchemaAwareTable
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