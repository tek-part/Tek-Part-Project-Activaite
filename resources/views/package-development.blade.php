@extends('admin.layouts.app')
@section('title') إنشاء حزمة Laravel @endsection
@section('sub-title') دليل تطوير الحزم @endsection
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                        <h4 class="mb-0 fw-bold text-primary">دليل إنشاء حزمة Laravel</h4>
                        <div class="">
                            <ol class="breadcrumb mb-0 bg-transparent">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-primary">{{ __('admin.dashboard') }}</a></li>
                                <li class="breadcrumb-item active">تطوير الحزم</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm border-0 rounded-lg">
                        <div class="card-header bg-white py-3">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h5 class="text-primary fw-semibold mb-0">إنشاء حزمة Laravel قابلة للنشر عبر Composer</h5>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="alert alert-info">
                                <i class="fa fa-info-circle me-2"></i> يشرح هذا الدليل كيفية إنشاء حزمة Laravel خاصة بك والتي يمكن للمطورين تثبيتها مباشرة باستخدام أمر <code>composer require</code>
                            </div>

                            <h3 class="mt-4 fw-semibold fs-5 text-primary">١. إنشاء هيكل الحزمة</h3>
                            <div class="p-3 bg-light border rounded mb-4">
                                <p>أولاً، قم بإنشاء مجلد للحزمة الخاصة بك. يوصى باتباع تنسيق <code>vendor-name/package-name</code>:</p>
                                <pre dir="ltr" class="mt-2 bg-dark text-light p-2">vendor-name/
└── package-name/
    ├── src/             # كود المصدر الخاص بالحزمة
    │   └── ServiceProvider.php
    ├── config/          # ملفات الإعدادات
    ├── database/        # الترحيلات والبذور
    │   └── migrations/
    ├── resources/       # الموارد مثل العروض، اللغات، إلخ
    │   ├── views/
    │   └── lang/
    ├── routes/          # تعريفات المسارات
    ├── tests/           # اختبارات الوحدة
    ├── composer.json    # تعريف الحزمة
    ├── LICENSE          # ترخيص البرمجيات
    └── README.md        # وثائق الحزمة</pre>
                            </div>

                            <h3 class="mt-4 fw-semibold fs-5 text-primary">٢. تكوين ملف composer.json</h3>
                            <div class="p-3 bg-light border rounded mb-4">
                                <p>أنشئ ملف <code>composer.json</code> الذي يحدد تفاصيل حزمتك:</p>
                                <pre dir="ltr" class="mt-2 bg-dark text-light p-2">{
    "name": "vendor-name/package-name",
    "description": "وصف الحزمة الخاصة بك",
    "type": "library",
    "license": "MIT",
    "authors": [
        {
            "name": "اسمك",
            "email": "بريدك الإلكتروني"
        }
    ],
    "require": {
        "php": "^7.4|^8.0",
        "illuminate/support": "^8.0|^9.0|^10.0"
    },
    "require-dev": {
        "orchestra/testbench": "^6.0|^7.0|^8.0",
        "phpunit/phpunit": "^9.0"
    },
    "autoload": {
        "psr-4": {
            "VendorName\\PackageName\\": "src/"
        }
    },
    "autoload-dev": {
        "psr-4": {
            "VendorName\\PackageName\\Tests\\": "tests/"
        }
    },
    "extra": {
        "laravel": {
            "providers": [
                "VendorName\\PackageName\\PackageNameServiceProvider"
            ],
            "aliases": {
                "PackageName": "VendorName\\PackageName\\Facades\\PackageName"
            }
        }
    },
    "minimum-stability": "dev",
    "prefer-stable": true
}</pre>
                            </div>

                            <h3 class="mt-4 fw-semibold fs-5 text-primary">٣. إنشاء مزود الخدمة (Service Provider)</h3>
                            <div class="p-3 bg-light border rounded mb-4">
                                <p>مزود الخدمة هو نقطة الدخول الرئيسية لحزمتك. أنشئ ملف <code>src/PackageNameServiceProvider.php</code>:</p>
                                <pre dir="ltr" class="mt-2 bg-dark text-light p-2">&lt;?php

namespace VendorName\PackageName;

use Illuminate\Support\ServiceProvider;

class PackageNameServiceProvider extends ServiceProvider
{
    /**
     * تسجيل أي خدمات للحزمة.
     *
     * @return void
     */
    public function register()
    {
        // دمج إعدادات الحزمة
        $this->mergeConfigFrom(
            __DIR__.'/../config/package-name.php', 'package-name'
        );

        // تسجيل واجهات الحزمة
        $this->app->singleton('package-name', function ($app) {
            return new PackageName();
        });
    }

    /**
     * تهيئة أي خدمات للحزمة.
     *
     * @return void
     */
    public function boot()
    {
        // نشر الإعدادات
        $this->publishes([
            __DIR__.'/../config/package-name.php' => config_path('package-name.php'),
        ], 'package-name-config');

        // نشر الترحيلات
        $this->publishes([
            __DIR__.'/../database/migrations/' => database_path('migrations'),
        ], 'package-name-migrations');

        // تحميل الترحيلات
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        // نشر العروض
        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/package-name'),
        ], 'package-name-views');

        // تحميل العروض
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'package-name');

        // تحميل الترجمات
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'package-name');

        // نشر اللغات
        $this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/package-name'),
        ], 'package-name-lang');

        // تحميل المسارات
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
    }
}</pre>
                            </div>

                            <h3 class="mt-4 fw-semibold fs-5 text-primary">٤. إنشاء الفصول الرئيسية للحزمة</h3>
                            <div class="p-3 bg-light border rounded mb-4">
                                <p>أنشئ الفصل الرئيسي للحزمة <code>src/PackageName.php</code>:</p>
                                <pre dir="ltr" class="mt-2 bg-dark text-light p-2">&lt;?php

namespace VendorName\PackageName;

class PackageName
{
    // أضف المنطق الرئيسي للحزمة هنا

    public function doSomething()
    {
        return 'PackageName is working!';
    }
}</pre>
                            </div>

                            <h3 class="mt-4 fw-semibold fs-5 text-primary">٥. إنشاء واجهة Facade (اختياري)</h3>
                            <div class="p-3 bg-light border rounded mb-4">
                                <p>إنشاء واجهة تسهل استخدام الحزمة <code>src/Facades/PackageName.php</code>:</p>
                                <pre dir="ltr" class="mt-2 bg-dark text-light p-2">&lt;?php

namespace VendorName\PackageName\Facades;

use Illuminate\Support\Facades\Facade;

class PackageName extends Facade
{
    /**
     * الحصول على اسم المكون المسجل.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'package-name';
    }
}</pre>
                            </div>

                            <h3 class="mt-4 fw-semibold fs-5 text-primary">٦. إنشاء ملف إعدادات</h3>
                            <div class="p-3 bg-light border rounded mb-4">
                                <p>أنشئ ملف الإعدادات الافتراضي <code>config/package-name.php</code>:</p>
                                <pre dir="ltr" class="mt-2 bg-dark text-light p-2">&lt;?php

return [
    // الإعدادات الافتراضية للحزمة
    'option_1' => 'default_value_1',
    'option_2' => 'default_value_2',

    // المزيد من الخيارات
];</pre>
                            </div>

                            <h3 class="mt-4 fw-semibold fs-5 text-primary">٧. اختبار الحزمة محليًا</h3>
                            <div class="p-3 bg-light border rounded mb-4">
                                <p>لاختبار الحزمة محليًا قبل نشرها، يمكنك استخدام مشروع Laravel حالي واستخدام مسار محلي في <code>composer.json</code>:</p>
                                <pre dir="ltr" class="mt-2 bg-dark text-light p-2">"repositories": [
    {
        "type": "path",
        "url": "../vendor-name/package-name"
    }
]</pre>
                                <p>ثم قم بتنفيذ الأمر التالي:</p>
                                <pre dir="ltr" class="mt-2 bg-dark text-light p-2">composer require vendor-name/package-name</pre>
                            </div>

                            <h3 class="mt-4 fw-semibold fs-5 text-primary">٨. نشر الحزمة على GitHub</h3>
                            <div class="p-3 bg-light border rounded mb-4">
                                <p>قم بإنشاء مستودع GitHub وارفع الحزمة:</p>
                                <pre dir="ltr" class="mt-2 bg-dark text-light p-2">git init
git add .
git commit -m "Initial commit"
git remote add origin https://github.com/username/package-name.git
git push -u origin main</pre>
                            </div>

                            <h3 class="mt-4 fw-semibold fs-5 text-primary">٩. نشر الحزمة على Packagist</h3>
                            <div class="p-3 bg-light border rounded mb-4">
                                <p>لكي يتمكن المطورون من تثبيت حزمتك باستخدام Composer، قم بتسجيلها على Packagist:</p>
                                <ol>
                                    <li>انتقل إلى <a href="https://packagist.org" target="_blank">https://packagist.org</a> وقم بإنشاء حساب</li>
                                    <li>انقر على "Submit" وأدخل عنوان URL لمستودع GitHub</li>
                                    <li>أكمل عملية التسجيل</li>
                                </ol>
                                <p>بعد التسجيل، سيتمكن المطورون من تثبيت حزمتك باستخدام:</p>
                                <pre dir="ltr" class="mt-2 bg-dark text-light p-2">composer require vendor-name/package-name</pre>
                            </div>

                            <h3 class="mt-4 fw-semibold fs-5 text-primary">١٠. إعداد التحديثات التلقائية</h3>
                            <div class="p-3 bg-light border rounded mb-4">
                                <p>لضمان تحديث Packagist تلقائيًا عند دفع تغييرات جديدة، قم بإعداد webhook:</p>
                                <ol>
                                    <li>انتقل إلى مستودع GitHub الخاص بك</li>
                                    <li>انتقل إلى التبويب "Settings" ثم "Webhooks"</li>
                                    <li>أضف webhook جديد بالعنوان: <code>https://packagist.org/api/github?username=your-packagist-username</code></li>
                                    <li>اختر نوع المحتوى "application/json"</li>
                                    <li>أضف سر Packagist الخاص بك (يمكن العثور عليه في إعدادات Packagist)</li>
                                </ol>
                            </div>

                            <div class="alert alert-warning mt-4">
                                <i class="fas fa-exclamation-triangle me-2"></i> <strong>ملاحظة هامة:</strong> تأكد من إضافة وثائق شاملة في ملف README.md الخاص بك. وثائق جيدة تساعد المطورين على فهم كيفية استخدام حزمتك.
                            </div>

                            <div class="alert alert-success mt-4">
                                <i class="fas fa-check-circle me-2"></i> <strong>تلميح:</strong> استخدم التحكم في الإصدارات بشكل صحيح (Semantic Versioning). على سبيل المثال v1.0.0 للإصدار الأول، وزيادة الرقم الأخير للإصلاحات، والرقم الأوسط للميزات الجديدة، والرقم الأول للتغييرات غير المتوافقة.
                            </div>

                            <h2 class="mt-5 fw-bold text-primary border-bottom pb-2">تطبيق عملي: باكدج TekPart License لحماية المشاريع</h2>

                            <p class="mt-3">قمنا بتطوير باكدج خاص لحماية مشاريع Laravel وإدارة التراخيص يمكنك استخدامه في مشاريعك البرمجية. هذا الباكدج يتيح لك حماية الشفرة المصدرية وإدارة ترخيص استخدام البرمجيات التي تقوم بتطويرها.</p>

                            <h3 class="mt-4 fw-semibold fs-5 text-primary">١. مميزات باكدج TekPart License</h3>
                            <div class="p-3 bg-light border rounded mb-4">
                                <ul>
                                    <li>حماية الشفرة المصدرية ومنع الاستخدام غير المصرح به</li>
                                    <li>نظام تشفير قوي باستخدام خوارزمية RSA</li>
                                    <li>التحقق من صلاحية التراخيص محلياً وعن بعد</li>
                                    <li>دعم التراخيص المقيدة بنطاق معين (domain) أو تاريخ انتهاء</li>
                                    <li>أوامر خاصة لتوليد وإدارة التراخيص</li>
                                    <li>واجهة سهلة الاستخدام للتحقق من حالة الترخيص</li>
                                    <li>وسيط (middleware) لحماية المسارات والتحكم بالوصول</li>
                                </ul>
                            </div>

                            <h3 class="mt-4 fw-semibold fs-5 text-primary">٢. تثبيت واستخدام الباكدج</h3>
                            <div class="p-3 bg-light border rounded mb-4">
                                <p>لتثبيت الباكدج في مشروع Laravel، اتبع الخطوات التالية:</p>

                                <h5 class="mt-3 fw-semibold">تثبيت الباكدج باستخدام Composer</h5>
                                <pre dir="ltr" class="mt-2 bg-dark text-light p-2">composer require tekpart/license</pre>

                                <h5 class="mt-3 fw-semibold">نشر ملفات الإعدادات والترحيلات</h5>
                                <pre dir="ltr" class="mt-2 bg-dark text-light p-2">php artisan vendor:publish --provider="TekPart\License\LicenseServiceProvider"</pre>

                                <h5 class="mt-3 fw-semibold">تشغيل الترحيلات</h5>
                                <pre dir="ltr" class="mt-2 bg-dark text-light p-2">php artisan migrate</pre>

                                <h5 class="mt-3 fw-semibold">تثبيت الباكدج وإعداده</h5>
                                <pre dir="ltr" class="mt-2 bg-dark text-light p-2">php artisan tekpart:install-license</pre>

                                <div class="alert alert-info mt-3">
                                    <i class="fa fa-info-circle me-2"></i> سيقوم الأمر السابق بإنشاء زوج مفاتيح تشفير جديد وإعداد البيانات اللازمة لعمل الباكدج
                                </div>
                            </div>

                            <h3 class="mt-4 fw-semibold fs-5 text-primary">٣. إنشاء وإدارة التراخيص</h3>
                            <div class="p-3 bg-light border rounded mb-4">
                                <h5 class="mt-3 fw-semibold">إنشاء ترخيص جديد</h5>
                                <pre dir="ltr" class="mt-2 bg-dark text-light p-2">php artisan tekpart:generate-license</pre>

                                <p class="mt-2">أو يمكنك تحديد خيارات الترخيص مباشرة:</p>
                                <pre dir="ltr" class="mt-2 bg-dark text-light p-2">php artisan tekpart:generate-license --domain=example.com --expires=2024-12-31 --owner="اسم العميل" --email=client@example.com</pre>

                                <h5 class="mt-3 fw-semibold">استخدام التراخيص في الكود</h5>
                                <pre dir="ltr" class="mt-2 bg-dark text-light p-2">use TekPart\License\Facades\License;

// التحقق من صلاحية الترخيص
if (License::verifyLicense()) {
    // الترخيص صالح
} else {
    // الترخيص غير صالح أو منتهي الصلاحية
}</pre>

                                <h5 class="mt-3 fw-semibold">حماية المسارات بالترخيص</h5>
                                <pre dir="ltr" class="mt-2 bg-dark text-light p-2">// في ملف routes/web.php
Route::middleware('license.check')->group(function () {
    // المسارات المحمية بالترخيص
    Route::get('/admin', 'AdminController@index');
});</pre>
                            </div>

                            <h3 class="mt-4 fw-semibold fs-5 text-primary">٤. نشر الباكدج وتوزيعه</h3>
                            <div class="p-3 bg-light border rounded mb-4">
                                <p>لنشر الباكدج وجعله متاحًا للتثبيت عبر Composer، اتبع الخطوات التالية:</p>

                                <h5 class="mt-3 fw-semibold">رفع الباكدج على GitHub</h5>
                                <pre dir="ltr" class="mt-2 bg-dark text-light p-2">cd tekpart-license
git init
git add .
git commit -m "Initial commit"
git remote add origin https://github.com/tekpart/license.git
git push -u origin main</pre>

                                <h5 class="mt-3 fw-semibold">تسجيل الباكدج على Packagist</h5>
                                <ol>
                                    <li>قم بإنشاء حساب على <a href="https://packagist.org" target="_blank">https://packagist.org</a></li>
                                    <li>انقر على زر "Submit" من لوحة التحكم</li>
                                    <li>أدخل عنوان مستودع GitHub الخاص بالباكدج</li>
                                    <li>اتبع التعليمات لإكمال عملية التسجيل</li>
                                </ol>

                                <h5 class="mt-3 fw-semibold">تهيئة التحديثات التلقائية</h5>
                                <p>لجعل Packagist يتحدث تلقائيًا عند دفع تحديثات جديدة إلى GitHub:</p>
                                <ol>
                                    <li>انتقل إلى مستودع GitHub الخاص بك</li>
                                    <li>انتقل إلى التبويب Settings ثم Webhooks</li>
                                    <li>انقر على "Add webhook"</li>
                                    <li>أدخل رابط webhook من Packagist: <code>https://packagist.org/api/github?username=your-packagist-username</code></li>
                                    <li>اختر نوع المحتوى "application/json"</li>
                                    <li>حدد الأحداث التي ترغب في تشغيل webhook عندها (عادة، اختر "Just the push event")</li>
                                </ol>
                            </div>

                            <h3 class="mt-4 fw-semibold fs-5 text-primary">٥. دعم العملاء واستخدام الباكدج</h3>
                            <div class="p-3 bg-light border rounded mb-4">
                                <p>عند توزيع البرمجيات المحمية بالباكدج، يجب توفير المعلومات التالية للعملاء:</p>

                                <ul>
                                    <li>ملف الترخيص المشفر (.dat) الذي تم إنشاؤه باستخدام الأمر <code>tekpart:generate-license</code></li>
                                    <li>مفتاح الترخيص الخاص بهم</li>
                                    <li>تعليمات حول كيفية تفعيل الترخيص</li>
                                    <li>معلومات حول تاريخ انتهاء الترخيص وكيفية تجديده</li>
                                </ul>

                                <div class="alert alert-warning mt-3">
                                    <i class="fas fa-exclamation-triangle me-2"></i> <strong>ملاحظة هامة:</strong> لا تشارك أبدًا المفتاح الخاص (private key) مع العملاء، حيث يمكن استخدامه لإنشاء تراخيص غير مصرح بها.
                                </div>

                                <p>يمكن للعملاء تفعيل التراخيص من خلال:</p>
                                <ol>
                                    <li>وضع ملف الترخيص في المسار <code>storage/app/license/license.dat</code></li>
                                    <li>إضافة مفتاح الترخيص إلى ملف <code>.env</code>: <code>TEKPART_LICENSE_KEY=xxxxx-xxxxx-xxxxx-xxxxx</code></li>
                                    <li>أو استخدام واجهة التفعيل المضمنة على المسار <code>/license/activate</code></li>
                                </ol>
                            </div>
                        </div>

                        <div class="card-footer bg-white py-3">
                            <div class="row">
                                <div class="col">
                                    <small class="text-muted">© {{ date('Y') }} - دليل تطوير الحزم - جميع الحقوق محفوظة</small>
                                </div>
                                <div class="col text-end">
                                    <small class="text-muted">أحدث تحديث: {{ date('Y-m-d') }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
