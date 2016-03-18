<?php
// Functions Library - groupme_functions.php
require_once ROOT_DIR.'/groupme_config.php';

function gmImageService($url) {
    $gmISURL = 'https://image.groupme.com/pictures?access_token='.$GLOBALS['AUTH_TOKEN'];

    // $raw = curlGrabRawBinary($url);

    $temp = tempnam('/tmp', 'SLACK_PIC_');
    file_put_contents($temp, file_get_contents($url));
    $postfields = array('file'=>'@'.$temp);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $gmISURL);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: multipart/form-data'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
    logger('Response: '. $response = curl_exec($ch));
    curl_close($ch);

    unlink($temp);

    return json_decode($response)->payload->picture_url;


    // fwrite($temp, file_get_contents($url));



    // $postfields = array('file'=>base64_encode($raw));

    // $ch = curl_init();
    // curl_setopt($ch, CURLOPT_URL, $gmISURL);
    // curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: multipart/form-data'));
    // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    // curl_setopt($ch, CURLOPT_POST, 1);
    // curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
    // logger('Response: '. $response = curl_exec($ch));
    // curl_close($ch);
    // return json_decode($response)->payload->url;

    

    // return json_decode(sendPostJson($gmISURL, json_encode($postfields)))->payload->url;
}