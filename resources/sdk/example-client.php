<?php
/**
 * Example License Client Implementation
 *
 * This example demonstrates how to use the License Client SDK
 * to implement license validation in your application.
 */

// Include the license client SDK
require_once 'license-client.php';

// Define constants
define('LICENSE_SERVER_URL', 'https://your-license-server.com');
define('LICENSE_KEY', 'YOUR-LICENSE-KEY-HERE');
define('PRODUCT_ID', 'your-product-id');
define('LICENSE_FILE', __DIR__ . '/license.dat');

// Create a function for license validation
function validateLicense() {
    // Check if we have a cached license token
    if (file_exists(LICENSE_FILE)) {
        $tokenData = @file_get_contents(LICENSE_FILE);
        if ($tokenData) {
            try {
                // Parse the token data
                $data = json_decode($tokenData, true);
                if (isset($data['token']) && isset($data['expires'])) {
                    // Check if token is still valid
                    if ($data['expires'] > time()) {
                        // Verify the token against the server
                        $client = new LicenseClient(LICENSE_SERVER_URL, LICENSE_KEY, PRODUCT_ID);
                        $result = $client->verifyToken($data['token']);
                        if ($result) {
                            return true;
                        }
                    }
                }
            } catch (Exception $e) {
                // Token parsing failed, we'll continue to online validation
            }
        }
    }

    // If cached license doesn't exist or is invalid, try online validation
    $client = new LicenseClient(LICENSE_SERVER_URL, LICENSE_KEY, PRODUCT_ID);
    if ($client->validate()) {
        // Save a token for future offline validation
        $token = $client->generateToken();
        if ($token) {
            // Store the token with a 24-hour expiration
            $tokenData = json_encode([
                'token' => $token,
                'expires' => time() + 86400
            ]);
            @file_put_contents(LICENSE_FILE, $tokenData);
        }
        return true;
    }

    return false;
}

// Function to activate the license
function activateLicense($activationCode) {
    $client = new LicenseClient(LICENSE_SERVER_URL, LICENSE_KEY, PRODUCT_ID);
    return $client->activate($activationCode);
}

// Example usage

// First check if license is valid
if (validateLicense()) {
    echo "License is valid. Application can proceed.\n";

    // Your application code here

} else {
    echo "License validation failed. Please activate your license.\n";

    // You could prompt for activation code here
    echo "Enter activation code: ";
    $activationCode = trim(fgets(STDIN));

    if (activateLicense($activationCode)) {
        echo "License successfully activated!\n";
    } else {
        echo "License activation failed. Please contact support.\n";
        exit(1);
    }
}

// Example of checking license details
function displayLicenseInfo() {
    $client = new LicenseClient(LICENSE_SERVER_URL, LICENSE_KEY, PRODUCT_ID);
    $licenseData = $client->check();

    if ($licenseData) {
        echo "License Information:\n";
        echo "-------------------\n";
        echo "Status: " . ($licenseData['is_active'] ? "Active" : "Inactive") . "\n";
        echo "Type: " . ($licenseData['is_permanent'] ? "Permanent" : "Subscription") . "\n";
        if (!$licenseData['is_permanent'] && isset($licenseData['expires_at'])) {
            echo "Expires: " . $licenseData['expires_at'] . "\n";
        }
        if (isset($licenseData['product'])) {
            echo "Product: " . $licenseData['product']['name'] . " (v" . $licenseData['product']['version'] . ")\n";
        }
    } else {
        echo "Failed to retrieve license information.\n";
    }
}

// Uncomment to display license information
// displayLicenseInfo();
