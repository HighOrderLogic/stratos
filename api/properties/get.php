<?php
// api/properties/index.get.php


header('Content-Type: application/json');

$response = [];
$statusCode = 200;

try {
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

    $properties = [];
    $iterator = null; // Required for SCAN
    $prefix = 'properties:';

    // SCAN for keys matching "properties:*"
    // Using a loop for SCAN as it might not return all keys in one go
    do {
        // scan($cursor, ['MATCH' => $pattern, 'COUNT' => $count])
        // For php-redis, the 'MATCH' and 'COUNT' options are passed as part of an options array or directly
        // The exact signature can vary slightly based on php-redis version, common is scan($iterator, $pattern, $count)
        $keys = $redis->scan($iterator, $prefix . '*', 100); // Adjust COUNT as needed

        if (!empty($keys[1])) {
            foreach ($keys[1] as $key) {
                $propertyDataString = $redis->get($key);
                if ($propertyDataString !== null) {
                    $property = json_decode($propertyDataString, true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        // Extract ID from the key by removing the prefix
                        $id = substr($key, strlen($prefix));
                        $properties[] = array_merge(['id' => $id], $property);
                    } else {
                        // Log or handle malformed JSON in Redis if necessary
                        // For now, we'll skip it
                    }
                }
            }
        }
    } while ($iterator > 0); // Continue if the cursor is not 0

    $response = $properties;

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