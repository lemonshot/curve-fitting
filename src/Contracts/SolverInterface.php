<?php

namespace Lemonshot\CurveFitting\Contracts;

interface SolverInterface
{
    /**
     * Solve the curve fitting for the given dataset.
     *
     * @param  array  $dataset
     * @return array
     */
    public function solve(array $dataset): array;
}
