<?php
namespace DBALGateway\Tests;

use DBALGateway\Tests\Base\TestsWithFixture;
use DBALGateway\Tests\Base\Mock\MockBuilder;


class AliasBuilderTest extends TestsWithFixture
{
    
    public function testMockBuilder()
    {
        
        $oBuilder = new MockBuilder();
        $oBuilder->setTableQueryAlias('a');
        
        
        $aResult = array(
            'a_id'    =>  1
            ,'a_field' => 'myfield'
        );
        
        
        $oClass = $oBuilder->build($aResult);
        
        $this->assertEquals($aResult['a_id'],$oClass->id);
        $this->assertEquals($aResult['a_field'],$oClass->field);
        
    }
    
    
}
/* End of class */