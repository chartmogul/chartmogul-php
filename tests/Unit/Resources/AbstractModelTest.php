<?php

use ChartMogul\Resource\AbstractModel;

class TestClassAbstractModel extends AbstractModel
{
    protected $a;
}

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
        $mock = $this->getMockBuilder(TestClassAbstractModel::class)
            ->setConstructorArgs([$in])
            ->onlyMethods([])
            ->getMock();

        $output = $mock->toArray();
        $this->assertEquals($out, $output);
    }
}
