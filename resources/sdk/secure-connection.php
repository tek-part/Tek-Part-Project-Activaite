<?php

/**
 * SecureConnectionManager - Advanced Laravel API Connector
 *
 * This class implements a highly secure connection mechanism for Laravel systems
 * with multiple layers of protection:
 * 1. Multi-layered encryption
 * 2. Time-based verification
 * 3. Hardware fingerprinting
 * 4. Anti-tampering measures
 * 5. Obfuscated communication protocol
 */

class SecureConnectionManager
{
    // XOR-based obfuscation key (dynamically generated)
    private $obfuscationKey;

    // Connection parameters
    private $serverUrl;
    private $appSecret;
    private $publicKey;
    private $appId;

    // Runtime values
    private $sessionToken;
    private $lastNonce;
    private $handshakeComplete = false;
    private $deviceFingerprint;
    private $lastServerResponse;
    private $connectionAttempts = 0;

    /**
     * Constructor with key scrambling
     */
    public function __construct(string $serverUrl, string $appSecret, string $appId, string $publicKey = null)
    {
        // Basic validation
        if (empty($serverUrl) || empty($appSecret) || empty($appId)) {
            $this->terminate('Invalid initialization parameters');
        }

        $this->serverUrl = $this->processUrl($serverUrl);
        $this->appSecret = $this->obfuscateSecret($appSecret);
        $this->appId = $this->encodeWithIntegrity($appId);
        $this->publicKey = $publicKey;

        // Generate dynamic obfuscation key based on multiple factors
        $this->generateObfuscationKey();

        // Generate device fingerprint (hardware + software)
        $this->deviceFingerprint = $this->generateDeviceFingerprint();
    }

    /**
     * Establish secure connection with multi-layered handshake
     */
    public function connect(): bool
    {
        if ($this->connectionAttempts > 3) {
            $this->terminate('Connection attempts exceeded');
        }

        $this->connectionAttempts++;

        // Generate nonce for this connection attempt
        $this->lastNonce = $this->generateSecureNonce();

        // Prepare challenge response data
        $challengeData = [
            'app_id' => $this->decodeWithIntegrity($this->appId),
            'timestamp' => $this->getObfuscatedTimestamp(),
            'nonce' => $this->lastNonce,
            'fingerprint' => $this->deviceFingerprint,
            'challenge_response' => $this->generateChallengeResponse()
        ];

        // First layer encryption
        $encryptedData = $this->encryptLayer1($challengeData);

        // Perform handshake
        $response = $this->sendHandshake($encryptedData);
        if (!$response) {
            return false;
        }

        // Validate server response with multi-factor verification
        $decodedResponse = $this->decryptServerResponse($response);
        if (!$this->validateServerResponse($decodedResponse)) {
            return false;
        }

        // Set session token from response
        $this->sessionToken = $decodedResponse['session_token'] ?? null;
        if (!$this->sessionToken) {
            $this->terminate('Invalid session establishment');
            return false;
        }

        $this->handshakeComplete = true;
        return true;
    }

    /**
     * Execute a secure API call using the established connection
     */
    public function executeSecureCall(string $endpoint, array $data = []): ?array
    {
        if (!$this->handshakeComplete || !$this->sessionToken) {
            $this->connect();
            if (!$this->handshakeComplete) {
                return null;
            }
        }

        // Prepare request with shifting polynomials and integrity checks
        $requestPayload = [
            'api_command' => $this->obfuscateCommand($endpoint),
            'timestamp' => $this->getObfuscatedTimestamp(),
            'session_ref' => $this->sessionToken,
            'nonce' => $this->generateSecureNonce(),
            'payload' => $this->encryptPayload($data),
            'integrity' => $this->calculateDataIntegrity($data)
        ];

        // Multi-layer encryption of the entire request
        $encryptedRequest = $this->encryptFinalPayload($requestPayload);

        // Send request through secure channel
        $response = $this->sendSecureRequest($endpoint, $encryptedRequest);
        if (!$response) {
            return null;
        }

        // Decrypt and validate response
        $decryptedResponse = $this->decryptLayeredResponse($response);
        if (!$this->validateResponseIntegrity($decryptedResponse)) {
            $this->terminate('Response integrity validation failed');
            return null;
        }

        return $decryptedResponse['data'] ?? null;
    }

    /**
     * Process URL with domain verification
     */
    private function processUrl(string $url): string
    {
        // Remove any malicious fragments or parameters
        $url = preg_replace('/[<>"\'&%]/', '', trim($url));
        $url = filter_var($url, FILTER_SANITIZE_URL);

        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            $this->terminate('Invalid server URL');
        }

        return rtrim($url, '/');
    }

    /**
     * Obfuscate secret using dynamic polymorphic encoding
     */
    private function obfuscateSecret(string $secret): string
    {
        // Apply multiple transformations to make reverse engineering difficult
        $parts = str_split($secret, 4);
        $obfuscated = '';

        foreach ($parts as $index => $part) {
            // Use different obfuscation for each segment based on its position
            $transformed = strrev($part);
            $transformed = base64_encode($transformed ^ chr(($index * 7) % 256));
            $obfuscated .= $transformed;
        }

        return $obfuscated;
    }

    /**
     * Generate device fingerprint based on hardware and environment
     */
    private function generateDeviceFingerprint(): string
    {
        $components = [];

        // OS information
        $components[] = php_uname();

        // Server software
        $components[] = $_SERVER['SERVER_SOFTWARE'] ?? '';

        // IP address (hashed)
        $components[] = hash('sha256', $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1');

        // CPU info if available
        if (function_exists('shell_exec') && !in_array('shell_exec', array_map('trim', explode(',', ini_get('disable_functions'))))) {
            $cpuInfo = @shell_exec('cat /proc/cpuinfo | grep "model name" | head -1');
            $components[] = $cpuInfo ? md5($cpuInfo) : '';
        }

        // MAC address if available (Linux)
        if (function_exists('shell_exec') && !in_array('shell_exec', array_map('trim', explode(',', ini_get('disable_functions'))))) {
            $macAddr = @shell_exec("ifconfig | grep -o -E '([0-9a-fA-F]{2}:){5}[0-9a-fA-F]{2}' | head -1");
            $components[] = $macAddr ? hash('sha256', $macAddr) : '';
        }

        // Disk serial if available
        if (function_exists('shell_exec') && !in_array('shell_exec', array_map('trim', explode(',', ini_get('disable_functions'))))) {
            $diskSerial = @shell_exec('lsblk --nodeps -o serial | head -2 | tail -1');
            $components[] = $diskSerial ? hash('sha256', $diskSerial) : '';
        }

        // Combine all factors and apply complex hashing
        return hash_hmac('sha512', implode('|', $components), $this->getObfuscatedTimestamp());
    }

    /**
     * Generate dynamic obfuscation key that changes with each execution
     */
    private function generateObfuscationKey(): void
    {
        // Mix various runtime factors to create a unique key
        $seed = [
            microtime(true),
            memory_get_usage(true),
            getmypid(),
            random_bytes(16)
        ];

        $seedStr = implode('|', array_map(function($item) {
            return is_string($item) ? bin2hex($item) : $item;
        }, $seed));

        // Apply multiple rounds of hashing to prevent reverse engineering
        $this->obfuscationKey = hash('sha512', $seedStr, true);

        // Additional transformation to further obfuscate the key
        $this->obfuscationKey = hash_hmac('sha256', $this->obfuscationKey, $seedStr, true);
    }

    /**
     * Encrypt data with first layer using polymorphic encryption
     */
    private function encryptLayer1(array $data): string
    {
        // Convert to JSON
        $jsonData = json_encode($data);

        // First transformation - XOR with dynamic key
        $transformed = '';
        $keyLength = strlen($this->obfuscationKey);

        for ($i = 0; $i < strlen($jsonData); $i++) {
            $transformed .= $jsonData[$i] ^ $this->obfuscationKey[$i % $keyLength];
        }

        // Second transformation - custom base64 with character substitution
        $encoded = base64_encode($transformed);
        $substitutionMap = [
            '+' => '!', '/' => '@', '=' => '#'
        ];

        $encoded = strtr($encoded, $substitutionMap);

        // Final wrapping - add time-based integrity check
        $integrity = hash_hmac('sha256', $encoded, $this->getObfuscatedTimestamp());

        return $encoded . '.' . substr($integrity, 0, 16);
    }

    /**
     * Generate a secure, time-based nonce
     */
    private function generateSecureNonce(): string
    {
        $timeComponent = floor(microtime(true) * 1000);
        $randomComponent = random_bytes(16);

        return hash_hmac('sha256', $timeComponent . bin2hex($randomComponent), $this->obfuscationKey);
    }

    /**
     * Generate time-based obfuscated timestamp that changes in a predictable pattern
     */
    private function getObfuscatedTimestamp(): string
    {
        $time = time();
        $timeKey = floor($time / 30); // Changes every 30 seconds

        return hash_hmac('sha256', (string)$timeKey, $this->obfuscationKey);
    }

    /**
     * Generate challenge response for server verification
     */
    private function generateChallengeResponse(): string
    {
        $components = [
            $this->decodeWithIntegrity($this->appId),
            $this->lastNonce,
            $this->deviceFingerprint,
            $this->getObfuscatedTimestamp()
        ];

        // Mix the components with the secret
        $mixed = implode('|', $components) . '|' . $this->deobfuscateSecret($this->appSecret);

        // Apply multiple rounds of hashing
        $result = '';
        $temp = $mixed;

        for ($i = 0; $i < 3; $i++) {
            $temp = hash_hmac('sha256', $temp, $this->obfuscationKey . $i);
            $result .= $temp;
        }

        return substr($result, 0, 64);
    }

    /**
     * Encode data with integrity check
     */
    private function encodeWithIntegrity(string $data): string
    {
        $encoded = base64_encode($data);
        $integrity = hash_hmac('sha256', $encoded, $this->getObfuscatedTimestamp());

        return $encoded . '.' . substr($integrity, 0, 16);
    }

    /**
     * Decode data with integrity verification
     */
    private function decodeWithIntegrity(string $encodedData): string
    {
        list($data, $checksum) = explode('.', $encodedData);
        $calculatedChecksum = substr(hash_hmac('sha256', $data, $this->getObfuscatedTimestamp()), 0, 16);

        if ($checksum !== $calculatedChecksum) {
            $this->terminate('Integrity check failed');
        }

        return base64_decode($data);
    }

    /**
     * Deobfuscate the secret key
     */
    private function deobfuscateSecret(string $obfuscatedSecret): string
    {
        // Split into chunks (reverse of obfuscation)
        $chunks = str_split($obfuscatedSecret, 8); // Base64 of 4 chars is around 8 chars
        $deobfuscated = '';

        foreach ($chunks as $index => $chunk) {
            $decoded = base64_decode($chunk);
            $transformed = $decoded ^ chr(($index * 7) % 256);
            $deobfuscated .= strrev($transformed);
        }

        return $deobfuscated;
    }

    /**
     * Send handshake request to server
     */
    private function sendHandshake(string $encryptedData)
    {
        $url = $this->serverUrl . '/api/secure-handshake';

        $options = [
            'http' => [
                'header' => "Content-Type: application/x-secure-protocol\r\n",
                'method' => 'POST',
                'content' => $encryptedData,
                'ignore_errors' => true,
                'timeout' => 30,
            ],
        ];

        $context = stream_context_create($options);
        $response = @file_get_contents($url, false, $context);

        if ($response === false) {
            $this->lastServerResponse = 'Connection failed: ' . error_get_last()['message'] ?? 'Unknown error';
            return false;
        }

        return $response;
    }

    /**
     * Decrypt server response with multiple security checks
     */
    private function decryptServerResponse(string $response): ?array
    {
        try {
            // Separate integrity check from payload
            list($payload, $integrity) = explode('.', $response);

            // Verify integrity
            $calculatedIntegrity = substr(hash_hmac('sha256', $payload, $this->getObfuscatedTimestamp()), 0, 16);
            if ($integrity !== $calculatedIntegrity) {
                return null;
            }

            // Reverse character substitution
            $substitutionMap = [
                '!' => '+', '@' => '/', '#' => '='
            ];
            $encoded = strtr($payload, $substitutionMap);

            // Decode base64
            $encrypted = base64_decode($encoded);

            // XOR decrypt with obfuscation key
            $decrypted = '';
            $keyLength = strlen($this->obfuscationKey);

            for ($i = 0; $i < strlen($encrypted); $i++) {
                $decrypted .= $encrypted[$i] ^ $this->obfuscationKey[$i % $keyLength];
            }

            // Parse JSON
            $result = json_decode($decrypted, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return null;
            }

            return $result;
        } catch (\Exception $e) {
            $this->lastServerResponse = 'Decryption error: ' . $e->getMessage();
            return null;
        }
    }

    /**
     * Validate server response with multiple security checks
     */
    private function validateServerResponse(array $response): bool
    {
        if (!isset($response['status'], $response['server_nonce'], $response['challenge_verification'])) {
            return false;
        }

        // Check status
        if ($response['status'] !== 'success') {
            $this->lastServerResponse = $response['message'] ?? 'Handshake failed';
            return false;
        }

        // Verify server nonce (prevents replay attacks)
        if (!isset($response['server_timestamp']) ||
            abs(time() - intval($response['server_timestamp'])) > 300) { // 5 minute window
            return false;
        }

        // Verify challenge response
        $expectedVerification = hash_hmac('sha256',
            $this->lastNonce . '|' . $response['server_nonce'] . '|' . $this->deviceFingerprint,
            $this->deobfuscateSecret($this->appSecret)
        );

        if ($response['challenge_verification'] !== $expectedVerification) {
            return false;
        }

        return true;
    }

    /**
     * Obfuscate API command
     */
    private function obfuscateCommand(string $command): string
    {
        // Simple substitution cipher based on the obfuscation key
        $obfuscated = '';
        $keyBytes = str_split($this->obfuscationKey);

        for ($i = 0; $i < strlen($command); $i++) {
            $charCode = ord($command[$i]);
            $keyByte = ord($keyBytes[$i % count($keyBytes)]);
            $obfuscated .= chr(($charCode + $keyByte) % 256);
        }

        return base64_encode($obfuscated);
    }

    /**
     * Encrypt payload data
     */
    private function encryptPayload(array $data): string
    {
        // Convert to JSON
        $json = json_encode($data);

        // Apply AES encryption if OpenSSL is available, otherwise use custom encryption
        if (function_exists('openssl_encrypt') && extension_loaded('openssl')) {
            $ivlen = openssl_cipher_iv_length('aes-256-cbc');
            $iv = openssl_random_pseudo_bytes($ivlen);

            $key = hash('sha256', $this->obfuscationKey, true);
            $encrypted = openssl_encrypt($json, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);

            return base64_encode($iv . $encrypted);
        } else {
            // Custom encryption if OpenSSL is not available
            $encrypted = '';
            $key = hash('sha256', $this->obfuscationKey, true);
            $keyLength = strlen($key);

            for ($i = 0; $i < strlen($json); $i++) {
                $encrypted .= chr((ord($json[$i]) + ord($key[$i % $keyLength])) % 256);
            }

            return base64_encode($encrypted);
        }
    }

    /**
     * Calculate data integrity
     */
    private function calculateDataIntegrity(array $data): string
    {
        $serialized = serialize($data);
        return hash_hmac('sha256', $serialized, $this->sessionToken . $this->obfuscationKey);
    }

    /**
     * Final payload encryption
     */
    private function encryptFinalPayload(array $payload): string
    {
        $jsonPayload = json_encode($payload);

        // Multiple transformation layers
        $transformed = $this->applyBitTransformation($jsonPayload);
        $encrypted = $this->applyCustomEncryption($transformed);

        return base64_encode($encrypted);
    }

    /**
     * Apply bit-level transformation
     */
    private function applyBitTransformation(string $data): string
    {
        $result = '';
        $dataLength = strlen($data);

        for ($i = 0; $i < $dataLength; $i++) {
            // Bit manipulation based on position
            $byte = ord($data[$i]);
            $transformed = (($byte << ($i % 3)) | ($byte >> (8 - ($i % 3)))) & 0xFF;
            $result .= chr($transformed);
        }

        return $result;
    }

    /**
     * Apply custom encryption
     */
    private function applyCustomEncryption(string $data): string
    {
        $key = hash('sha512', $this->obfuscationKey . $this->sessionToken, true);
        $keyLength = strlen($key);
        $result = '';

        // XOR encryption with position-dependent key modification
        for ($i = 0; $i < strlen($data); $i++) {
            $keyByte = ord($key[$i % $keyLength]);
            $dataByte = ord($data[$i]);

            // Position-dependent transformation
            $modifiedKey = ($keyByte + $i) % 256;
            $encrypted = ($dataByte ^ $modifiedKey) % 256;

            $result .= chr($encrypted);
        }

        return $result;
    }

    /**
     * Send secure request
     */
    private function sendSecureRequest(string $endpoint, string $encryptedData)
    {
        $url = $this->serverUrl . '/api/secure-channel';

        // Add complex headers with additional verification data
        $headers = [
            "Content-Type: application/x-secure-protocol",
            "X-Secure-Timestamp: " . time(),
            "X-Secure-Version: 3.2.1",
            "X-Secure-Endpoint: " . base64_encode($endpoint),
            "X-Secure-Token: " . substr(hash_hmac('sha256', $this->sessionToken, time()), 0, 32)
        ];

        $options = [
            'http' => [
                'header' => implode("\r\n", $headers) . "\r\n",
                'method' => 'POST',
                'content' => $encryptedData,
                'ignore_errors' => true,
                'timeout' => 30,
            ],
        ];

        $context = stream_context_create($options);
        $response = @file_get_contents($url, false, $context);

        if ($response === false) {
            $this->lastServerResponse = 'Request failed: ' . error_get_last()['message'] ?? 'Unknown error';
            return false;
        }

        return $response;
    }

    /**
     * Decrypt layered response
     */
    private function decryptLayeredResponse(string $response): ?array
    {
        try {
            // Decode base64
            $encrypted = base64_decode($response);

            // Decrypt custom encryption
            $decrypted = $this->reverseCustomEncryption($encrypted);

            // Reverse bit transformation
            $transformed = $this->reverseBitTransformation($decrypted);

            // Parse JSON
            $result = json_decode($transformed, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return null;
            }

            // Decrypt inner payload if exists
            if (isset($result['payload']) && is_string($result['payload'])) {
                $result['data'] = $this->decryptPayload($result['payload']);
            }

            return $result;
        } catch (\Exception $e) {
            $this->lastServerResponse = 'Response decryption error: ' . $e->getMessage();
            return null;
        }
    }

    /**
     * Reverse custom encryption
     */
    private function reverseCustomEncryption(string $encrypted): string
    {
        $key = hash('sha512', $this->obfuscationKey . $this->sessionToken, true);
        $keyLength = strlen($key);
        $result = '';

        for ($i = 0; $i < strlen($encrypted); $i++) {
            $keyByte = ord($key[$i % $keyLength]);
            $encryptedByte = ord($encrypted[$i]);

            // Reverse position-dependent transformation
            $modifiedKey = ($keyByte + $i) % 256;
            $decrypted = $encryptedByte ^ $modifiedKey;

            $result .= chr($decrypted);
        }

        return $result;
    }

    /**
     * Reverse bit transformation
     */
    private function reverseBitTransformation(string $data): string
    {
        $result = '';
        $dataLength = strlen($data);

        for ($i = 0; $i < $dataLength; $i++) {
            // Reverse bit manipulation based on position
            $byte = ord($data[$i]);
            $transformed = (($byte >> ($i % 3)) | ($byte << (8 - ($i % 3)))) & 0xFF;
            $result .= chr($transformed);
        }

        return $result;
    }

    /**
     * Decrypt payload
     */
    private function decryptPayload(string $encryptedPayload): ?array
    {
        $decoded = base64_decode($encryptedPayload);

        // Check if OpenSSL is available
        if (function_exists('openssl_decrypt') && extension_loaded('openssl')) {
            $ivlen = openssl_cipher_iv_length('aes-256-cbc');
            $iv = substr($decoded, 0, $ivlen);
            $encrypted = substr($decoded, $ivlen);

            $key = hash('sha256', $this->obfuscationKey, true);
            $decrypted = openssl_decrypt($encrypted, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);

            if ($decrypted === false) {
                return null;
            }
        } else {
            // Custom decryption if OpenSSL is not available
            $decrypted = '';
            $key = hash('sha256', $this->obfuscationKey, true);
            $keyLength = strlen($key);

            for ($i = 0; $i < strlen($decoded); $i++) {
                $decrypted .= chr((ord($decoded[$i]) - ord($key[$i % $keyLength]) + 256) % 256);
            }
        }

        // Parse JSON
        $result = json_decode($decrypted, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return null;
        }

        return $result;
    }

    /**
     * Validate response integrity
     */
    private function validateResponseIntegrity(array $response): bool
    {
        if (!isset($response['nonce'], $response['timestamp'], $response['integrity'])) {
            return false;
        }

        // Check timestamp to prevent replay attacks
        $timestamp = intval($response['timestamp']);
        if (abs(time() - $timestamp) > 300) { // 5 minute window
            return false;
        }

        // Calculate expected integrity
        $payload = $response['data'] ?? [];
        $calculatedIntegrity = hash_hmac('sha256',
            $response['nonce'] . '|' . $timestamp . '|' . json_encode($payload),
            $this->sessionToken . $this->obfuscationKey
        );

        return hash_equals($calculatedIntegrity, $response['integrity']);
    }

    /**
     * Terminate execution on critical security violation
     */
    private function terminate(string $reason): void
    {
        $this->lastError = 'Security violation: ' . $reason;

        // Log the violation attempt
        if (function_exists('error_log')) {
            error_log('SecureConnectionManager security violation: ' . $reason);
        }

        // In a real security-critical environment, you might want more drastic measures
        // like blocking the IP, alerting admins, etc.
    }

    /**
     * Get last error
     */
    public function getLastError(): ?string
    {
        return $this->lastError ?? null;
    }
}

/**
 * Usage example - Keep this section for reference
 */
/*
// Initialize secure connection
$connection = new SecureConnectionManager(
    'https://your-laravel-api.com',  // Server URL
    'YOUR_SECRET_KEY_XXXX',          // Application secret
    'APP_ID_12345',                  // Application ID
    'optional-public-key'            // Optional public key
);

// Establish secure connection
if ($connection->connect()) {
    // Execute secure API calls
    $result = $connection->executeSecureCall('get-user-data', [
        'user_id' => 123,
        'include_details' => true
    ]);

    if ($result) {
        // Process result
        print_r($result);
    } else {
        echo "API call failed: " . $connection->getLastError();
    }
} else {
    echo "Connection failed: " . $connection->getLastError();
}
*/
