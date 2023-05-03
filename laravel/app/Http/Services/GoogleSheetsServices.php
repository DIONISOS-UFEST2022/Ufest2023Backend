<?php

namespace App\Http\Services;

use App\Models\panitia;
use Google\Client;
use Google\Service\Sheets;
use Google\Service\Drive;
use Google\Service\Sheets\ValueRange;
use Google\Service\Sheets\ClearValuesRequest;
use Google\Service\Sheets\BatchUpdateSpreadsheetRequest;
use Google\Service\Sheets\Request;

class GoogleSheetsServices
{
    public $client, $service, $documentId, $range;

    public function __construct()
    {
        $this->client = $this->getClient();
        $this->service = new Sheets($this->client);
        $this->documentId = "1mWdSzkKiuVoXP8sTnVYR3CwWbLsa37bcxDOvVyTOzEY";
        $this->range = 'PendaftaranMobileLegend!A2:P';
    }

    public function getClient()
    {
        $client = new Client();
        $client->setApplicationName("Data Panitia Ufest 2023");
        $client->setRedirectUri("http://localhost:8000/googlesheets");
        $client->setScopes(Sheets::SPREADSHEETS);
        $client->setAuthConfig('public/credentials.json');
        $client->setAccessType("offline");

        return $client;
    }

    public function writeSheet($data)
    {

        $body = new ValueRange([
            'values' => $data
        ]);

        $params = [
            'valueInputOption' => 'RAW'
        ];

        $result = $this->service->spreadsheets_values->update($this->documentId, $this->range, $body, $params);

        if (!$result) {
            return false;
        }
        return true;
    }

    public function writeSheet2($data, $row)
    {

        $body = new ValueRange([
            'values' => $data
        ]);

        $params = [
            'valueInputOption' => 'RAW'
        ];

        $result = $this->service->spreadsheets_values->update($this->documentId, 'PendaftaranMobileLegend!A' . $row, $body, $params);
        if (!$result) {
            return false;
        }
        return true;
    }
    
    public function appendSheet($data)
    {
        $body = new ValueRange([
            'values' => $data
        ]);

        $params = [
            'valueInputOption' => 'RAW'
        ];

        $this->service->spreadsheets_values->append($this->documentId, $this->range, $body, $params);
    }

    public function DeleteSheet()
    {
        $requestBody = new ClearValuesRequest();

        $this->service->spreadsheets_values->clear($this->documentId, $this->range, $requestBody);
    }

    //   
}
