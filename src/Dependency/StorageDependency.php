<?php

use FGhazaleh\Storage\GoogleSheets\GoogleClientFactory;
use FGhazaleh\Storage\GoogleSheets\GoogleSheetsStorage;

//create GoogleClient instance.
$googleClient = GoogleClientFactory::make(
    $config->get('google_api.sheets')
);

//set GoogleClient logger instance.
$googleClient->setLogger($logger);

//create GoogleSheetsStorage.
$storage = new GoogleSheetsStorage($googleClient);
