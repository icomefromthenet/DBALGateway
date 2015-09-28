<?php
namespace DBALGateway\Metadata;

use Doctrine\DBAL\Schema\Schema as DoctrineSchema;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Schema\Column;
use Doctrine\DBAL\Schema\SchemaException;

/**
  *  Modified to allow our own table objects
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 0.0.1
  */
class Schema extends DoctrineSchema
{
    
    /**
     * Create a new table
     *
     * @param  string $tableName
     * @return Table
     */
    public function createTable($tableName)
    {
        $table = new Table($tableName);
        $this->_addTable($table);

        foreach ($this->_schemaConfig->getDefaultTableOptions() as $name => $value) {
            $table->addOption($name, $value);
        }

        return $table;
    }
    
    
}
/* End of File */