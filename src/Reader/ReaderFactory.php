<?php

declare(strict_types=1);

namespace App\Reader;

class ReaderFactory
{
    public static function get(string $source): Reader
    {
        $extension = pathinfo($source, PATHINFO_EXTENSION);

        return match ($extension) {
            'csv' => new CsvReader(),
            default => throw new \InvalidArgumentException("Unsupported reader type: $extension"),
        };
    }
}
