<?php
$getSingleArray = array(
    'id'  => '2169122',
    'name'=> 'Test_Form_1'
);

$getAllUrl = 'https://www.formstack.com/api/v2/form.json';
$getAllResponse = callApi($getAllUrl);
$getAllArray = array('2169122', '2169130');

    echo "\n Get ALL Form Verification";
verifyAllResponse($getAllResponse, $getAllArray);

$getSingleUrl = 'https://www.formstack.com/api/v2/form/2169122.json';
$getSingleResponse = callApi($getSingleUrl);
    echo "\n Single Form Verification";
verifyResponse($getSingleResponse, $getSingleArray);

$copySingleUrl = 'https://www.formstack.com/api/v2/form/2169122/copy.json';
$copyResponse = callApi($copySingleUrl, "POST");
$copiedId = $copyResponse['id'];
$copiedName = $copyResponse['name'];

$getCopyArray = array(
    'id'  => "$copiedId",
    'name'=> "$copiedName"
);
        echo "\n Copied Form Verification";
verifyResponse($copyResponse, $getCopyArray);

$newCopyUrl = "https://www.formstack.com/api/v2/form/$copiedId.json";
$deleteResponse = callApi($newCopyUrl, "DELETE");;
$deletedName = $deleteResponse['name'];
$deletedId   = $deltedResponse['id'];

$getDeleteArray = array(
    'id'   => "$copiedId",
    'name' => "$deletedName",
    'success'=> "1"
);
        echo "\n Deleted Form Verification";
verifyDeleteResponse($deleteResponse, $getDeleteArray);


function callApi ($url, $type = "GET", $data = array()) {

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: Bearer 0ef4462226af829b6c30d4bfe383d459'));
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "$type");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        $curl_response = curl_exec($curl);
        curl_close($curl);
        $curl_decoded = json_decode($curl_response);
        $curl_decoded = (array) $curl_decoded;
        return $curl_decoded;
}

function verifyResponse ($response, $correctResponse = array()) {
        echo "\n API RESPONSE \n";
foreach(array_keys($correctResponse) as $key) {
        echo "$key : $response[$key] \n";
        }
        echo "\n CORRECT RESPONSE \n";
        foreach($correctResponse as $key => $value) {
                echo "$key : $value \n";
        }
        foreach(array_keys($correctResponse) as $key) {
                echo " Verifying '$key' matches...";
                if ($response[$key] == $correctResponse[$key])  {
                        echo "true \n";
                }       else {
                        echo "false \n";
                }
        }
}

function verifyAllResponse ($response, $correctResponse = array())  {
        $getAllId = array();
        echo "\n API RESPONSE \n";
        foreach($response['forms'] as $key => $value) {
                array_push($getAllId, $value->id);
                print_r($value->id);
                echo "\n";
}
                echo "\n CORRECT RESPONSE \n";
                $result = array_intersect($correctResponse, $getAllId);
        foreach($correctResponse as $key => $value) {
                echo "$value \n";
        }
                echo "Verifying files exist on account...";
                if($result >= 0) {
                echo "PASS, these forms matched \n";

        }       else {
                echo "FAIL, No forms found. \n";
                }
}
function verifyDeleteResponse ($response, $correctResponse = array())  {
        echo "\n API RESPONSE \n";
        foreach(array_keys($correctResponse) as $key) {
                echo "$key : $response[$key] \n";
        }
        echo "\n CORRECT RESPONSE \n";
        foreach($correctResponse as $key => $value) {
                echo "$key : $value \n";
        }
            echo " Verifying form was properly deleted...";
        if ($response[$key] == $correctResponse[$key]) {
            echo "PASS, form was deleted. \n";
        }   else {
            echo "FAIL, form was not properly deleted. \n";
        }
}
?>
