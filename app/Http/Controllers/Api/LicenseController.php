<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\License;
use App\Services\LicenseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LicenseController extends Controller
{
    protected $licenseService;

    public function __construct(LicenseService $licenseService)
    {
        $this->licenseService = $licenseService;
    }

    /**
     * Validate a license key
     */
    public function validate(Request $request)
    {
        return $this->validateLicenseKey($request);
    }

    /**
     * Validate a license key
     */
    public function validateLicenseKey(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'license_key' => 'required|string',
            'domain' => 'nullable|string',
            'product_id' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->licenseService->validateLicense(
            $request->license_key,
            $request->domain,
            $request->product_id
        );

        return response()->json($result, $result['valid'] ? 200 : 400);
    }

    /**
     * Activate a license with an activation code
     */
    public function activate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'license_key' => 'required|string',
            'activation_code' => 'required|string',
            'domain' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->licenseService->activateLicense(
            $request->license_key,
            $request->activation_code,
            $request->domain
        );

        return response()->json($result, $result['success'] ? 200 : 400);
    }

    /**
     * Deactivate a license
     */
    public function deactivate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'license_key' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->licenseService->deactivateLicense($request->license_key);

        return response()->json($result, $result['success'] ? 200 : 400);
    }

    /**
     * Check license status
     */
    public function check(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'license_key' => 'required|string',
            'product_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $license = License::where('license_key', $request->license_key)
                        ->where('product_id', $request->product_id)
                        ->first();

        if (!$license) {
            return response()->json([
                'success' => false,
                'message' => 'License not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'License found',
            'data' => [
                'is_active' => $license->is_active,
                'is_permanent' => $license->is_permanent,
                'expires_at' => $license->expires_at,
                'product' => $license->product ? [
                    'name' => $license->product->name,
                    'version' => $license->product->version,
                ] : null,
            ]
        ]);
    }

    /**
     * Generate a secure token for a license
     */
    public function generateToken(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'license_key' => 'required|string',
            'product_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $license = License::where('license_key', $request->license_key)
                          ->where('product_id', $request->product_id)
                          ->first();

        if (!$license) {
            return response()->json([
                'success' => false,
                'message' => 'License not found',
            ], 404);
        }

        if (!$license->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'License is not active',
            ], 400);
        }

        if (!$license->is_permanent && $license->isExpired()) {
            return response()->json([
                'success' => false,
                'message' => 'License has expired',
            ], 400);
        }

        $token = $this->licenseService->generateSecureToken($license);

        return response()->json([
            'success' => true,
            'message' => 'Token generated successfully',
            'token' => $token,
            'expires_in' => 86400, // 24 hours in seconds
        ]);
    }

    /**
     * Verify a secure token
     */
    public function verifyToken(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->licenseService->verifySecureToken($request->token);

        if (!$result['valid']) {
            return response()->json([
                'success' => false,
                'message' => $result['message'],
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => $result['message'],
            'data' => [
                'license' => [
                    'id' => $result['license']->id,
                    'is_active' => $result['license']->is_active,
                    'is_permanent' => $result['license']->is_permanent,
                    'expires_at' => $result['license']->expires_at,
                ],
                'product' => $result['license']->product ? [
                    'name' => $result['license']->product->name,
                    'product_id' => $result['license']->product->product_id,
                    'version' => $result['license']->product->version,
                    'features' => $result['license']->product->features,
                ] : null,
            ]
        ]);
    }

    /**
     * Check if license exists and is activated for the specified domain
     */
    public function checkDomainLicense(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'license_key' => 'required|string',
            'domain' => 'required|string',
            'product_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->licenseService->checkDomainLicense(
            $request->license_key,
            $request->domain,
            $request->product_id
        );

        return response()->json($result, $result['success'] ? 200 : 400);
    }
}
