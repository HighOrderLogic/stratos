<?php
// api/handler.php

// Autoload Composer dependencies
require __DIR__ . '/../vendor/autoload.php';

use Predis\Client as PredisClient;

header('Content-Type: application/json');

$response = ['status' => 'error', 'message' => 'Unknown error'];

try {
    $redisUrl = getenv('UPSTASH_REDIS_URL');

    if (!$redisUrl) {
        throw new Exception('Upstash Redis URL (UPSTASH_REDIS_URL) not found in environment variables.');
    }

    // The $redisUrl should be a full DSN, e.g., rediss://:password@host:port or tls://:password@host:port
    // Predis\Client can accept a DSN string directly.
    $redis = new PredisClient($redisUrl);

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