<?php

declare(strict_types=1);

namespace App\Reader;

use App\Model\Operation;

class CsvReader implements Reader
{
    /**
     * @throws \Exception
     */
    public function read(string $source): array
    {
        $operations = [];

        if (!file_exists($source)) {
            throw new \Exception("File not found: $source");
        }

        $handle = fopen($source, 'r');
        while (($data = fgetcsv($handle, 0, ',')) !== false) {
            if (count($data) < 6) {
                throw new \Exception("Invalid CSV format in file: $source");
            }

            $operations[] = new Operation(
                date: $data[0],
                userId: (int) $data[1],
                userType: $data[2],
                operationType: $data[3],
                amount: (float) $data[4],
                currency: $data[5]
            );
        }

        fclose($handle);

        return $operations;
    }
}
