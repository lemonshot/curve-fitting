<?php

namespace Lemonshot\CurveFitting\Fitters;

use Lemonshot\CurveFitting\Contracts\SolverInterface;
use InvalidArgumentException;

class CurveFitter
{
    /**
     * The curve fitting solver.
     *
     * @var SolverInterface
     */
    protected $solver;

    /**
     * Create a new CurveFitter instance.
     *
     * @param  SolverInterface  $solver
     * @return void
     */
    public function __construct(SolverInterface $solver)
    {
        $this->solver = $solver;
    }

    /**
     * Fit a curve to the given dataset.
     *
     * @param  array  $dataset
     * @return array
     */
    public function fit(array $dataset): array
    {
        if (empty($dataset)) {
            throw new InvalidArgumentException('Dataset cannot be empty.');
        }

        foreach ($dataset as $data) {
            if (!isset($data['x']) || !isset($data['y'])) {
                throw new InvalidArgumentException('Each data point must be an associative array with "x" and "y" keys.');
            }
        }

        return $this->solver->solve($dataset);
    }
}
