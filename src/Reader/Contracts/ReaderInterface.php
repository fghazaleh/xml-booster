<?php declare(strict_types=1);

namespace FGhazaleh\Reader\Contracts;

use FGhazaleh\Support\Collection\Collection;

interface ReaderInterface
{
    /**
     * Read data by provided file.
     *
     * @param string $file
     * @return Collection
     */
    public function read(string $file):Collection;
}
