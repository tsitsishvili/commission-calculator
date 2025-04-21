<?php

declare(strict_types=1);

namespace App\Reader;

interface Reader
{
    public function read(string $source): array;
}
