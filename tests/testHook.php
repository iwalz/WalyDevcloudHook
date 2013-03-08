<?php
require_once '../../../autoload.php';
set_time_limit(60);
use Zend\Http\Client;

$client = new Client();
$client->setOptions(array('timeout' => 120));
$client->setUri('http://' . $argv[1] . '/devcloud_hook');
$client->setMethod('post');
$client->setParameterPost(
    array(
        'payload' => file_get_contents('WalyDevcloudHookTest/TestAssets/hookrepo.json')
    )
);
var_dump($client->send());
