<?php
namespace Migration\Components\Migration\Entities;

use Doctrine\DBAL\Connection,
    Doctrine\DBAL\Schema\AbstractSchemaManager as Schema,
    Migration\Components\Migration\EntityInterface,
    Doctrine\DBAL\Schema\Schema as SchemaBuilder;

class init_schema implements EntityInterface
{

    protected function buildSchema(SchemaBuilder $builder)
    {
        # create demo table
        $myTable = $builder->createTable("users");
        $myTable->addColumn("id", "integer", array("unsigned" => true));
        $myTable->addColumn("username", "string", array("length" => 32));
        $myTable->addColumn("first_name", "string", array("length" => 45));
        $myTable->addColumn("last_name", "string", array("length" => 45));
        $myTable->addColumn("dte_created", "datetime");
        $myTable->addColumn("dte_updated", "datetime");
        $myTable->setPrimaryKey(array("id"));
        
        return $builder;
        
    }


    public function up(Connection $db, Schema $sc)
    {
        $builder = new SchemaBuilder();
        $this->buildSchema($builder);
        
        $build = $builder->toSql($db->getDatabasePlatform());
        
        foreach($build as $query) {
            $db->exec($query);    
        }
        
    }

    public function down(Connection $db, Schema $sc)
    {
        $builder = new SchemaBuilder();
        $this->buildSchema($builder);
        
        $drop = $builder->toDropSql($db->getDatabasePlatform());
        
        foreach($build as $query) {
            $db->exec($query);    
        }
    }


}
/* End of File */
