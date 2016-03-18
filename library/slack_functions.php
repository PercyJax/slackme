<?php
// Functions Library - slack_functions.php

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
        file_put_contents($LOGFILE, (gettype($text) == 'string' ? $text : json_encode($text))."\n", FILE_APPEND);
    }
    return $text;
}

// function isFromSlack() {
//     return isset($_POST["token"]) && $_POST["token"] == $VERIFICATION_TOKEN;
// }

function sendPostJson($url, $post_fields_json_str) {
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, logger($post_fields_json_str));
    curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    logger(json_decode($result = logger(curl_exec($curl))));
    logger(json_encode(curl_getinfo($curl)));
    curl_close($curl);
    return $result;
}

function callSlackAPI($api, $input_obj = array(), $token = null) {
    $input_obj['token'] = (is_null($token) ? $GLOBALS['API_TOKEN'] : $token);
    return json_decode(logger(sendPostJson("https://slack.com/api/".$api.'?'.http_build_query($input_obj), logger(json_encode($input_obj)))));
}