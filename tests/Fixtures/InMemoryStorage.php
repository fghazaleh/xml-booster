<?php

namespace Tests\Fixtures;

use FGhazaleh\Storage\Contracts\StorageInterface;
use FGhazaleh\Support\Collection\Collection;

class InMemoryStorage implements StorageInterface
{
    private $storage = [];

    /**
     * Store data as key, value pair in storage engine.
     *
     * @param string $key
     * @param Collection $data
     * @return null|string
     */
    public function store(string $key, Collection $data): ?string
    {
        $this->storage[$key] = $data;
        return $key;
    }

    /**
     * Using key to retrieve data from storage engine
     *
     * @param string $key
     * @return Collection
     * */
    public function read(string $key): Collection
    {
        return $this->storage[$key];
    }
}
