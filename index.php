<?php 

    require __DIR__ .'/vendor/autoload.php';

    $client = new \Google_Client();
    $client->setApplicationName('Google Sheets and PHP');
    $client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);
    $client->setAccessType('offline');
    $client->setAuthConfig(__DIR__.'/credential.json');
    $service = new Google_Service_Sheets($client);
    $spreadsheetId = "1sBJNkXkI503_lK0lYbGbxubkXhJs0WWSRAxzoxZ_680";
    
    $range = "A1:F8";
    $response = $service->spreadsheets_values->get($spreadsheetId,$range);
    $values = $response->getValues();

    if(empty($values)){
        print "No Data found.\n";
    } else {
        var_dump($values);
    }
?>