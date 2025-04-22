<?php

namespace App\Services;

use App\Models\License;
use App\Models\LicenseLog;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class LicenseService
{
    public function createLicense(array $data): License
    {
        $licenseKey = $data['license_key'] ?? License::generateLicenseKey();
        $activationCode = License::generateActivationCode();

        $license = License::create([
            'license_key' => $licenseKey,
            'client_name' => $data['client_name'],
            'product_id' => $data['product_id'],
            'domain' => $data['domain'] ?? null,
            'is_active' => $data['is_active'] ?? false,
            'is_permanent' => $data['is_permanent'] ?? false,
            'expires_at' => isset($data['expires_at']) ? Carbon::parse($data['expires_at']) : null,
            'activation_code' => $activationCode,
            'metadata' => $data['metadata'] ?? null,
        ]);

        return $license;
    }

    public function validateLicense(string $licenseKey, string $domain = null, string $productId = null): array
    {
        $license = License::where('license_key', $licenseKey)->first();

        if (!$license) {
            $this->logLicenseAction(null, 'validate', false, [
                'license_key' => $licenseKey,
                'domain' => $domain,
                'product_id' => $productId,
            ], ['message' => 'License not found']);

            return [
                'valid' => false,
                'message' => 'License not found',
            ];
        }

        // Check if product is active
        $product = $license->product;
        if (!$product || !$product->is_active) {
            $this->logLicenseAction($license->id, 'validate', false, [
                'license_key' => $licenseKey,
                'domain' => $domain,
                'product_id' => $productId,
            ], ['message' => 'Product is not active']);

            return [
                'valid' => false,
                'message' => 'Product is not active',
            ];
        }

        // Check if product ID matches
        if ($productId && $license->product_id !== $productId) {
            $this->logLicenseAction($license->id, 'validate', false, [
                'license_key' => $licenseKey,
                'domain' => $domain,
                'product_id' => $productId,
            ], ['message' => 'Product ID mismatch']);

            return [
                'valid' => false,
                'message' => 'Product ID mismatch',
            ];
        }

        // Check domain if provided and set
        if ($domain && $license->domain && $license->domain !== $domain) {
            $this->logLicenseAction($license->id, 'validate', false, [
                'license_key' => $licenseKey,
                'domain' => $domain,
                'product_id' => $productId,
            ], ['message' => 'Domain mismatch']);

            return [
                'valid' => false,
                'message' => 'Domain mismatch',
            ];
        }

        // Check if license is active
        if (!$license->is_active) {
            $this->logLicenseAction($license->id, 'validate', false, [
                'license_key' => $licenseKey,
                'domain' => $domain,
                'product_id' => $productId,
            ], ['message' => 'License is not active']);

            return [
                'valid' => false,
                'message' => 'License is not active',
            ];
        }

        // Check expiration
        if (!$license->is_permanent && $license->isExpired()) {
            $this->logLicenseAction($license->id, 'validate', false, [
                'license_key' => $licenseKey,
                'domain' => $domain,
                'product_id' => $productId,
            ], ['message' => 'License has expired']);

            return [
                'valid' => false,
                'message' => 'License has expired',
            ];
        }

        $responseData = [
            'license_id' => $license->id,
            'product' => $license->product->only(['name', 'product_id', 'version', 'features']),
            'is_permanent' => $license->is_permanent,
            'expires_at' => $license->expires_at,
        ];

        $this->logLicenseAction($license->id, 'validate', true, [
            'license_key' => $licenseKey,
            'domain' => $domain,
            'product_id' => $productId,
        ], $responseData);

        return [
            'valid' => true,
            'message' => 'License is valid',
            'data' => $responseData,
        ];
    }

    public function activateLicense(string $licenseKey, string $activationCode, string $domain = null): array
    {
        $license = License::where('license_key', $licenseKey)->first();

        if (!$license) {
            return [
                'success' => false,
                'message' => 'License not found',
            ];
        }

        // Verify activation code
        if ($license->activation_code !== $activationCode) {
            $this->logLicenseAction($license->id, 'activate', false, [
                'license_key' => $licenseKey,
                'activation_code' => '***',
                'domain' => $domain,
            ], ['message' => 'Invalid activation code']);

            return [
                'success' => false,
                'message' => 'Invalid activation code',
            ];
        }

        // Update license details
        $license->is_active = true;
        $license->domain = $domain ?? $license->domain;
        $license->ip_address = request()->ip();
        $license->activated_at = now();
        $license->save();

        $responseData = [
            'license_id' => $license->id,
            'product' => $license->product->only(['name', 'product_id', 'version', 'features']),
            'is_permanent' => $license->is_permanent,
            'expires_at' => $license->expires_at,
        ];

        $this->logLicenseAction($license->id, 'activate', true, [
            'license_key' => $licenseKey,
            'activation_code' => '***',
            'domain' => $domain,
        ], $responseData);

        return [
            'success' => true,
            'message' => 'License activated successfully',
            'data' => $responseData,
        ];
    }

    public function deactivateLicense(string $licenseKey): array
    {
        $license = License::where('license_key', $licenseKey)->first();

        if (!$license) {
            return [
                'success' => false,
                'message' => 'License not found',
            ];
        }

        $license->is_active = false;
        $license->save();

        $this->logLicenseAction($license->id, 'deactivate', true, [
            'license_key' => $licenseKey,
        ], ['message' => 'License deactivated']);

        return [
            'success' => true,
            'message' => 'License deactivated successfully',
        ];
    }

    private function logLicenseAction($licenseId, $action, $success, $request = null, $response = null)
    {
        return LicenseLog::logAction($licenseId, $action, $success, $request, $response);
    }

    public function generateSecureToken(License $license): string
    {
        $data = [
            'license_key' => $license->license_key,
            'product_id' => $license->product_id,
            'timestamp' => now()->timestamp,
            'random' => Str::random(8),
        ];

        return Crypt::encryptString(json_encode($data));
    }

    public function verifySecureToken(string $token): array
    {
        try {
            $data = json_decode(Crypt::decryptString($token), true);

            // Check if token is expired (24 hours validity)
            if (Carbon::createFromTimestamp($data['timestamp'])->diffInHours(now()) > 24) {
                return [
                    'valid' => false,
                    'message' => 'Token expired',
                ];
            }

            $license = License::where('license_key', $data['license_key'])
                             ->where('product_id', $data['product_id'])
                             ->first();

            if (!$license) {
                return [
                    'valid' => false,
                    'message' => 'Invalid license information',
                ];
            }

            return [
                'valid' => true,
                'message' => 'Token is valid',
                'license' => $license,
            ];
        } catch (\Exception $e) {
            return [
                'valid' => false,
                'message' => 'Invalid token',
            ];
        }
    }

    /**
     * Check if license exists, is active, and is activated for the specified domain
     *
     * @param string $licenseKey The license key to check
     * @param string $domain The domain to check against
     * @param string $productId The product ID to check
     * @return array The result of the check
     */
    public function checkDomainLicense(string $licenseKey, string $domain, string $productId): array
    {
        $license = License::where('license_key', $licenseKey)
                          ->where('product_id', $productId)
                          ->first();

        if (!$license) {
            $this->logLicenseAction(null, 'check_domain', false, [
                'license_key' => $licenseKey,
                'domain' => $domain,
                'product_id' => $productId,
            ], ['message' => 'License not found']);

            return [
                'success' => false,
                'message' => 'License not found',
            ];
        }

        // Check if license is active
        if (!$license->is_active) {
            $this->logLicenseAction($license->id, 'check_domain', false, [
                'license_key' => $licenseKey,
                'domain' => $domain,
                'product_id' => $productId,
            ], ['message' => 'License is not active']);

            return [
                'success' => false,
                'message' => 'License is not active',
            ];
        }

        // Check if domain matches
        if ($license->domain !== $domain) {
            $this->logLicenseAction($license->id, 'check_domain', false, [
                'license_key' => $licenseKey,
                'domain' => $domain,
                'product_id' => $productId,
            ], ['message' => 'Domain mismatch']);

            return [
                'success' => false,
                'message' => 'Domain mismatch',
            ];
        }

        // Check expiration
        if (!$license->is_permanent && $license->isExpired()) {
            $this->logLicenseAction($license->id, 'check_domain', false, [
                'license_key' => $licenseKey,
                'domain' => $domain,
                'product_id' => $productId,
            ], ['message' => 'License has expired']);

            return [
                'success' => false,
                'message' => 'License has expired',
            ];
        }

        $responseData = [
            'license_id' => $license->id,
            'product' => $license->product->only(['name', 'product_id', 'version', 'features']),
            'is_permanent' => $license->is_permanent,
            'expires_at' => $license->expires_at,
            'domain' => $license->domain,
            'activated_at' => $license->activated_at,
        ];

        $this->logLicenseAction($license->id, 'check_domain', true, [
            'license_key' => $licenseKey,
            'domain' => $domain,
            'product_id' => $productId,
        ], $responseData);

        return [
            'success' => true,
            'message' => 'License is valid for this domain',
            'data' => $responseData,
        ];
    }
}
