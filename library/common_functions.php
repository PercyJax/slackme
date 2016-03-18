<?php
// Common Functions Library - common_functions.php

if ($DEBUG == 1) {
    ini_set("log_errors", 1);
    ini_set("error_log", $LOGFILE);
    error_log( "Hello, errors!" );
    file_put_contents($LOGFILE, date('l, F jS Y - g:i:s A')."\n", FILE_APPEND);
    file_put_contents($LOGFILE, 'POST: '.print_r($_POST, true).'GET: '.print_r($_GET, true), FILE_APPEND);
    file_put_contents($LOGFILE, 'BODY: '.print_r(json_decode(file_get_contents('php://input')), true), FILE_APPEND);
    file_put_contents($LOGFILE, "\n", FILE_APPEND);
}

function logger($text) {
    global $DEBUG, $LOGFILE;
    if ($DEBUG == 1) {
        // file_put_contents($LOGFILE, $text."\n", FILE_APPEND);
        file_put_contents($LOGFILE, "\n", FILE_APPEND);
        file_put_contents($LOGFILE, (gettype($text) == 'string' ? $text : json_encode($text))."\n", FILE_APPEND);
        file_put_contents($LOGFILE, "\n", FILE_APPEND);
    }
    return $text;
}

function sendPostJson($url, $post_fields_json_str, $content_type = "application/json") {
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, logger($post_fields_json_str));
    curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: " . $content_type));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    logger(json_decode($result = logger(curl_exec($curl))));
    logger(json_encode(curl_getinfo($curl)));
    curl_close($curl);
    return $result;
}

// function curlGrabRawBinary($url) {
//     // $ch = curl_init($url);
//     // curl_setopt($ch, CURLOPT_HEADER, 0);
//     // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//     // curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
//     // $raw = curl_exec($ch);
//     // curl_close($ch);
//     $raw = file_get_contents($url);
//     return $raw;
// }