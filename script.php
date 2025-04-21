<?php

require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

use App\App;

$app = new App();

try {
    if ($argc < 2) {
        throw new Exception("Please provide the input file path as an argument.");
    }

    $app->run($argv[1]);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . PHP_EOL;
    exit();
}