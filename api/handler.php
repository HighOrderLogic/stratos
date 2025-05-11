<?php
// api/handler.php

// Autoload Composer dependencies
require __DIR__ . '/../vendor/autoload.php';

use Predis\Client as PredisClient;

header('Content-Type: application/json');

$response = ['status' => 'error', 'message' => 'Unknown error'];

try {
    $redisHost = getenv('UPSTASH_REDIS_HOST');
    $redisPort = getenv('UPSTASH_REDIS_PORT');
    $redisPassword = getenv('UPSTASH_REDIS_PASSWORD');

    if (!$redisHost || !$redisPort || !$redisPassword) {
        throw new Exception('Upstash Redis connection details not found in environment variables.');
    }

    $redis = new PredisClient([
        'scheme' => 'tls', // Upstash typically uses TLS
        'host'   => $redisHost,
        'port'   => (int)$redisPort,
        'password' => $redisPassword,
    ]);

    // Test connection
    $redis->ping();
    $response = ['status' => 'success', 'message' => 'Successfully connected to Upstash Redis and pinged.'];

    // Example: Set a key
    // $redis->set('mykey', 'Hello from PHP via Upstash!');
    // $value = $redis->get('mykey');
    // $response['data'] = ['key_set' => 'mykey', 'value_retrieved' => $value];


} catch (Predis\Connection\ConnectionException $e) {
    http_response_code(500);
    $response = ['status' => 'error', 'message' => 'Failed to connect to Redis: ' . $e->getMessage()];
} catch (Exception $e) {
    http_response_code(500);
    $response = ['status' => 'error', 'message' => $e->getMessage()];
}

echo json_encode($response);

?>