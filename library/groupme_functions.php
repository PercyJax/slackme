<?php
// Functions Library - groupme_functions.php
require_once ROOT_DIR.'/groupme_config.php';

function gmImageService($url) {
    $gmISURL = 'https://image.groupme.com/pictures?access_token='.$AUTH_TOKEN;
    return sendPostJson($gmISURL, "file=".$url, "multipart/form-data")
}