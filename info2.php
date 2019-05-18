<?php

function infoDossier($data) {
    //$url ="http://mahakim.ma/Ar/Services/SuiviAffaires_new/JFunctions/fn.aspx/getCarteDossierInWeb";
    $url ="http://10.250.1.125/saj2extensionta/borne/Default.aspx/KioskResult";
    //$url = "http://10.250.1.125/SAJExtensionV2/Borne/GetCarteDossier";
    $data = $data;
    $data_string = json_encode($data);
    $result = file_get_contents($url, null, stream_context_create(array(
    'http' => array(
    'method' => 'POST',
    'header' => 'Content-Type: application/json' . "\r\n"
    . 'Content-Length: ' . strlen($data_string) . "\r\n",
    'content' => $data_string,
    ),
    )));
    return $result;
    //Get the JSON data POSTed to the page
    //Send the JSON data to the right server
    /*$ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json; charset=utf-8"));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;*/

}

if(isset($_POST['json'])){
	$json = $_POST['json'];
	$data1 = infoDossier($json);
    echo $data1;
}else{
	echo "Error!ok";
}

?>
