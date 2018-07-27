<?php declare(strict_types=1);

namespace FGhazaleh\Storage\GoogleSheets;

use FGhazaleh\Storage\Contracts\StorageInterface;
use FGhazaleh\Support\Collection\Collection;
use Google_Client;
use Google_Service_Sheets;
use Google_Service_Sheets_BatchUpdateValuesRequest;
use Google_Service_Sheets_Spreadsheet;
use Google_Service_Sheets_SpreadsheetProperties;
use Google_Service_Sheets_ValueRange;

class GoogleSheetsStorage implements StorageInterface
{
    /**
     * @var Google_Client
     */
    protected $client;

    /**
     * GoogleSheetsStorage constructor.
     *
     * @param Google_Client $client
     */
    public function __construct(Google_Client $client)
    {
        $this->client = $client;
    }

    /**
     * Store data as key, value pair in storage engine.
     *
     * @param string $key
     * @param Collection $data
     * @return null|string
     */
    public function store(string $key, Collection $data): ?string
    {
        // create a new  empty spreadsheet.
        $spreadSheetResponse = $this->createNewGoogleSpreadsheet($key);

        $id = $spreadSheetResponse->getSpreadsheetId();

        //store the data in new spreadsheet.
        $this->insertDataToGoogleSheet($id, $data);

        return $id;
    }

    /**
     * Using key to retrieve data from storage engine
     *
     * @param string $key
     * @return Collection
     * */
    public function read(string $key): Collection
    {
        $service = new Google_Service_Sheets($this->client);
        $range = 'Sheet1!A:Z';
        $response = $service->spreadsheets_values->get($key, $range);
        $values = $response->getValues() ?? [];
        return Collection::make($values);
    }


    /**
     * Insert data into Google spreadsheet.
     *
     * @param string $spreadSheetId
     * @param Collection $data
     */
    private function insertDataToGoogleSheet(string $spreadSheetId, Collection $data): void
    {
        $range = 'Sheet1!A1:Z';
        $values = [];

        //add header
        if (is_array($data->all()) && count($data) > 0) {
            array_push($values, array_keys($data[0]));
        }
        //add values
        foreach ($data->all() as $item) {
            array_push($values, array_values($item));
        }
        $data = [];
        $data[] = new Google_Service_Sheets_ValueRange([
            'range' => $range,
            'values' => $values
        ]);
        // Additional ranges to update ...
        $body = new Google_Service_Sheets_BatchUpdateValuesRequest([
            'valueInputOption' => 'RAW',
            'data' => $data
        ]);
        $service = new Google_Service_Sheets($this->client);
        //update the sheets.
        $service->spreadsheets_values->batchUpdate($spreadSheetId, $body);
    }

    /**
     * Create a new Google spreadsheet.
     *
     * @param string $title
     * @return Google_Service_Sheets_Spreadsheet
     */
    private function createNewGoogleSpreadsheet(string $title)
    {
        $service = new Google_Service_Sheets($this->client);
        $requestBody = new Google_Service_Sheets_Spreadsheet();
        $property = new Google_Service_Sheets_SpreadsheetProperties();
        $property->setTitle($title);
        $requestBody->setProperties($property);
        $response = $service->spreadsheets->create($requestBody);
        return $response;
    }
}
