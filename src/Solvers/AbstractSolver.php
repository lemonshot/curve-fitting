<?php

namespace Lemonshot\CurveFitting\Solvers;

use Lemonshot\CurveFitting\Contracts\SolverInterface;

abstract class AbstractSolver implements SolverInterface
{
    /**
     * Dataset to perform curve fitting on.
     *
     * @var array
     */
    protected $dataset;

    /**
     * Set the dataset.
     *
     * @param  array  $dataset
     * @return self
     */
    public function setDataset(array $dataset): self
    {
        $this->dataset = $dataset;

        return $this;
    }

    /**
     * Solve the curve fitting for the given dataset.
     *
     * @param  array  $dataset
     * @return array
     */
    abstract public function solve(array $dataset): array;
}
