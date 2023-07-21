<?php

namespace Lemonshot\CurveFitting\Utils;

use InvalidArgumentException;

class MatrixOperations
{
    public static function add(array $matrixA, array $matrixB): array
    {
        $result = [];

        foreach ($matrixA as $i => $row) {
            foreach ($row as $j => $value) {
                $result[$i][$j] = $value + $matrixB[$i][$j];
            }
        }

        return $result;
    }

    public static function subtract(array $matrixA, array $matrixB): array
    {
        $result = [];

        foreach ($matrixA as $i => $row) {
            foreach ($row as $j => $value) {
                $result[$i][$j] = $value - $matrixB[$i][$j];
            }
        }

        return $result;
    }

    public static function multiply(array $matrixA, array $matrixB): array
    {
        if (empty($matrixA) || empty($matrixB)) {
            throw new \InvalidArgumentException('Both matrices must be non-empty arrays');
        }

        $result = [];

        $bRowCount = count($matrixB);
        $bColumnCount = is_array($matrixB[0]) ? count($matrixB[0]) : 0;

        if ($bColumnCount == 0) {
            throw new \InvalidArgumentException('Matrix B must have at least one column');
        }

        foreach ($matrixA as $i => $row) {
            for ($j = 0; $j < $bColumnCount; $j++) {
                $result[$i][$j] = 0;
                for ($k = 0; $k < $bRowCount; $k++) {
                    $result[$i][$j] += $matrixA[$i][$k] * $matrixB[$k][$j];
                }
            }
        }

        return $result;
    }

    public static function transpose(array $matrix): array
    {
        $result = [];

        foreach ($matrix as $i => $row) {
            foreach ($row as $j => $value) {
                $result[$j][$i] = $value;
            }
        }

        return $result;
    }

    public static function gaussianEliminationForInverse(array $matrix): array
    {
        $n = count($matrix);

        for ($i = 0; $i < $n; $i++) {
            // Search for maximum in this column
            $maxEl = abs($matrix[$i][$i]);
            $maxRow = $i;
            for ($k = $i + 1; $k < $n; $k++) {
                if (abs($matrix[$k][$i]) > $maxEl) {
                    $maxEl = abs($matrix[$k][$i]);
                    $maxRow = $k;
                }
            }

            // Swap maximum row with current row
            for ($k = $i; $k < 2 * $n; $k++) {
                if (!array_key_exists($k, $matrix[$maxRow]) || !array_key_exists($k, $matrix[$i])) {
                    continue;
                }
                $tmp = $matrix[$maxRow][$k];
                $matrix[$maxRow][$k] = $matrix[$i][$k];
                $matrix[$i][$k] = $tmp;
            }

            if ($matrix[$i][$i] == 0) {
                throw new InvalidArgumentException('Matrix is singular and cannot be solved.');
            }

            // Make all rows below and above this one 0 in current column
            for ($k = 0; $k < $n; $k++) {
                if ($k != $i) {
                    $c = -$matrix[$k][$i] / $matrix[$i][$i];
                    for ($j = $i; $j < 2 * $n; $j++) {
                        if (!array_key_exists($j,  $matrix[$k])) {
                            continue;
                        }
                        if ($i == $j) {
                            $matrix[$k][$j] = 0;
                        } else {
                            $matrix[$k][$j] += $c * $matrix[$i][$j];
                        }
                    }
                }
            }
        }

        return $matrix;
    }

    public static function gaussianElimination(array $matrix): array
    {
        $n = count($matrix);

        $matrix = self::gaussianEliminationForInverse($matrix);

        // Solve equation for upper triangular matrix
        $solution = array_fill(0, $n, 0);
        for ($i = $n - 1; $i >= 0; $i--) {
            $solution[$i] = $matrix[$i][$n] / $matrix[$i][$i];
            if (is_nan($solution[$i]) || is_infinite($solution[$i])) {
                throw new InvalidArgumentException('Solution contains non-finite numbers.');
            }
            for ($k = 0; $k < $i; $k++) {
                $matrix[$k][$n] -= $matrix[$k][$i] * $solution[$i];
            }
        }

        return $solution;
    }

    public static function invert(array $matrix): array
    {
        $rowCount = count($matrix);
        $columnCount = count($matrix[0]);

        if ($rowCount !== $columnCount) {
            throw new \Exception('The matrix must be square to compute the inverse.');
        }

        // Append the identity matrix to the input matrix
        for ($i = 0; $i < $rowCount; $i++) {
            for ($j = 0; $j < $rowCount; $j++) {
                $matrix[$i][] = $i === $j ? 1 : 0;
            }
        }

        // Apply Gaussian elimination
        $matrix = self::gaussianEliminationForInverse($matrix);

        // Now we have [original matrix | identity matrix]
        // We want to get [identity matrix | inverse matrix]

        for ($i = 0; $i < $rowCount; $i++) {
            // Divide row by pivot (makes pivot = 1)
            $pivot = $matrix[$i][$i];
            if ($pivot == 0) {
                throw new \Exception('Matrix is not invertible.');
            }
            for ($j = $i; $j < 2 * $rowCount; $j++) {
                $matrix[$i][$j] /= $pivot;
            }

            // Make other elements in column 0
            for ($j = 0; $j < $rowCount; $j++) {
                if ($i !== $j) {
                    $temp = $matrix[$j][$i];
                    for ($k = $i; $k < 2 * $rowCount; $k++) {
                        $matrix[$j][$k] -= $matrix[$i][$k] * $temp;
                    }
                }
            }
        }

        // Now we have [identity matrix | inverse matrix]
        // We want to remove the left half (identity matrix)

        for ($i = 0; $i < $rowCount; $i++) {
            $matrix[$i] = array_slice($matrix[$i], $rowCount);
        }

        return $matrix;
    }

    public static function addColumn(array $matrix, $columnValue): array
    {
        if (empty($matrix)) {
            return [];
        }

        foreach ($matrix as &$row) {
            $row[] = $columnValue;
        }

        return $matrix;
    }

    public static function createMatrixFromColumn(array $column): array
    {
        $matrix = [];
        foreach ($column as $value) {
            $matrix[] = [$value];
        }
        return $matrix;
    }
}
