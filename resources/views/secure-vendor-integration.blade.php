@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h2 class="mb-0">دليل التكامل الآمن للبائعين</h2>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fa fa-info-circle mr-2"></i> هذا الدليل يوضح كيفية إنشاء وإدارة التكاملات الآمنة للبائعين في نظام Laravel.
                    </div>

                    <h3 class="mt-4">١. إنشاء حزمة خاصة في مجلد vendor</h3>
                    <div class="p-3 bg-light border rounded">
                        <ol>
                            <li>قم بإنشاء مجلد باسم شركتك داخل <code>vendor/</code>:
                                <pre class="mt-2 bg-dark text-light p-2">mkdir -p vendor/yourcompany/secure-integration</pre>
                            </li>
                            <li>قم بإنشاء ملف <code>composer.json</code> للحزمة:
                                <pre class="mt-2 bg-dark text-light p-2">{
    "name": "yourcompany/secure-integration",
    "description": "Secure vendor integration package",
    "type": "library",
    "autoload": {
        "psr-4": {
            "YourCompany\\SecureIntegration\\": "src/"
        }
    },
    "require": {
        "php": "^8.0",
        "laravel/framework": "^9.0|^10.0",
        "defuse/php-encryption": "^2.3"
    }
}</pre>
                            </li>
                            <li>أنشئ هيكل المجلدات المطلوب:
                                <pre class="mt-2 bg-dark text-light p-2">vendor/yourcompany/secure-integration/
├── src/
│   ├── Services/
│   ├── Models/
│   └── Providers/
└── composer.json</pre>
                            </li>
                        </ol>
                    </div>

                    <h3 class="mt-4">٢. إعداد التشفير وأمان الملفات</h3>
                    <div class="p-3 bg-light border rounded">
                        <p>استخدم مكتبة <code>defuse/php-encryption</code> لتشفير البيانات الحساسة:</p>
                        <pre class="mt-2 bg-dark text-light p-2">
// إنشاء مفتاح تشفير آمن
$key = \Defuse\Crypto\Key::createNewRandomKey();
$keyString = $key->saveToAsciiSafeString();
// احفظ المفتاح في ملف .env أو في نظام إدارة السر الخاص بك

// تشفير البيانات
$encrypted = \Defuse\Crypto\Crypto::encrypt($sensitiveData, $key);

// فك تشفير البيانات
$decrypted = \Defuse\Crypto\Crypto::decrypt($encrypted, $key);</pre>
                    </div>

                    <h3 class="mt-4">٣. إعداد نظام التحقق والتصريح</h3>
                    <div class="p-3 bg-light border rounded">
                        <p>قم بإنشاء <code>ServiceProvider</code> خاص للحزمة:</p>
                        <pre class="mt-2 bg-dark text-light p-2">
namespace YourCompany\SecureIntegration\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class SecureIntegrationServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('secure-integration', function ($app) {
            return new \YourCompany\SecureIntegration\Services\SecureIntegrationService();
        });
    }

    public function boot()
    {
        // تسجيل سياسات التصريح
        Gate::define('access-secure-integration', function ($user) {
            return $user->hasRole('admin') || $user->hasPermission('secure-integration');
        });
    }
}</pre>
                    </div>

                    <h3 class="mt-4">٤. تسجيل الحزمة في تطبيق Laravel</h3>
                    <div class="p-3 bg-light border rounded">
                        <p>أضف إلى ملف <code>composer.json</code> الخاص بتطبيقك:</p>
                        <pre class="mt-2 bg-dark text-light p-2">"require": {
    // ... الاعتمادات الأخرى
    "yourcompany/secure-integration": "*"
},
"repositories": [
    {
        "type": "path",
        "url": "vendor/yourcompany/secure-integration"
    }
]</pre>
                        <p class="mt-3">ثم قم بتشغيل:</p>
                        <pre class="mt-2 bg-dark text-light p-2">composer update</pre>

                        <p class="mt-3">أضف مزود الخدمة إلى <code>config/app.php</code>:</p>
                        <pre class="mt-2 bg-dark text-light p-2">'providers' => [
    // ... المزودون الآخرون
    YourCompany\SecureIntegration\Providers\SecureIntegrationServiceProvider::class,
]</pre>
                    </div>

                    <h3 class="mt-4">٥. استخدام الحزمة بشكل آمن</h3>
                    <div class="p-3 bg-light border rounded">
                        <pre class="mt-2 bg-dark text-light p-2">
// في المتحكم الخاص بك
public function process(Request $request)
{
    // التحقق من التصريح
    $this->authorize('access-secure-integration');

    // استخدام الخدمة
    $secureService = app('secure-integration');
    $result = $secureService->processSecureData($request->data);

    return response()->json(['status' => 'success', 'data' => $result]);
}</pre>
                    </div>

                    <div class="alert alert-warning mt-4">
                        <i class="fa fa-exclamation-triangle mr-2"></i> <strong>ملاحظة هامة:</strong> تأكد من عدم تضمين مجلد <code>vendor/</code> في المستودع العام. استخدم <code>.gitignore</code> للتأكد من عدم مشاركة الكود الخاص بك.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
