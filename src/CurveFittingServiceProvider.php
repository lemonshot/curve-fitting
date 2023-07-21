<?php

namespace Lemonshot\CurveFitting;

use Illuminate\Support\ServiceProvider;
use Lemonshot\CurveFitting\Fitters\CurveFitter;
use Lemonshot\CurveFitting\Solvers\LinearRegressionSolver;
use Lemonshot\CurveFitting\Solvers\PolynomialRegressionSolver;

class CurveFittingServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(CurveFitter::class, function ($app) {
            return new CurveFitter([
                'linear' => $app->make(LinearRegressionSolver::class),
                'polynomial' => $app->make(PolynomialRegressionSolver::class),
            ]);
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {}
}
