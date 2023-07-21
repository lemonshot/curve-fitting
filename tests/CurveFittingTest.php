<?php

namespace Lemonshot\CurveFitting\Tests;

use PHPUnit\Framework\TestCase;
use Lemonshot\CurveFitting\Fitters\CurveFitter;
use Lemonshot\CurveFitting\Solvers\LinearRegressionSolver;
use Lemonshot\CurveFitting\Solvers\PolynomialRegressionSolver;

class CurveFittingTest extends TestCase
{
    public function testLinearRegressionSolver()
    {
        $dataset = [
            ['x' => 1, 'y' => 5],
            ['x' => 2, 'y' => 7],
            ['x' => 3, 'y' => 9],
            ['x' => 4, 'y' => 11],
        ];

        $curveFitter = new CurveFitter(new LinearRegressionSolver());
        $result = $curveFitter->fit($dataset);

        $this->assertIsArray($result);
        $this->assertCount(2, $result);

        $expectedSlope = 2;
        $expectedIntercept = 3;

        $this->assertEqualsWithDelta($expectedSlope, $result[0], 0.0000001);
        $this->assertEqualsWithDelta($expectedIntercept, $result[1], 0.0000001);
    }

    public function testPolynomialRegressionSolverDegree2()
    {
        $dataset = [
            ['x' => 1, 'y' => 1],
            ['x' => 2, 'y' => 4],
            ['x' => 3, 'y' => 9],
            ['x' => 4, 'y' => 16],
        ];

        $curveFitter = new CurveFitter(new PolynomialRegressionSolver(2));
        $result = $curveFitter->fit($dataset);

        $this->assertIsArray($result);
        $this->assertCount(3, $result);
        $this->assertEquals([0, 0, 1], $result);
    }

    public function testPolynomialRegressionSolverDegree5()
    {
        $dataset = [
            ['x' => 1, 'y' => 1],
            ['x' => 2, 'y' => 5],
            ['x' => 3, 'y' => 3],
            ['x' => 4, 'y' => 7],
            ['x' => 5, 'y' => 9],
            ['x' => 6, 'y' => 7],
            ['x' => 7, 'y' => 15],
        ];

        $curveFitter = new CurveFitter(new PolynomialRegressionSolver(5));
        $result = $curveFitter->fit($dataset);

        $this->assertIsArray($result);
        $this->assertCount(6, $result);
        $expected = [-53.2857, 106.6757, -70.9166, 21.3257, -2.9318, 0.15];
        foreach ($expected as $i => $value) {
            $this->assertEqualsWithDelta($value, $result[$i], 0.0001);
        }
    }
}
