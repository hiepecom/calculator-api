<?php
namespace Rezolve\Calculator\Model;

use Rezolve\Calculator\Api\CalculatorInterface;

/**
 * Class Calculator
 * @package Rezolve\Calculator\Model
 */
class Calculator implements CalculatorInterface
{

    const RESPONSE_RESULT_FAIL = 'Fail';
    const RESPONSE_RESULT_SUCCESS = 'OK';

    const RESPONSE_RESULT_FAIL_DIVIDE_BY_ZERO = 'Can not divide by zero';
    const RESPONSE_RESULT_FAIL_INVALID_OPERATOR = 'Please enter a valid operator';
    const RESPONSE_RESULT_LEFT_FAIL_INVALID_NUMBER = 'Please enter a left valid number';
    const RESPONSE_RESULT_RIGHT_FAIL_INVALID_NUMBER = 'Please enter a right valid number';
    const RESPONSE_RESULT_PRECISION_FAIL_INVALID_INTERGER = 'Please enter a precision valid interger';

    const OPERATOR_ADD = 'add';
    const OPERATOR_SUBTRACT = 'subtract';
    const OPERATOR_MULTIPLY = 'multiply';
    const OPERATOR_DIVIDE = 'divide';
    const OPERATOR_POWER = 'power';

    const RESPONSE_LABEL_STATUS = 'status';
    const RESPONSE_LABEL_RESULT = 'result';
    const RESPONSE_LABEL_MESSAGE = 'mgs';

    /**
     * @var \Magento\Framework\Validator\IntUtils
     */
    private $intUtils;
    /**
     * @var \Magento\Framework\Validator\FloatUtils
     */
    private $floatUtils;

    /**
     * Calculator constructor.
     * @param \Magento\Framework\Validator\IntUtils $intUtils
     * @param \Magento\Framework\Validator\FloatUtils $floatUtils
     */
    public function __construct(
        \Magento\Framework\Validator\IntUtils $intUtils,
        \Magento\Framework\Validator\FloatUtils $floatUtils
    ) {
        $this->intUtils         = $intUtils;
        $this->floatUtils       = $floatUtils;
    }

    /**
     * @param $left
     * @param $right
     * @param $precision
     * @return array|bool
     */
    public function isValid($left, $right, $precision)
    {
        if ($this->floatUtils->isValid($left) === false) :
            $result = [
                self::RESPONSE_LABEL_STATUS => self::RESPONSE_RESULT_FAIL,
                self::RESPONSE_LABEL_MESSAGE => self::RESPONSE_RESULT_LEFT_FAIL_INVALID_NUMBER
            ];
        elseif ($this->floatUtils->isValid($right) === false) :
            $result = [
                self::RESPONSE_LABEL_STATUS => self::RESPONSE_RESULT_FAIL,
                self::RESPONSE_LABEL_MESSAGE => self::RESPONSE_RESULT_RIGHT_FAIL_INVALID_NUMBER
            ];
        elseif ($this->intUtils->isValid($precision) === false) :
            $result = [
                self::RESPONSE_LABEL_STATUS => self::RESPONSE_RESULT_FAIL,
                self::RESPONSE_LABEL_MESSAGE => self::RESPONSE_RESULT_PRECISION_FAIL_INVALID_INTERGER
            ];
        else :
            $result = true;
        endif;

        return $result;
    }

    /**
     * @param float $left
     * @param float $right
     * @param string $operator
     * @param int $precision
     * @return string
     */
    public function result($left, $right, $operator, $precision = 2)
    {
        $result = $this->isValid($left, $right, $precision);
        if ($result === true) :
            switch ($operator) {
                case self::OPERATOR_ADD:
                    $result = [
                        self::RESPONSE_LABEL_STATUS => self::RESPONSE_RESULT_SUCCESS,
                        self::RESPONSE_LABEL_RESULT => ($left + $right)
                    ];
                    break;
                case self::OPERATOR_SUBTRACT:
                    $result = [
                        self::RESPONSE_LABEL_STATUS => self::RESPONSE_RESULT_SUCCESS,
                        self::RESPONSE_LABEL_RESULT => ($left - $right)
                    ];
                    break;
                case self::OPERATOR_MULTIPLY:
                    $result = [
                        self::RESPONSE_LABEL_STATUS => self::RESPONSE_RESULT_SUCCESS,
                        self::RESPONSE_LABEL_RESULT => ($left * $right)
                    ];
                    break;
                case self::OPERATOR_DIVIDE:
                    if ($right == 0) :
                        $result = [
                            self::RESPONSE_LABEL_STATUS => self::RESPONSE_RESULT_FAIL,
                            self::RESPONSE_LABEL_RESULT => self::RESPONSE_RESULT_FAIL_DIVIDE_BY_ZERO
                        ];
                    else :
                        $result = [
                            self::RESPONSE_LABEL_STATUS => self::RESPONSE_RESULT_SUCCESS,
                            self::RESPONSE_LABEL_RESULT => $left / $right
                        ];
                    endif;
                    break;
                case self::OPERATOR_POWER:
                    $result = [
                        self::RESPONSE_LABEL_STATUS => self::RESPONSE_RESULT_SUCCESS,
                        self::RESPONSE_LABEL_RESULT => pow($left, $right)
                    ];
                    break;
                default:
                    $result = [
                        self::RESPONSE_LABEL_STATUS => self::RESPONSE_RESULT_FAIL,
                        self::RESPONSE_LABEL_MESSAGE => self::RESPONSE_RESULT_FAIL_INVALID_OPERATOR
                    ];
                    break;
            }
        endif;

        if ($result[self::RESPONSE_LABEL_STATUS] == self::RESPONSE_RESULT_SUCCESS) :
            $result[self::RESPONSE_LABEL_RESULT] = round($result[self::RESPONSE_LABEL_RESULT], $precision);
        endif;

        return \Zend_Json::encode($result);
    }
}
