<?php

namespace Lemonshot\CurveFitting\Tests;

use Lemonshot\CurveFitting\Utils\MatrixOperations;
use PHPUnit\Framework\TestCase;

class MatrixOperationsTest extends TestCase
{
    public function testAdd(): void
    {
        $matrixA = [
            [1, 2],
            [3, 4],
        ];

        $matrixB = [
            [5, 6],
            [7, 8],
        ];

        $expectedResult = [
            [6, 8],
            [10, 12],
        ];

        $this->assertEquals($expectedResult, MatrixOperations::add($matrixA, $matrixB));
    }

    public function testSubtract(): void
    {
        $matrixA = [
            [1, 2],
            [3, 4],
        ];

        $matrixB = [
            [5, 6],
            [7, 8],
        ];

        $expectedResult = [
            [-4, -4],
            [-4, -4],
        ];

        $this->assertEquals($expectedResult, MatrixOperations::subtract($matrixA, $matrixB));
    }

    public function testMultiply(): void
    {
        $matrixA = [
            [1, 2],
            [3, 4],
        ];

        $matrixB = [
            [5, 6],
            [7, 8],
        ];

        $expectedResult = [
            [19, 22],
            [43, 50],
        ];

        $this->assertEquals($expectedResult, MatrixOperations::multiply($matrixA, $matrixB));
    }

    public function testTranspose(): void
    {
        $matrix = [
            [1, 2, 3],
            [4, 5, 6],
        ];

        $expectedResult = [
            [1, 4],
            [2, 5],
            [3, 6],
        ];

        $this->assertEquals($expectedResult, MatrixOperations::transpose($matrix));
    }

    public function testInvert(): void
    {
        $matrix = [
            [4, 7],
            [2, 6]
        ];

        $expectedResult = [
            [0.6, -0.7],
            [-0.2, 0.4]
        ];

        $this->assertEquals($expectedResult, MatrixOperations::invert($matrix));
    }

    public function testAddColumn(): void
    {
        $matrix = [
            [1, 2],
            [3, 4],
        ];

        $columnValue = 5;

        $expectedResult = [
            [1, 2, 5],
            [3, 4, 5],
        ];

        $this->assertEquals($expectedResult, MatrixOperations::addColumn($matrix, $columnValue));
    }

    public function testCreateMatrixFromColumn(): void
    {
        $column = [1, 2, 3];

        $expectedResult = [
            [1],
            [2],
            [3],
        ];

        $this->assertEquals($expectedResult, MatrixOperations::createMatrixFromColumn($column));
    }
}
