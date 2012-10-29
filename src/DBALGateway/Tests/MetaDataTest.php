<?php
namespace DBALGateway\Tests;

use DBALGateway\Tests\Base\TestsWithFixture;
use DBALGateway\Metadata\Table;
use DBALGateway\Metadata\VColumn;
use Doctrine\DBAL\Types\Type;

class MetaDataTest extends TestsWithFixture
{
    
    public function testVirtualColumnsClassExists()
    {
        $col = new VColumn('myname',Type::getType('integer'));
        
        $this->assertInstanceOf('\DBALGateway\Metadata\VColumn',$col);
        $this->assertInstanceOf('Doctrine\DBAL\Schema\Column',$col);
    }
    
    
    public function testVirtualColumns()
    {
        $table = new Table('users');
        
        $table->addVirtualColumn('mycolumn','integer');
        
        $virtual = $table->getVirtualColumns();
        
        $this->assertArrayHasKey('mycolumn',$virtual);
        $this->assertInstanceOf('\DBALGateway\Metadata\VColumn',\end($virtual));
    }
    
    
}
/* End of Class */