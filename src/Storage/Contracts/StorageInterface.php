<?php declare(strict_types=1);

namespace FGhazaleh\Storage\Contracts;

use FGhazaleh\Support\Collection\Collection;

interface StorageInterface
{
    /**
     * Store data as key, value pair in storage engine.
     *
     * @param string $key
     * @param Collection $data
     * @return string|null
     * */
    public function store(string $key, Collection $data): ?string;

    /**
     * Using key to retrieve data from storage engine
     *
     * @param string $key
     * @return Collection
     * */
    public function read(string $key): Collection;
}
