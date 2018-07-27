<?php

namespace Tests\Fixtures;

use FGhazaleh\Storage\Contracts\StorageInterface;
use FGhazaleh\Storage\GoogleSheets\GoogleSheetsStorage;
use FGhazaleh\Support\Collection\Collection;

class GoogleSheetsStorageMock extends GoogleSheetsStorage implements StorageInterface
{

    private $insertedId = null;



    /**
     * @inheritdoc
    */
    public function store(string $key, Collection $data): ?string
    {
        return $this->insertedId = parent::store($key, $data);
    }

    /**
     * Get spreadsheet id.
     *
     * @return string|null
     * */
    public function getLastInsertedId(): ?string
    {
        return $this->insertedId;
    }



    public function remove(string $key):void
    {
        try {
            $service = new \Google_Service_Drive($this->client);
            $service->files->delete($key);
        } catch (Exception $e) {
            print "An error occurred: " . $e->getMessage();
        }
    }
}