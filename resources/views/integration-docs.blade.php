@extends('admin.layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h1 class="h3 mb-0">License System Integration Documentation</h1>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <p class="mb-0">This documentation provides information on how to integrate your applications with our licensing system.</p>
                    </div>

                    <h2 class="mt-4 mb-3">API Endpoints</h2>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th>Endpoint</th>
                                    <th>Method</th>
                                    <th>Description</th>
                                    <th>Parameters</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><code>/api/license/validate</code></td>
                                    <td>POST</td>
                                    <td>Validates a license key</td>
                                    <td>
                                        <ul>
                                            <li><code>license_key</code> (required): The license key to validate</li>
                                            <li><code>domain</code> (optional): The domain where the license is being used</li>
                                            <li><code>product_id</code> (optional): The product ID for validation</li>
                                        </ul>
                                    </td>
                                </tr>
                                <tr>
                                    <td><code>/api/license/activate</code></td>
                                    <td>POST</td>
                                    <td>Activates a license</td>
                                    <td>
                                        <ul>
                                            <li><code>license_key</code> (required): The license key to activate</li>
                                            <li><code>activation_code</code> (required): The activation code provided with the license</li>
                                            <li><code>domain</code> (optional): The domain where the license will be used</li>
                                        </ul>
                                    </td>
                                </tr>
                                <tr>
                                    <td><code>/api/license/deactivate</code></td>
                                    <td>POST</td>
                                    <td>Deactivates a license</td>
                                    <td>
                                        <ul>
                                            <li><code>license_key</code> (required): The license key to deactivate</li>
                                        </ul>
                                    </td>
                                </tr>
                                <tr>
                                    <td><code>/api/license/check</code></td>
                                    <td>POST</td>
                                    <td>Checks license status</td>
                                    <td>
                                        <ul>
                                            <li><code>license_key</code> (required): The license key to check</li>
                                            <li><code>product_id</code> (required): The product ID</li>
                                        </ul>
                                    </td>
                                </tr>
                                <tr>
                                    <td><code>/api/license/token/generate</code></td>
                                    <td>POST</td>
                                    <td>Generates a secure token for a license</td>
                                    <td>
                                        <ul>
                                            <li><code>license_key</code> (required): The license key</li>
                                            <li><code>product_id</code> (required): The product ID</li>
                                        </ul>
                                    </td>
                                </tr>
                                <tr>
                                    <td><code>/api/license/token/verify</code></td>
                                    <td>POST</td>
                                    <td>Verifies a secure token</td>
                                    <td>
                                        <ul>
                                            <li><code>token</code> (required): The token to verify</li>
                                        </ul>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <h2 class="mt-5 mb-3">Integration Examples</h2>

                    <h3 class="mt-4">1. PHP Integration</h3>
                    <div class="bg-light p-3 rounded">
<pre><code>// Example PHP code for license validation
$response = file_get_contents('https://yourdomain.com/api/license/validate', false, stream_context_create([
    'http' => [
        'method' => 'POST',
        'header' => 'Content-Type: application/json',
        'content' => json_encode([
            'license_key' => 'YOUR-LICENSE-KEY',
            'domain' => $_SERVER['HTTP_HOST'],
            'product_id' => 'your-product-id'
        ])
    ]
]));

$result = json_decode($response, true);
if (!$result['valid']) {
    die('License validation failed: ' . $result['message']);
}

// License is valid, continue with your application</code></pre>
                    </div>

                    <h3 class="mt-4">2. JavaScript Integration</h3>
                    <div class="bg-light p-3 rounded">
<pre><code>// Example JavaScript code for license activation
async function activateLicense(licenseKey, activationCode) {
    try {
        const response = await fetch('https://yourdomain.com/api/license/activate', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                license_key: licenseKey,
                activation_code: activationCode,
                domain: window.location.hostname
            }),
        });

        const result = await response.json();
        if (result.success) {
            console.log('License activated successfully');
            return true;
        } else {
            console.error('License activation failed:', result.message);
            return false;
        }
    } catch (error) {
        console.error('Error during license activation:', error);
        return false;
    }
}</code></pre>
                    </div>

                    <h3 class="mt-4">3. Python Integration</h3>
                    <div class="bg-light p-3 rounded">
<pre><code>import requests
import json

def check_license(license_key, product_id):
    url = 'https://yourdomain.com/api/license/check'
    data = {
        'license_key': license_key,
        'product_id': product_id
    }

    response = requests.post(url, json=data)
    result = response.json()

    if result.get('success'):
        print('License is valid')
        return result.get('data')
    else:
        print('License check failed:', result.get('message'))
        return None</code></pre>
                    </div>

                    <h2 class="mt-5 mb-3">Permanent Activation Configuration</h2>
                    <p>For setting up permanent activation in your application:</p>
                    <ol>
                        <li>Obtain a valid license key and activation code from the license administrator.</li>
                        <li>Use the <code>/api/license/activate</code> endpoint to activate the license.</li>
                        <li>Store the activation response securely in your application.</li>
                        <li>Periodically verify the license status using the <code>/api/license/check</code> endpoint.</li>
                        <li>For permanent licenses, you can use the token-based verification by generating a secure token and storing it for offline validation.</li>
                    </ol>

                    <h2 class="mt-5 mb-3">Security Recommendations</h2>
                    <div class="alert alert-warning">
                        <h4 class="alert-heading">Important Security Notes</h4>
                        <ul>
                            <li>Always use HTTPS for API communication.</li>
                            <li>Store license keys and activation codes securely.</li>
                            <li>Implement obfuscation techniques to prevent easy extraction of license keys.</li>
                            <li>For desktop applications, consider implementing hardware fingerprinting.</li>
                            <li>Implement a fallback mechanism in case license verification fails due to network issues.</li>
                        </ul>
                    </div>

                    <h2 class="mt-5 mb-3">Need Help?</h2>
                    <p>If you encounter any issues with integration or have questions, please contact our support team at <a href="mailto:support@example.com">support@example.com</a>.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection