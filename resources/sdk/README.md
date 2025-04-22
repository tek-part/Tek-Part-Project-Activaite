# License System SDK

This SDK provides a simple way to integrate with our license management system. It allows you to validate, activate, and manage licenses in your PHP applications.

## Files

- `license-client.php` - The main SDK file
- `example-client.php` - An example implementation
- `README.md` - This file

## Basic Usage

```php
// Include the license client SDK
require_once 'license-client.php';

// Create a new license client
$licenseClient = new LicenseClient(
    'https://your-license-server.com',  // License server URL
    'YOUR-LICENSE-KEY',                 // Your license key
    'your-product-id'                   // Your product ID
);

// Validate the license
if ($licenseClient->validate()) {
    echo "License is valid!";
} else {
    echo "License validation failed: " . $licenseClient->getLastError();
}
```

## Features

- License validation
- License activation with activation codes
- License status checking
- Support for permanent licenses
- Token-based validation for offline use
- Detailed error reporting

## Methods

### `validate()`

Validates the license against the license server.

```php
if ($licenseClient->validate()) {
    // License is valid
}
```

### `activate($activationCode)`

Activates a license using the provided activation code.

```php
if ($licenseClient->activate('ACTIVATION-CODE-HERE')) {
    // License activated successfully
}
```

### `check()`

Checks the current status of the license.

```php
$licenseInfo = $licenseClient->check();
if ($licenseInfo) {
    // Access license information
    $isActive = $licenseInfo['is_active'];
    $isPermanent = $licenseInfo['is_permanent'];
}
```

### `generateToken()`

Generates a secure token for offline validation.

```php
$token = $licenseClient->generateToken();
if ($token) {
    // Store the token for future use
    file_put_contents('license.dat', $token);
}
```

### `verifyToken($token)`

Verifies a previously generated token.

```php
$token = file_get_contents('license.dat');
$result = $licenseClient->verifyToken($token);
if ($result) {
    // Token is valid
}
```

## Error Handling

You can access the last error message using the `getLastError()` method:

```php
if (!$licenseClient->validate()) {
    die("License validation failed: " . $licenseClient->getLastError());
}
```

## Security Recommendations

1. Always use HTTPS for license verification
2. Store your license key and activation code securely
3. Implement obfuscation to prevent easy extraction of your license key
4. Use the token-based validation for offline scenarios
5. Regularly check the license status, even after initial activation

## Advanced Implementation Example

See `example-client.php` for a complete implementation example including:

- Offline token-based validation
- Caching of license information
- Fallback to online validation
- Activation workflow 