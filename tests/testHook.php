<?php
require_once '../../../autoload.php';

use Zend\Http\Client;

$client = new Client();
$client->setUri('http://' . $argv[1] . '/devcloud_hook');
$client->setMethod('post');
$client->setParameterPost(
    array(
        'payload' => file_get_contents('WalyDevcloudHookTest/TestAssets/payload.json')
    )
);
var_dump($client->send());
