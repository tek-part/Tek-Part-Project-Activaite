<?php
/**
 * Laravel API Client - اتصال سريع وبسيط
 *
 * ملف اتصال بسيط للتعامل مع Laravel API
 */
class LaravelApiClient {
    private $apiUrl;
    private $apiKey;
    private $lastError;

    /**
     * Constructor
     *
     * @param string $apiUrl رابط الـ API (مثال: https://example.com/api)
     * @param string $apiKey مفتاح الـ API للمصادقة
     */
    public function __construct($apiUrl, $apiKey) {
        $this->apiUrl = rtrim($apiUrl, '/');
        $this->apiKey = $apiKey;
        $this->lastError = null;
    }

    /**
     * إرسال طلب GET
     *
     * @param string $endpoint النقطة النهائية (مثال: /products)
     * @param array $params المعلمات المطلوبة
     * @return array|false البيانات في حال النجاح أو false في حال الفشل
     */
    public function get($endpoint, $params = []) {
        return $this->request('GET', $endpoint, $params);
    }

    /**
     * إرسال طلب POST
     *
     * @param string $endpoint النقطة النهائية
     * @param array $data البيانات المرسلة
     * @return array|false النتيجة أو false في حال الفشل
     */
    public function post($endpoint, $data = []) {
        return $this->request('POST', $endpoint, $data);
    }

    /**
     * إرسال طلب PUT
     *
     * @param string $endpoint النقطة النهائية
     * @param array $data البيانات المرسلة
     * @return array|false النتيجة أو false في حال الفشل
     */
    public function put($endpoint, $data = []) {
        return $this->request('PUT', $endpoint, $data);
    }

    /**
     * إرسال طلب DELETE
     *
     * @param string $endpoint النقطة النهائية
     * @param array $params المعلمات المطلوبة
     * @return array|false النتيجة أو false في حال الفشل
     */
    public function delete($endpoint, $params = []) {
        return $this->request('DELETE', $endpoint, $params);
    }

    /**
     * الحصول على آخر خطأ
     *
     * @return string|null رسالة الخطأ أو null إذا لم يحدث خطأ
     */
    public function getLastError() {
        return $this->lastError;
    }

    /**
     * إرسال الطلب
     *
     * @param string $method طريقة الطلب (GET, POST, PUT, DELETE)
     * @param string $endpoint النقطة النهائية
     * @param array $data البيانات المرسلة
     * @return array|false النتيجة أو false في حال الفشل
     */
    private function request($method, $endpoint, $data = []) {
        $url = $this->apiUrl . '/' . ltrim($endpoint, '/');

        // إعداد الـ cURL
        $ch = curl_init();

        // إعداد Headers
        $headers = [
            'Content-Type: application/json',
            'Accept: application/json',
            'X-API-KEY: ' . $this->apiKey
        ];

        if ($method === 'GET' && !empty($data)) {
            $url .= '?' . http_build_query($data);
        }

        $options = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_TIMEOUT => 30
        ];

        if ($method !== 'GET') {
            $options[CURLOPT_CUSTOMREQUEST] = $method;
            $options[CURLOPT_POSTFIELDS] = json_encode($data);
        }

        curl_setopt_array($ch, $options);

        // تنفيذ الطلب
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            $this->lastError = 'خطأ في الاتصال: ' . curl_error($ch);
            curl_close($ch);
            return false;
        }

        curl_close($ch);

        $decodedResponse = json_decode($response, true);

        if ($httpCode >= 400) {
            $this->lastError = 'خطأ في الطلب [' . $httpCode . ']: ' .
                ($decodedResponse['message'] ?? 'خطأ غير معروف');
            return false;
        }

        return $decodedResponse;
    }
}

// مثال على الاستخدام:
/*
// إنشاء كائن من الـ API Client
$api = new LaravelApiClient(
    'https://your-laravel-app.com/api',  // رابط الـ API
    'YOUR_API_KEY_HERE'                  // مفتاح الـ API
);

// الحصول على قائمة المنتجات
$products = $api->get('products');
if ($products) {
    // معالجة البيانات
    foreach ($products as $product) {
        echo $product['name'] . " - " . $product['price'] . "<br>";
    }
} else {
    echo "حدث خطأ: " . $api->getLastError();
}

// التحقق من ترخيص
$license = $api->get('license/check', [
    'license_key' => 'YOUR-LICENSE-KEY'
]);
if ($license && $license['is_active']) {
    echo "الترخيص فعال!";
} else {
    echo "الترخيص غير فعال أو حدث خطأ";
}
*/
