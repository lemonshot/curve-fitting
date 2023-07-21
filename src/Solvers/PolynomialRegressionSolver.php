<?php

namespace Lemonshot\CurveFitting\Solvers;

use Lemonshot\CurveFitting\Contracts\SolverInterface;
use Lemonshot\CurveFitting\Utils\MatrixOperations;
use InvalidArgumentException;

class PolynomialRegressionSolver extends AbstractSolver implements SolverInterface
{
    /**
     * Degree of the polynomial
     *
     * @var int
     */
    protected $degree;

    /**
     * Create a new PolynomialRegressionSolver instance.
     *
     * @param  int  $degree
     * @return void
     */
    public function __construct(int $degree)
    {
        $this->degree = $degree;
    }

    /**
     * Solve the curve fitting for the given dataset.
     *
     * @param  array  $dataset
     * @return array
     */
    public function solve(array $dataset): array
    {
        if (empty($dataset)) {
            throw new InvalidArgumentException('Dataset cannot be empty.');
        }

        foreach ($dataset as $data) {
            if (!isset($data['x']) || !isset($data['y'])) {
                throw new InvalidArgumentException('Each data point must be an associative array with "x" and "y" keys.');
            }
        }

        // Create augmented matrix
        $augmentedMatrix = $this->createAugmentedMatrix($dataset);

        // Solve the system of linear equations
        try {
            $coefficients = MatrixOperations::gaussianElimination($augmentedMatrix);
        } catch (InvalidArgumentException $e) {
            // Re-throw exception
            throw $e;
        }

        return $coefficients;
    }

    /**
     * Create the augmented matrix for the polynomial regression.
     *
     * @param  array  $dataset
     * @return array
     */
    protected function createAugmentedMatrix(array $dataset): array
    {
        $n = count($dataset);

        // Initialize sums
        $x = array_fill(0, ($this->degree * 2) + 1, 0);
        $y = array_fill(0, $this->degree + 1, 0);

        // Calculate the sums
        for ($i = 0; $i <= $this->degree * 2; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $x[$i] += pow($dataset[$j]['x'], $i);
            }
        }

        for ($i = 0; $i <= $this->degree; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $y[$i] += pow($dataset[$j]['x'], $i) * $dataset[$j]['y'];
            }
        }

        // Create augmented matrix
        $augmentedMatrix = [];
        for ($i = 0; $i <= $this->degree; $i++) {
            for ($j = 0; $j <= $this->degree; $j++) {
                $augmentedMatrix[$i][$j] = $x[$i + $j];
            }
            $augmentedMatrix[$i][] = $y[$i];
        }

        return $augmentedMatrix;
    }
}
