<?php

namespace Lemonshot\CurveFitting\Solvers;

use Lemonshot\CurveFitting\Contracts\SolverInterface;
use Lemonshot\CurveFitting\Utils\MatrixOperations;

class LinearRegressionSolver extends AbstractSolver implements SolverInterface
{
    /**
     * Solve the curve fitting for the given dataset.
     *
     * @param  array  $dataset
     * @return array
     */
    public function solve(array $dataset): array
    {
        $xValues = array_map(function ($row) {
            return $row['x'];
        }, $dataset);
        $yValues = array_map(function ($row) {
            return $row['y'];
        }, $dataset);

        // Convert input arrays to matrix
        $xMatrix = MatrixOperations::createMatrixFromColumn($xValues);
        $yMatrix = MatrixOperations::createMatrixFromColumn($yValues);

        // Augment xMatrix with a column of ones
        $xMatrix = MatrixOperations::addColumn($xMatrix, 1);

        // Calculate X^T*X
        $xTx = MatrixOperations::multiply(
            MatrixOperations::transpose($xMatrix),
            $xMatrix
        );

        // Calculate (X^T*X)^-1
        $inverse = MatrixOperations::invert($xTx);

        // Calculate X^T*Y
        $xTy = MatrixOperations::multiply(
            MatrixOperations::transpose($xMatrix),
            $yMatrix
        );

        // Calculate (X^T*X)^-1 * X^T*Y = B
        $bMatrix = MatrixOperations::multiply($inverse, $xTy);

        // The resulting matrix B contains the slope and intercept
        // Note: the intercept is now in the second element
        $slope = $bMatrix[0][0];
        $intercept = $bMatrix[1][0];

        return [
            $slope,
            $intercept,
        ];
    }
}
