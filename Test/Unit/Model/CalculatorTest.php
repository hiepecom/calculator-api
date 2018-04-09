<?php
namespace Rezolve\Calculator\Test\Unit\Model;

use Rezolve\Calculator\Model\Calculator;
use Magento\Framework\TestFramework\Unit\Autoloader\FactoryGenerator;

class CalculatorTest extends \PHPUnit_Framework_TestCase
{

    private $calculator;
    private $resultData;

    public function setUp()
    {
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->calculator = $objectManager->getObject('Rezolve\Calculator\Model\Calculator');
        $this->resultData = '{"status":"OK","result":69.122}';
    }

    public function testCalculator()
    {
        $this->assertEquals($this->resultData, $this->calculator->result(12.341555521, 56.78, 'add', 3));
    }
}
