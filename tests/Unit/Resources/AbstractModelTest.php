<?php

use ChartMogul\Resource\AbstractModel;

class AbstractModelTest extends \PHPUnit\Framework\TestCase
{



    public static function provider()
    {
        return [
            [ ['a' => 'b'], ['a' => 'b'] ],
            [ ['a' => null], [] ],
            [ ['a' => (object) [ 'b' => 'c' ]], ['a' => [ 'b' => 'c' ]]],
            [ ['a' => [ 'b' => (object) ['c' => 'd'] ]], ['a' => [ 'b' => ['c' => 'd'] ]]],
        ];
    }
    /**
     * @dataProvider provider
     */
    public function testObjectToArray($in, $out)
    {
        $mock = $this->getMockBuilder(AbstractModel::class)
            ->setConstructorArgs([$in])
            ->getMock();

        $output = $mock->toArray();
        $this->assertEquals($out, $output);
    }
}
