<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$client = new MongoDB\Client(config('database.connections.mongodb.dsn'));
$db = $client->selectDatabase(config('database.connections.mongodb.database'));
$collection = $db->selectCollection('questions');

$collections = $db->listCollections();
foreach ($collections as $collectionInfo) {
    if ($collectionInfo['name'] === 'health_records' && isset($collectionInfo['options']['validator'])) {
        echo json_encode($collectionInfo['options']['validator'], JSON_PRETTY_PRINT);
    }
}
