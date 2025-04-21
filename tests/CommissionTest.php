<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\App;

class CommissionTest extends TestCase
{
    public function testCommissionCalculation()
    {
        $inputFile = __DIR__ . '/fixtures/input.csv';
        $expectedFile = __DIR__ . '/fixtures/expected.txt';

        $config = [
            'static_rates' => [
                'EUR' => 1,
                'USD' => 1.1497,
                'JPY' => 129.53,
            ],
        ];

        // Capture script output
        ob_start();
        (new App())->run($inputFile, $config);
        $output = trim(ob_get_clean());

        $expected = trim(file_get_contents($expectedFile));

        $this->assertEquals($expected, $output);
    }
}
