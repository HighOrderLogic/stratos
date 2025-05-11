<?php
// api/properties/index.post.php


header('Content-Type: application/json');

$response = null;
$statusCode = 201; // Default to 201 Created for successful POST

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method. Only POST is allowed.', 405);
    }

    $rawBody = file_get_contents('php://input');
    $body = json_decode($rawBody, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Invalid JSON in request body.', 400);
    }

    // Validation
    $requiredFields = ['name', 'owner', 'type', 'address'];
    foreach ($requiredFields as $field) {
        if (empty($body[$field])) {
            throw new Exception("Missing required field: {$field}", 400);
        }
    }

    $allowedTypes = ['house', 'apartment', 'villa'];
    if (!in_array($body['type'], $allowedTypes, true)) {
        throw new Exception('Invalid property type. Must be one of: ' . implode(', ', $allowedTypes), 400);
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

    // Generate a UUID v4 compatible string
    if (function_exists('random_bytes')) {
        $data = random_bytes(16);
        assert(strlen($data) === 16);
        // Set version to 0100
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        // Set bits 6-7 to 10
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
        $newPropertyId = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    } else {
        // Fallback for environments where random_bytes is not available
        $newPropertyId = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000, // 3rd group, 1st 4 bits = 0100
            mt_rand(0, 0x3fff) | 0x8000, // 4th group, 1st 4 bits = 1000
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }
    $newProperty = [
        'name' => $body['name'],
        'owner' => $body['owner'],
        'type' => $body['type'],
        'address' => $body['address'],
        'dateCreated' => date('c'), // ISO 8601 format date
        'sold' => false,
    ];

    $redisKey = 'properties:' . $newPropertyId;
    // Store the property as a JSON string
    $redis->set($redisKey, json_encode($newProperty));

    // Return the full new property object including its generated ID
    $response = array_merge(['id' => $newPropertyId], $newProperty);

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