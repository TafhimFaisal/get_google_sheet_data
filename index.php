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

        $data = dataModify( $values );
        $table = tableGenerate( $data );
        echo $table ;
        dataManipulation( $data );

    }

    function dataModify($data)
    {
        $tableData = [];
        $tableData['thead'] = $data[0];
        unset($data[0]);
        $tableData['tbody'] = $data;
        return $tableData;
    }

    function tableGenerate($tableData){

        $table      = '';
        $all_th     = [];
        $all_td     = [];
        $tablehead  = '<thead>';
        $tbody      = '<tbody>';
        
        foreach ($tableData['thead'] as $key => $th) {
            array_push($all_th,'<th>'.$th.'</th>');
        }

        foreach ($tableData['tbody'] as $key1 => $tds) {
            foreach ($tds as $key2 => $td) {
                array_push( $all_td,'<td>'.$td.'</td>');
            }   
        }

        foreach ($all_th as $key => $th) {
            $tablehead .= $th;
        }
        
        $tablehead .= '</thead>';
        $tr         = '<tr>';
        $i          = 0;

        foreach ($all_td as $key => $td) {
                        
            if( $i == 5 ){
                $tr .= $td;
                
                if(key($all_td) != $key){
                    $tr .= '</tr><tr>';
                }else{
                    $tr .= '</tr>';
                }

                $i = 0;
            }else {
                $tr .= $td;
                $i++;
            }

        }

        $tbody  .=  $tr;
        $tbody  .=  '</tbody>';
        $table  .=  '<table>';
        $table  .=  $tablehead;
        $table  .=  $tbody;
        $table  .=  '</table>';


        return $table;
    }

    function getKey($data){
        return $data[0];
    }

    function keyValuePeared( $data,$name )
    {
        $structuredData = [];
        foreach ($data['tbody'] as $key1 => $value1) {
            
            if( $value1[0] == $name ){
                
                foreach ($value1 as $key2 => $value2) {
                    $array[ $data['thead'][$key2] ] = $value2;
                }
                array_push($structuredData,$array);
            }
        }
        return $structuredData;

    }

    function dataManipulation($data)
    {
        $names = array_map( 'getKey',$data['tbody'] );
        $structuredData = [];
        foreach ($names as $key => $name) {
            $structuredData [ $name ] = getKey(keyValuePeared( $data,$name )); 
        }
        var_dump($structuredData);
    }
?>