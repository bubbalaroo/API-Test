<?php
$getSingleArray = array(
    'id'  => '2169122',
    'name'=> 'Test_Form_1'
);
// GET ALL call to api and verified against two set form id values.
$getAllUrl = 'https://www.formstack.com/api/v2/form.json';
$getAllResponse = callApi($getAllUrl);
$getAllArray = array('2169122', '2169130');
    echo "\n Get ALL Form Verification";
verifyAllResponse($getAllResponse, $getAllArray);

// GET SINGLE call to api and verified against Test_Form_1.
$getSingleUrl = 'https://www.formstack.com/api/v2/form/2169122.json';
$getSingleResponse = callApi($getSingleUrl);
    echo "\n Single Form Verification";
verifyResponse($getSingleResponse, $getSingleArray);

// POST to make a copy and verified against original form.
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

// DELETE to the copied form id and verification against correct array.
$newCopyUrl = "https://www.formstack.com/api/v2/form/$copiedId.json";
$deleteResponse = callApi($newCopyUrl, "DELETE");;
$deletedName = $deleteResponse['name'];
$deletedId   = $deltedResponse['id'];

$getDeleteArray = array(
    'id'   => "$copiedId",
    'success'=> "1"
);
	echo "\n Deleted Form Verification"; 
verifyDeleteResponse($deleteResponse, $getDeleteArray);


// Function calls api
//
// $url  -  the url to call
// $type -  GET, PUT, POST, DELETE\
// #data -  array of data to send to the api
//

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

// Function verfies responses against a correct response array.          //
// 
// $response  -  the decoded response from the api                       //
// $correctResponse -  correct answers to compare response array against.//
//

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
		}	else {
			echo "false \n";
		}
	}
}
//
// Specific function to verify response of get all call to api to decode multidimensional array. //
//
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
		if($result == $correctResponse ) {
		echo "PASS, all forms returned.  \n";
		
	}	else {
		echo "FAIL, Not all forms found. \n";
		}
}

// Function specific to DELETE form request to compare key values and confirm form was deleted. //
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
