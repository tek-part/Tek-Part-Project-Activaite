<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>دليل الربط السريع - Laravel API</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        pre {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            direction: ltr;
            text-align: left;
        }
        .step-box {
            background-color: #f8f9fa;
            border-right: 4px solid #0d6efd;
            margin-bottom: 20px;
            padding: 15px;
            border-radius: 4px;
        }
        .files-list {
            background-color: #f0f8ff;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .file-path {
            font-family: 'Courier New', Courier, monospace;
            font-weight: bold;
            color: #0d6efd;
        }
    </style>
</head>
<body>
    <div class="container my-5">
        <h1 class="mb-4">دليل الربط السريع مع Laravel API</h1>

        <div class="alert alert-info">
            <h5>ملخص الربط</h5>
            <p>هذا الدليل يشرح الخطوات الأساسية للربط مع Laravel API بطريقة سريعة وبسيطة.</p>
        </div>

        <div class="files-list mb-4">
            <h4>الملفات الأساسية للربط</h4>
            <ul>
                <li><span class="file-path">api_client.php</span> - ملف الاتصال الرئيسي (ضعه في مشروعك)</li>
                <li><span class="file-path">routes/api.php</span> - مسارات API في Laravel (موجود في المشروع)</li>
                <li><span class="file-path">app/Http/Controllers/Api/ApiController.php</span> - متحكم API (موجود في المشروع)</li>
            </ul>
        </div>

        <div class="row mt-5">
            <div class="col-md-12">
                <h3>خطوات الربط السريع</h3>

                <div class="step-box">
                    <h5>1. نسخ ملف الاتصال</h5>
                    <p>انسخ ملف <code>api_client.php</code> إلى مشروعك الخاص. هذا هو الملف الرئيسي الذي ستستخدمه للاتصال بـ Laravel API.</p>
                    <pre><code>&lt;?php
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
}</code></pre>
                </div>

                <div class="step-box">
                    <h5>2. استخدام الملف للاتصال بـ API</h5>
                    <p>استخدم ملف الاتصال في مشروعك. إليك أمثلة على كيفية استخدامه:</p>
                    <pre><code>&lt;?php
// تضمين ملف الاتصال
require_once 'api_client.php';

// إنشاء كائن من الـ API Client
$apiClient = new LaravelApiClient(
    'https://your-laravel-app.com/api', // رابط الـ API
    'YOUR_API_KEY_HERE'                 // مفتاح الـ API
);

// مثال 1: الحصول على قائمة المنتجات
$products = $apiClient->get('products');
if ($products) {
    // طباعة المنتجات
    print_r($products);
} else {
    echo "حدث خطأ: " . $apiClient->getLastError();
}

// مثال 2: الحصول على معلومات الترخيص
$licenseInfo = $apiClient->get('license/check', [
    'license_key' => 'ABC-123-XYZ-789'
]);
if ($licenseInfo) {
    echo "حالة الترخيص: " . ($licenseInfo['is_active'] ? "فعال" : "غير فعال");
    echo "تاريخ الانتهاء: " . ($licenseInfo['expires_at'] ?? "غير محدد");
} else {
    echo "حدث خطأ: " . $apiClient->getLastError();
}

// مثال 3: إنشاء عنصر جديد
$newItem = $apiClient->post('items', [
    'name' => 'عنصر جديد',
    'price' => 99.99,
    'category_id' => 1
]);
if ($newItem) {
    echo "تم إنشاء العنصر بنجاح مع المعرف: " . $newItem['id'];
} else {
    echo "حدث خطأ: " . $apiClient->getLastError();
}

// مثال 4: تحديث عنصر
$updatedItem = $apiClient->put('items/1', [
    'name' => 'اسم محدث',
    'price' => 129.99
]);
if ($updatedItem) {
    echo "تم تحديث العنصر بنجاح";
} else {
    echo "حدث خطأ: " . $apiClient->getLastError();
}

// مثال 5: حذف عنصر
$deleteResult = $apiClient->delete('items/1');
if ($deleteResult) {
    echo "تم حذف العنصر بنجاح";
} else {
    echo "حدث خطأ: " . $apiClient->getLastError();
}</code></pre>
                </div>

                <div class="step-box">
                    <h5>3. إعداد نقاط النهاية في Laravel</h5>
                    <p>قد يكون المشروع يحتوي بالفعل على مسارات API، لكن إذا كنت بحاجة إلى إضافة مسارات جديدة، اتبع الخطوات التالية:</p>
                    <pre><code>// في ملف routes/api.php

Route::middleware('auth:api')->group(function() {
    // مسارات الترخيص
    Route::prefix('license')->group(function() {
        Route::get('/check', [LicenseController::class, 'check']);
        Route::post('/activate', [LicenseController::class, 'activate']);
    });

    // مسارات المنتجات
    Route::apiResource('products', ProductController::class);

    // مسارات مخصصة أخرى
    Route::get('/stats', [StatsController::class, 'index']);
});</code></pre>
                </div>
            </div>
        </div>

        <div class="mt-5">
            <h3>متطلبات الاتصال</h3>
            <ul class="list-group">
                <li class="list-group-item">
                    <strong>PHP 7.2+</strong> على الخادم المضيف والعميل
                </li>
                <li class="list-group-item">
                    <strong>امتداد cURL</strong> مفعل في PHP
                </li>
                <li class="list-group-item">
                    <strong>اتصال بالإنترنت</strong> بين العميل والخادم
                </li>
                <li class="list-group-item">
                    <strong>مفتاح API</strong> صالح للمصادقة
                </li>
            </ul>
        </div>

        <div class="mt-5">
            <h3>نقاط النهاية API المتاحة</h3>
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>المسار</th>
                        <th>الطريقة</th>
                        <th>الوصف</th>
                        <th>المعلمات المطلوبة</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>/api/license/check</td>
                        <td>GET</td>
                        <td>التحقق من حالة الترخيص</td>
                        <td>license_key</td>
                    </tr>
                    <tr>
                        <td>/api/license/activate</td>
                        <td>POST</td>
                        <td>تفعيل ترخيص</td>
                        <td>license_key, activation_code, domain</td>
                    </tr>
                    <tr>
                        <td>/api/products</td>
                        <td>GET</td>
                        <td>قائمة المنتجات</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>/api/products/{id}</td>
                        <td>GET</td>
                        <td>عرض منتج محدد</td>
                        <td>id</td>
                    </tr>
                    <tr>
                        <td>/api/products</td>
                        <td>POST</td>
                        <td>إنشاء منتج جديد</td>
                        <td>name, price, description</td>
                    </tr>
                    <tr>
                        <td>/api/stats</td>
                        <td>GET</td>
                        <td>الحصول على إحصائيات النظام</td>
                        <td>-</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
