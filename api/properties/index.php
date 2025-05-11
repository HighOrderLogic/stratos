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
        // $iterator is passed by reference and updated by scan.
        // scan returns an array of keys or FALSE on error.
        $scanned_keys = $redis->scan($iterator, $prefix . '*', 100);

        if ($scanned_keys === false) {
            // An error occurred during scan. This might be caught by RedisException handler,
            // but if scan simply returns false without an exception, we should stop.
            // error_log("Redis scan returned false. Current iterator value: " . (is_null($iterator) ? 'null' : $iterator));
            break; // Exit the do-while loop
        }

        if (!empty($scanned_keys)) { // Proceed if scan returned some keys
            foreach ($scanned_keys as $key) { // Iterate directly over the array of keys
                $propertyDataString = $redis->get($key);
                // php-redis get() returns FALSE if key does not exist or on error.
                // It returns the string value otherwise.
                if ($propertyDataString !== false) {
                    $property = json_decode($propertyDataString, true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        $id = substr($key, strlen($prefix));
                        $properties[] = array_merge(['id' => $id], $property);
                    } else {
                        // Optional: Log malformed JSON data.
                        // error_log("Failed to decode JSON for key '{$key}': " . json_last_error_msg());
                    }
                } else {
                    // Optional: Log if a key found by SCAN does not exist when GET is called (race condition or inconsistency).
                    // error_log("Key '{$key}' found by SCAN was not retrievable with GET.");
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