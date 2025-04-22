<?php
/**
 * License Client SDK
 *
 * A simple SDK to integrate with the license system.
 *
 * Usage:
 * $licenseClient = new LicenseClient('https://your-license-server.com', 'YOUR-LICENSE-KEY', 'YOUR-PRODUCT-ID');
 * if ($licenseClient->validate()) {
 *     // License is valid, proceed with application
 * } else {
 *     die('License validation failed: ' . $licenseClient->getLastError());
 * }
 */

class LicenseClient
{
    private $apiUrl;
    private $licenseKey;
    private $productId;
    private $domain;
    private $lastError;
    private $lastResponse;

    /**
     * LicenseClient constructor
     *
     * @param string $apiUrl The base URL of the license server (e.g. https://example.com)
     * @param string $licenseKey The license key
     * @param string $productId The product ID
     * @param string|null $domain The domain for validation (defaults to current domain)
     */
    public function __construct(string $apiUrl, string $licenseKey, string $productId, string $domain = null)
    {
        $this->apiUrl = rtrim($apiUrl, '/');
        $this->licenseKey = $licenseKey;
        $this->productId = $productId;
        $this->domain = $domain ?? $this->getCurrentDomain();
        $this->lastError = null;
        $this->lastResponse = null;
    }

    /**
     * Validate the license
     *
     * @return bool True if the license is valid, false otherwise
     */
    public function validate(): bool
    {
        $endpoint = '/api/license/validate';
        $data = [
            'license_key' => $this->licenseKey,
            'product_id' => $this->productId,
            'domain' => $this->domain,
        ];

        $response = $this->makeRequest($endpoint, $data);
        if (!$response) {
            return false;
        }

        if (!isset($response['valid']) || $response['valid'] !== true) {
            $this->lastError = $response['message'] ?? 'License validation failed.';
            return false;
        }

        return true;
    }

    /**
     * Activate the license
     *
     * @param string $activationCode The activation code provided with the license
     * @return bool True if activation was successful, false otherwise
     */
    public function activate(string $activationCode): bool
    {
        $endpoint = '/api/license/activate';
        $data = [
            'license_key' => $this->licenseKey,
            'activation_code' => $activationCode,
            'domain' => $this->domain,
        ];

        $response = $this->makeRequest($endpoint, $data);
        if (!$response) {
            return false;
        }

        if (!isset($response['success']) || $response['success'] !== true) {
            $this->lastError = $response['message'] ?? 'License activation failed.';
            return false;
        }

        return true;
    }

    /**
     * Check the license status
     *
     * @return array|false License data if successful, false otherwise
     */
    public function check()
    {
        $endpoint = '/api/license/check';
        $data = [
            'license_key' => $this->licenseKey,
            'product_id' => $this->productId,
        ];

        $response = $this->makeRequest($endpoint, $data);
        if (!$response) {
            return false;
        }

        if (!isset($response['success']) || $response['success'] !== true) {
            $this->lastError = $response['message'] ?? 'License check failed.';
            return false;
        }

        return $response['data'] ?? false;
    }

    /**
     * Generate a secure token for offline validation
     *
     * @return string|false Token if successful, false otherwise
     */
    public function generateToken()
    {
        $endpoint = '/api/license/token/generate';
        $data = [
            'license_key' => $this->licenseKey,
            'product_id' => $this->productId,
        ];

        $response = $this->makeRequest($endpoint, $data);
        if (!$response) {
            return false;
        }

        if (!isset($response['success']) || $response['success'] !== true) {
            $this->lastError = $response['message'] ?? 'Token generation failed.';
            return false;
        }

        return $response['token'] ?? false;
    }

    /**
     * Verify a token
     *
     * @param string $token The token to verify
     * @return array|false License data if successful, false otherwise
     */
    public function verifyToken(string $token)
    {
        $endpoint = '/api/license/token/verify';
        $data = [
            'token' => $token,
        ];

        $response = $this->makeRequest($endpoint, $data);
        if (!$response) {
            return false;
        }

        if (!isset($response['success']) || $response['success'] !== true) {
            $this->lastError = $response['message'] ?? 'Token verification failed.';
            return false;
        }

        return $response['data'] ?? false;
    }

    /**
     * Check if license exists, is active, and matches the specified domain
     *
     * @return array|false License data if successful, false otherwise
     */
    public function checkDomain()
    {
        $endpoint = '/api/license/check-domain';
        $data = [
            'license_key' => $this->licenseKey,
            'product_id' => $this->productId,
            'domain' => $this->domain,
        ];

        $response = $this->makeRequest($endpoint, $data);
        if (!$response) {
            return false;
        }

        if (!isset($response['success']) || $response['success'] !== true) {
            $this->lastError = $response['message'] ?? 'Domain check failed.';
            return false;
        }

        return $response['data'] ?? false;
    }

    /**
     * Get the last error message
     *
     * @return string|null The last error message
     */
    public function getLastError(): ?string
    {
        return $this->lastError;
    }

    /**
     * Get the last response
     *
     * @return array|null The last response
     */
    public function getLastResponse()
    {
        return $this->lastResponse;
    }

    /**
     * Make an API request
     *
     * @param string $endpoint The API endpoint
     * @param array $data The data to send
     * @return array|false Response array if successful, false on failure
     */
    private function makeRequest(string $endpoint, array $data)
    {
        $url = $this->apiUrl . $endpoint;

        $options = [
            'http' => [
                'header' => "Content-Type: application/json\r\n",
                'method' => 'POST',
                'content' => json_encode($data),
                'ignore_errors' => true,
            ],
        ];

        $context = stream_context_create($options);
        $response = @file_get_contents($url, false, $context);

        if ($response === false) {
            $this->lastError = 'Failed to connect to license server.';
            return false;
        }

        $responseData = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->lastError = 'Invalid response from license server.';
            return false;
        }

        $this->lastResponse = $responseData;
        return $responseData;
    }

    /**
     * Get the current domain
     *
     * @return string Current domain
     */
    private function getCurrentDomain(): string
    {
        if (php_sapi_name() === 'cli') {
            return 'cli';
        }

        return isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'unknown';
    }
}
