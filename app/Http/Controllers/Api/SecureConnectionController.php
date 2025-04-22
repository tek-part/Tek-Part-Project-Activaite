<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\License;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SecureConnectionController extends Controller
{
    private $obfuscationKey;
    private $appSecrets = [];

    public function __construct()
    {
        // Load registered app secrets (in production, this would be from database)
        $this->loadAppSecrets();

        // Generate server-side obfuscation key (would be constant in real implementation)
        $this->generateObfuscationKey();
    }

    /**
     * Handle secure handshake
     */
    public function secureHandshake(Request $request)
    {
        try {
            // Get encrypted payload
            $encryptedData = $request->getContent();
            if (empty($encryptedData)) {
                return $this->sendErrorResponse('Invalid handshake data', 400);
            }

            // Decode and verify the handshake data
            $handshakeData = $this->decryptHandshakeData($encryptedData);
            if (!$handshakeData) {
                return $this->sendErrorResponse('Handshake decryption failed', 400);
            }

            // Extract and validate the required fields
            $requiredFields = ['app_id', 'timestamp', 'nonce', 'fingerprint', 'challenge_response'];
            foreach ($requiredFields as $field) {
                if (!isset($handshakeData[$field])) {
                    return $this->sendErrorResponse('Missing required field: ' . $field, 400);
                }
            }

            // Verify app ID
            $appId = $handshakeData['app_id'];
            if (!isset($this->appSecrets[$appId])) {
                // Log failed verification attempt
                Log::warning('Secure API: Unknown app ID attempted connection', [
                    'app_id' => $appId,
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);

                return $this->sendErrorResponse('Invalid application ID', 403);
            }

            // Verify timestamp (prevent replay attacks)
            if (!$this->verifyTimestamp($handshakeData['timestamp'])) {
                return $this->sendErrorResponse('Invalid timestamp', 403);
            }

            // Verify challenge response
            if (!$this->verifyChallenge(
                $handshakeData,
                $appId
            )) {
                return $this->sendErrorResponse('Challenge verification failed', 403);
            }

            // Generate session token
            $sessionToken = $this->generateSessionToken($appId, $handshakeData['fingerprint']);

            // Create response
            $responseData = [
                'status' => 'success',
                'server_nonce' => Str::random(32),
                'server_timestamp' => time(),
                'session_token' => $sessionToken,
                'challenge_verification' => $this->generateChallengeVerification(
                    $handshakeData['nonce'],
                    Str::random(32),
                    $handshakeData['fingerprint'],
                    $appId
                ),
                'expires_in' => 3600, // Session token expires in 1 hour
            ];

            // Log successful handshake
            Log::info('Secure API: Successful handshake', [
                'app_id' => $appId,
                'fingerprint_hash' => substr(md5($handshakeData['fingerprint']), 0, 8),
                'ip' => $request->ip(),
            ]);

            return $this->encryptResponse($responseData);
        } catch (\Exception $e) {
            Log::error('Secure API: Handshake error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'ip' => $request->ip(),
            ]);

            return $this->sendErrorResponse('Server error', 500);
        }
    }

    /**
     * Handle secure channel requests
     */
    public function secureChannel(Request $request)
    {
        try {
            // Get encrypted payload
            $encryptedData = $request->getContent();
            if (empty($encryptedData)) {
                return $this->sendErrorResponse('Invalid request data', 400);
            }

            // Check secure headers
            if (!$this->validateSecureHeaders($request)) {
                return $this->sendErrorResponse('Invalid security headers', 403);
            }

            // Decrypt the request
            $requestData = $this->decryptLayeredRequest($encryptedData);
            if (!$requestData) {
                return $this->sendErrorResponse('Request decryption failed', 400);
            }

            // Extract and validate the required fields
            $requiredFields = ['api_command', 'timestamp', 'session_ref', 'nonce', 'payload'];
            foreach ($requiredFields as $field) {
                if (!isset($requestData[$field])) {
                    return $this->sendErrorResponse('Missing required field: ' . $field, 400);
                }
            }

            // Verify session token
            if (!$this->verifySessionToken($requestData['session_ref'])) {
                return $this->sendErrorResponse('Invalid session token', 403);
            }

            // Verify timestamp to prevent replay attacks
            if (!$this->verifyTimestamp($requestData['timestamp'])) {
                return $this->sendErrorResponse('Invalid timestamp', 403);
            }

            // Process the API command
            $command = $this->deobfuscateCommand($requestData['api_command']);
            $payload = $this->decryptPayload($requestData['payload']);

            // Execute the requested operation
            $result = $this->executeCommand($command, $payload);

            // Generate response with nonce to prevent replay
            $responseData = [
                'status' => 'success',
                'nonce' => Str::random(32),
                'timestamp' => time(),
                'data' => $result,
                'integrity' => $this->generateResponseIntegrity(
                    Str::random(32),
                    time(),
                    $result,
                    $requestData['session_ref']
                ),
            ];

            return $this->encryptLayeredResponse($responseData, $requestData['session_ref']);
        } catch (\Exception $e) {
            Log::error('Secure API: Channel error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'ip' => $request->ip(),
            ]);

            return $this->sendErrorResponse('Server error', 500);
        }
    }

    /**
     * Decrypt handshake data
     */
    private function decryptHandshakeData(string $encryptedData): ?array
    {
        try {
            // Split data and integrity check
            list($encodedData, $integrity) = explode('.', $encryptedData);

            // Reverse character substitution
            $substitutionMap = [
                '!' => '+', '@' => '/', '#' => '='
            ];
            $encoded = strtr($encodedData, $substitutionMap);

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
            Log::error('Secure API: Decryption error', [
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Generate server-side obfuscation key
     */
    private function generateObfuscationKey(): void
    {
        // In production, this would be stored and constant
        // For the prototype, we'll generate it based on app configuration
        $seed = [
            config('app.key'),
            config('app.name'),
            config('app.env'),
            config('app.debug') ? '1' : '0',
        ];

        $seedStr = implode('|', $seed);

        // Generate obfuscation key
        $this->obfuscationKey = hash('sha512', $seedStr, true);

        // Additional transformation
        $this->obfuscationKey = hash_hmac('sha256', $this->obfuscationKey, $seedStr, true);
    }

    /**
     * Encrypt response data
     */
    private function encryptResponse(array $data): \Illuminate\Http\Response
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

        $finalData = $encoded . '.' . substr($integrity, 0, 16);

        return response($finalData, 200)
            ->header('Content-Type', 'application/x-secure-protocol');
    }

    /**
     * Get obfuscated timestamp
     */
    private function getObfuscatedTimestamp(): string
    {
        $time = time();
        $timeKey = floor($time / 30); // Changes every 30 seconds

        return hash_hmac('sha256', (string)$timeKey, $this->obfuscationKey);
    }

    /**
     * Send error response
     */
    private function sendErrorResponse(string $message, int $status = 400): \Illuminate\Http\Response
    {
        $response = [
            'status' => 'error',
            'message' => $message,
            'timestamp' => time(),
        ];

        return $this->encryptResponse($response)->setStatusCode($status);
    }

    /**
     * Verify timestamp to prevent replay attacks
     */
    private function verifyTimestamp(string $timestamp): bool
    {
        // In a real implementation, this would be more complex
        // For prototype, we just verify it's a recent timestamp (within 5 minutes)

        // Get current timestamp key (changes every 30 seconds)
        $currentKey = floor(time() / 30);

        // Calculate keys for the last 10 intervals (5 minutes)
        $validKeys = [];
        for ($i = 0; $i <= 10; $i++) {
            $validKeys[] = hash_hmac('sha256', (string)($currentKey - $i), $this->obfuscationKey);
        }

        return in_array($timestamp, $validKeys);
    }

    /**
     * Verify the challenge response
     */
    private function verifyChallenge(array $handshakeData, string $appId): bool
    {
        // Get app secret
        $appSecret = $this->appSecrets[$appId];

        // Build components
        $components = [
            $appId,
            $handshakeData['nonce'],
            $handshakeData['fingerprint'],
            $handshakeData['timestamp']
        ];

        // Mix the components with the secret
        $mixed = implode('|', $components) . '|' . $appSecret;

        // Apply multiple rounds of hashing (same as client)
        $result = '';
        $temp = $mixed;

        for ($i = 0; $i < 3; $i++) {
            $temp = hash_hmac('sha256', $temp, $this->obfuscationKey . $i);
            $result .= $temp;
        }

        $expected = substr($result, 0, 64);

        return hash_equals($expected, $handshakeData['challenge_response']);
    }

    /**
     * Generate session token
     */
    private function generateSessionToken(string $appId, string $fingerprint): string
    {
        $sessionData = [
            'app_id' => $appId,
            'fingerprint' => $fingerprint,
            'created_at' => time(),
            'expires_at' => time() + 3600, // 1 hour
            'random' => Str::random(16),
        ];

        // In production, this would be stored in database or cache
        // For prototype, we'll just encrypt it

        return Crypt::encryptString(json_encode($sessionData));
    }

    /**
     * Generate challenge verification response
     */
    private function generateChallengeVerification(string $clientNonce, string $serverNonce, string $fingerprint, string $appId): string
    {
        $appSecret = $this->appSecrets[$appId];

        return hash_hmac('sha256',
            $clientNonce . '|' . $serverNonce . '|' . $fingerprint,
            $appSecret
        );
    }

    /**
     * Generate response integrity hash
     */
    private function generateResponseIntegrity(string $nonce, int $timestamp, array $data, string $sessionToken): string
    {
        return hash_hmac('sha256',
            $nonce . '|' . $timestamp . '|' . json_encode($data),
            $sessionToken . $this->obfuscationKey
        );
    }

    /**
     * Load application secrets
     */
    private function loadAppSecrets(): void
    {
        // In production, this would load from database
        // For prototype, we'll use hardcoded values

        $this->appSecrets = [
            'APP_ID_12345' => 'YOUR_SECRET_KEY_XXXX',
            // Add more app IDs and secrets as needed
        ];
    }

    /**
     * Verify session token
     */
    private function verifySessionToken(string $token): bool
    {
        try {
            $sessionData = json_decode(Crypt::decryptString($token), true);

            // Check if session is expired
            if (time() > ($sessionData['expires_at'] ?? 0)) {
                return false;
            }

            // Verify app ID is registered
            if (!isset($this->appSecrets[$sessionData['app_id'] ?? ''])) {
                return false;
            }

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Validate secure headers
     */
    private function validateSecureHeaders(Request $request): bool
    {
        // Check required headers
        $requiredHeaders = [
            'X-Secure-Timestamp',
            'X-Secure-Version',
            'X-Secure-Endpoint',
            'X-Secure-Token'
        ];

        foreach ($requiredHeaders as $header) {
            if (!$request->header($header)) {
                return false;
            }
        }

        // Verify timestamp is recent (within 5 minutes)
        $timestamp = (int)$request->header('X-Secure-Timestamp');
        if (abs(time() - $timestamp) > 300) {
            return false;
        }

        return true;
    }

    /**
     * Decrypt layered request
     */
    private function decryptLayeredRequest(string $encryptedData): ?array
    {
        try {
            // Decode base64
            $encrypted = base64_decode($encryptedData);

            // Decrypt custom encryption
            $decrypted = $this->reverseCustomEncryption($encrypted);

            // Reverse bit transformation
            $transformed = $this->reverseBitTransformation($decrypted);

            // Parse JSON
            $result = json_decode($transformed, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return null;
            }

            return $result;
        } catch (\Exception $e) {
            Log::error('Secure API: Request decryption error', [
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Reverse custom encryption (server-side implementation)
     */
    private function reverseCustomEncryption(string $encrypted): string
    {
        // We don't have sessionToken here, so we'll use a simplified approach
        // In production, this would handle session lookup or use a different approach

        $key = hash('sha512', $this->obfuscationKey, true);
        $keyLength = strlen($key);
        $result = '';

        for ($i = 0; $i < strlen($encrypted); $i++) {
            $keyByte = ord($key[$i % $keyLength]);
            $encryptedByte = ord($encrypted[$i]);

            // Reverse position-dependent transformation (simplified)
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
     * Deobfuscate command
     */
    private function deobfuscateCommand(string $obfuscatedCommand): string
    {
        $decoded = base64_decode($obfuscatedCommand);
        $keyBytes = str_split($this->obfuscationKey);
        $deobfuscated = '';

        for ($i = 0; $i < strlen($decoded); $i++) {
            $encryptedByte = ord($decoded[$i]);
            $keyByte = ord($keyBytes[$i % count($keyBytes)]);
            $deobfuscated .= chr((($encryptedByte - $keyByte) + 256) % 256);
        }

        return $deobfuscated;
    }

    /**
     * Decrypt payload
     */
    private function decryptPayload(string $encryptedPayload): ?array
    {
        try {
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
        } catch (\Exception $e) {
            Log::error('Secure API: Payload decryption error', [
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Execute API command
     */
    private function executeCommand(string $command, ?array $payload): array
    {
        // Implementation depends on the available commands
        switch ($command) {
            case 'get-license-info':
                return $this->getLicenseInfo($payload);

            case 'verify-product-access':
                return $this->verifyProductAccess($payload);

            case 'check-product-updates':
                return $this->checkProductUpdates($payload);

            default:
                return [
                    'error' => 'Unknown command',
                    'command' => $command,
                ];
        }
    }

    /**
     * Get license information
     */
    private function getLicenseInfo(array $payload): array
    {
        // Validate required fields
        if (!isset($payload['license_key'])) {
            return [
                'success' => false,
                'message' => 'Missing license key',
            ];
        }

        // Get license from database
        $license = License::where('license_key', $payload['license_key'])->first();

        if (!$license) {
            return [
                'success' => false,
                'message' => 'License not found',
            ];
        }

        // Return license information
        return [
            'success' => true,
            'license' => [
                'is_active' => $license->is_active,
                'is_permanent' => $license->is_permanent,
                'expires_at' => $license->expires_at ? $license->expires_at->toIso8601String() : null,
                'client_name' => $license->client_name,
            ],
            'product' => $license->product ? [
                'name' => $license->product->name,
                'product_id' => $license->product->product_id,
                'version' => $license->product->version,
            ] : null,
        ];
    }

    /**
     * Verify product access
     */
    private function verifyProductAccess(array $payload): array
    {
        // Validate required fields
        if (!isset($payload['license_key']) || !isset($payload['product_id'])) {
            return [
                'success' => false,
                'message' => 'Missing required fields',
            ];
        }

        // Get license from database
        $license = License::where('license_key', $payload['license_key'])
                        ->where('product_id', $payload['product_id'])
                        ->first();

        if (!$license) {
            return [
                'success' => false,
                'message' => 'Invalid license for this product',
            ];
        }

        // Check if license is active and not expired
        if (!$license->is_active) {
            return [
                'success' => false,
                'message' => 'License is not active',
            ];
        }

        if (!$license->is_permanent && $license->isExpired()) {
            return [
                'success' => false,
                'message' => 'License has expired',
            ];
        }

        // Return success with access details
        return [
            'success' => true,
            'message' => 'Product access verified',
            'access_level' => 'full', // Could be based on license type
            'features' => $license->product ? $license->product->features : [],
        ];
    }

    /**
     * Check for product updates
     */
    private function checkProductUpdates(array $payload): array
    {
        // Validate required fields
        if (!isset($payload['product_id']) || !isset($payload['current_version'])) {
            return [
                'success' => false,
                'message' => 'Missing required fields',
            ];
        }

        // Get product from database
        $product = Product::where('product_id', $payload['product_id'])->first();

        if (!$product) {
            return [
                'success' => false,
                'message' => 'Product not found',
            ];
        }

        // Compare versions
        $currentVersion = $payload['current_version'];
        $latestVersion = $product->version;

        $hasUpdate = version_compare($latestVersion, $currentVersion, '>');

        // Return update information
        return [
            'success' => true,
            'has_update' => $hasUpdate,
            'current_version' => $currentVersion,
            'latest_version' => $latestVersion,
            'update_url' => $hasUpdate ? route('api.product.download', ['id' => $product->id]) : null,
            'release_notes' => $hasUpdate ? $product->release_notes : null,
        ];
    }

    /**
     * Encrypt layered response
     */
    private function encryptLayeredResponse(array $responseData, string $sessionToken): \Illuminate\Http\Response
    {
        // Convert to JSON
        $jsonData = json_encode($responseData);

        // Apply bit transformation
        $transformed = $this->applyBitTransformation($jsonData);

        // Apply custom encryption
        $encrypted = $this->applyCustomEncryption($transformed, $sessionToken);

        // Encode to base64
        $encoded = base64_encode($encrypted);

        return response($encoded, 200)
            ->header('Content-Type', 'application/x-secure-protocol');
    }

    /**
     * Apply bit transformation
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
    private function applyCustomEncryption(string $data, string $sessionToken): string
    {
        // Create key based on obfuscation key and session token
        $key = hash('sha512', $this->obfuscationKey . $sessionToken, true);
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
}