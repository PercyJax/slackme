<?php
// Functions Library - slack_functions.php

// function isFromSlack() {
//     return isset($_POST["token"]) && $_POST["token"] == $VERIFICATION_TOKEN;
// }

function callSlackAPI($api, $input_obj = array(), $token = null) {
    $input_obj['token'] = (is_null($token) ? $GLOBALS['API_TOKEN'] : $token);
    return json_decode(logger(sendPostJson("https://slack.com/api/".$api.'?'.http_build_query($input_obj), logger(json_encode($input_obj)))));
}