<?php
namespace Rezolve\Calculator\Api;

interface CalculatorInterface
{
    /**
     * @api
     * @param mixed $left
     * @param mixed $right
     * @param mixed $operator
     * @param mixed $precision
     * @return mixed
     */

    public function result($left, $right, $operator, $precision = 2);
}
