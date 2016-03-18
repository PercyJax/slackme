<?php
// Functions Library - groupme_functions.php
require_once ROOT_DIR.'/groupme_config.php';

function gmImageService($url) {
    $gmISURL = 'https://image.groupme.com/pictures?access_token='.$GLOBALS['AUTH_TOKEN'];
    $raw = curlGrabRawBinary($url);

    // $ch = curl_init();
    // curl_setopt($ch, CURLOPT_URL, $gmISURL);
    // // curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: multipart/form-data'));
    // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    // curl_setopt($ch, CURLOPT_POST, 1);
    // curl_setopt($ch, CURLOPT_POSTFIELDS, $raw);
    // logger('Response: '. $response = curl_exec($ch));
    // curl_close($ch);
    // return $response;

    $postfields = array('file'=>$raw);

    return sendPostJson($gmISURL, $postfields);
}