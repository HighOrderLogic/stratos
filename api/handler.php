<?php
// api/handler.php

// Autoload Composer dependencies

header('Content-Type: application/json');

$response = ['status' => 'error', 'message' => 'Unknown error'];

try {
    $redisUrl = getenv('UPSTASH_REDIS_URL');

    if (!$redisUrl) {
        throw new Exception('Upstash Redis URL (UPSTASH_REDIS_URL) not found in environment variables.');
    }

    // The $redisUrl should be a full DSN, e.g., rediss://:password@host:port or tls://:password@host:port
    $parsedUrl = parse_url($redisUrl);
    if (!$parsedUrl || !isset($parsedUrl['host'])) {
        throw new Exception('Invalid Redis URL provided.');
    }

    $redis = new Redis();

    $host = $parsedUrl['host'];
    $port = isset($parsedUrl['port']) ? $parsedUrl['port'] : 6379;
    $scheme = isset($parsedUrl['scheme']) ? $parsedUrl['scheme'] : 'tcp';

    if ($scheme === 'rediss' || $scheme === 'tls') {
        $connectHost = 'tls://' . $host;
    } else {
        $connectHost = $host;
    }

    if (!$redis->connect($connectHost, $port)) {
        throw new Exception('Failed to connect to Redis host.');
    }

    if (isset($parsedUrl['pass'])) {
        if (!$redis->auth($parsedUrl['pass'])) {
            throw new Exception('Redis authentication failed.');
        }
    }

    // Test connection
    if ($redis->ping() !== '+PONG' && $redis->ping() !== true) { // true for older php-redis versions
        throw new Exception('Redis PING failed.');
    }
    $response = ['status' => 'success', 'message' => 'Successfully connected to Upstash Redis and pinged.'];

    // Example: Set a key
    // $redis->set('mykey', 'Hello from PHP via Upstash!');
    // $value = $redis->get('mykey');
    // $response['data'] = ['key_set' => 'mykey', 'value_retrieved' => $value];


} catch (RedisException $e) {
    http_response_code(500);
    $response = ['status' => 'error', 'message' => 'Redis error: ' . $e->getMessage()];
} catch (Exception $e) {
    http_response_code(500);
    $response = ['status' => 'error', 'message' => $e->getMessage()];
}

echo json_encode($response);

?>