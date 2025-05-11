<?php
// api/properties/[id].get.php


header('Content-Type: application/json');

$response = null;
$statusCode = 200;

try {
    $propertyId = $_GET['id'] ?? null;

    if (!$propertyId) {
        throw new Exception('Property ID is required.', 400);
    }

    $redisUrl = getenv('UPSTASH_REDIS_URL');
    if (!$redisUrl) {
        throw new Exception('Upstash Redis URL (UPSTASH_REDIS_URL) not found in environment variables.', 500);
    }

    $parsedUrl = parse_url($redisUrl);
    if (!$parsedUrl || !isset($parsedUrl['host'])) {
        throw new Exception('Invalid Redis URL provided.', 500);
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
        throw new Exception('Failed to connect to Redis host.', 500);
    }

    if (isset($parsedUrl['pass'])) {
        if (!$redis->auth($parsedUrl['pass'])) {
            throw new Exception('Redis authentication failed.', 500);
        }
    }

    $redisKey = 'properties:' . $propertyId;
    $propertyDataString = $redis->get($redisKey);

    if ($propertyDataString !== null) {
        $property = json_decode($propertyDataString, true); // true for associative array
        if (json_last_error() === JSON_ERROR_NONE) {
            $response = $property;
        } else {
            // Data in Redis was not valid JSON, or not what we expected
            throw new Exception('Failed to decode property data from Redis.', 500);
        }
    } else {
        $statusCode = 404;
        $response = ['error' => 'Property not found'];
    }

} catch (RedisException $e) {
    $statusCode = 500;
    $response = ['error' => 'Redis error: ' . $e->getMessage()];
} catch (Exception $e) {
    $statusCode = $e->getCode() >= 400 && $e->getCode() < 600 ? $e->getCode() : 500;
    $response = ['error' => $e->getMessage()];
}

http_response_code($statusCode);
echo json_encode($response);

?>