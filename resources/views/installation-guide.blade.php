@extends('admin.layouts.app')
@section('title') دليل تركيب نظام الحماية والتراخيص @endsection
@section('sub-title') دليل التركيب @endsection
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                        <h4 class="mb-0 fw-bold text-primary">دليل تركيب نظام الحماية والتراخيص</h4>
                        <div class="">
                            <ol class="breadcrumb mb-0 bg-transparent">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-primary">{{ __('admin.dashboard') }}</a></li>
                                <li class="breadcrumb-item active">دليل التركيب</li>
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
                                    <h5 class="text-primary fw-semibold mb-0">دليل تركيب نظام الحماية والتراخيص</h5>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="alert alert-info">
                                <i class="fa fa-info-circle me-2"></i> يوفر هذا الدليل شرحًا مفصلاً لكيفية تركيب حزمة الحماية وإدارة التراخيص في أي مشروع Laravel.
                            </div>

                            <h3 class="mt-4 fw-semibold fs-5 text-primary">١. إضافة المستودع إلى ملف composer.json</h3>
                            <div class="p-3 bg-light border rounded mb-4">
                                <p>أضف التالي إلى ملف <code>composer.json</code> في مشروعك:</p>
                                <pre dir="ltr" class="mt-2 bg-dark text-light p-2">"repositories": [
    {
        "type": "path",
        "url": "vendor/securefile"
    }
]</pre>
                            </div>

                            <h3 class="mt-4 fw-semibold fs-5 text-primary">٢. إضافة الحزمة إلى متطلبات المشروع</h3>
                            <div class="p-3 bg-light border rounded mb-4">
                                <p>أضف الحزمة إلى قائمة <code>require</code> في ملف <code>composer.json</code>:</p>
                                <pre dir="ltr" class="mt-2 bg-dark text-light p-2">"require": {
    "securefile/license-manager": "*"
}</pre>
                            </div>

                            <h3 class="mt-4 fw-semibold fs-5 text-primary">٣. تحديث حزم المشروع عبر Composer</h3>
                            <div class="p-3 bg-light border rounded mb-4">
                                <p>قم بتنفيذ الأمر التالي في طرفية المشروع:</p>
                                <pre dir="ltr" class="mt-2 bg-dark text-light p-2">composer update</pre>
                            </div>

                            <h3 class="mt-4 fw-semibold fs-5 text-primary">٤. نشر ملفات الإعدادات والواجهات (اختياري)</h3>
                            <div class="p-3 bg-light border rounded mb-4">
                                <p>لتخصيص إعدادات النظام، قم بنشر ملفات الإعدادات باستخدام الأمر:</p>
                                <pre dir="ltr" class="mt-2 bg-dark text-light p-2">php artisan vendor:publish --tag=securefile-license</pre>
                                <p class="mt-2">سينشئ هذا الأمر ملف <code>config/license.php</code> حيث يمكنك تعديل إعدادات النظام.</p>
                            </div>

                            <h3 class="mt-4 fw-semibold fs-5 text-primary">٥. تنفيذ الترحيلات لإنشاء جداول قاعدة البيانات</h3>
                            <div class="p-3 bg-light border rounded mb-4">
                                <p>قم بتشغيل الترحيلات لإنشاء جداول التراخيص في قاعدة البيانات:</p>
                                <pre dir="ltr" class="mt-2 bg-dark text-light p-2">php artisan migrate</pre>
                            </div>

                            <h3 class="mt-4 fw-semibold fs-5 text-primary">٦. إعداد ملف البيئة .env</h3>
                            <div class="p-3 bg-light border rounded mb-4">
                                <p>يمكن إضافة الإعدادات التالية إلى ملف <code>.env</code> لتخصيص سلوك نظام التراخيص:</p>
                                <pre dir="ltr" class="mt-2 bg-dark text-light p-2"># عنوان خادم التحقق من التراخيص (اتركه فارغًا للتحقق المحلي)
LICENSE_VALIDATION_SERVER=

# وقت تخزين الترخيص في الذاكرة المؤقتة (بالدقائق)
LICENSE_CACHE_TIME=60

# تمكين/تعطيل نظام التحقق من الترخيص (true/false)
LICENSE_VERIFICATION_ENABLED=true

# عناوين IP المسموح لها بالوصول بدون ترخيص (مفصولة بفواصل)
LICENSE_WHITELISTED_IPS=127.0.0.1</pre>
                            </div>

                            <h3 class="mt-4 fw-semibold fs-5 text-primary">٧. إنشاء تراخيص وأكواد تفعيل</h3>
                            <div class="p-3 bg-light border rounded mb-4">
                                <p>يمكنك إنشاء أكواد تفعيل جديدة باستخدام الأمر التالي:</p>
                                <pre dir="ltr" class="mt-2 bg-dark text-light p-2">php artisan license:generate</pre>
                                <p class="mt-2">خيارات إضافية:</p>
                                <pre dir="ltr" class="mt-2 bg-dark text-light p-2"># إنشاء ترخيص من نوع معين
php artisan license:generate --type=premium

# إنشاء ترخيص بمدة صلاحية محددة (بالأيام)
php artisan license:generate --expires=30

# إنشاء ترخيص دائم (لا ينتهي)
php artisan license:generate --expires=never</pre>
                            </div>

                            <h3 class="mt-4 fw-semibold fs-5 text-primary">٨. استخدام التراخيص في التطبيق</h3>
                            <div class="p-3 bg-light border rounded mb-4">
                                <p>للتحقق من صلاحية الترخيص برمجيًا:</p>
                                <pre dir="ltr" class="mt-2 bg-dark text-light p-2">use SecureStorage\Helpers\LicenseHelper;

// التحقق من صلاحية الترخيص
if (LicenseHelper::isValid()) {
    // الترخيص صالح
} else {
    // الترخيص غير صالح
}

// الحصول على الأيام المتبقية من الترخيص
$daysRemaining = LicenseHelper::getRemainingDays();</pre>
                            </div>

                            <h3 class="mt-4 fw-semibold fs-5 text-primary">٩. التحقق من حالة الترخيص</h3>
                            <div class="p-3 bg-light border rounded mb-4">
                                <p>بعد التثبيت، يمكنك التحقق من حالة الترخيص باستخدام الأمر:</p>
                                <pre dir="ltr" class="mt-2 bg-dark text-light p-2">php artisan license:status</pre>
                                <p>سيعرض هذا الأمر معلومات عن الترخيص الحالي مثل تاريخ التفعيل، تاريخ الانتهاء، والأيام المتبقية.</p>
                            </div>

                            <div class="alert alert-warning mt-4">
                                <i class="fas fa-exclamation-triangle me-2"></i> <strong>ملاحظة هامة:</strong> بعد تثبيت هذه الحزمة، سيتم تطبيق نظام التحقق من الترخيص تلقائيًا على جميع مسارات التطبيق. سيُعاد توجيه المستخدمين إلى صفحة التفعيل إذا لم يكن هناك ترخيص صالح.
                            </div>

                            <h3 class="mt-4 fw-semibold fs-5 text-primary">١٠. استخدام عميل API البسيط</h3>
                            <div class="p-3 bg-light border rounded mb-4">
                                <p>يمكنك استخدام عميل API البسيط للاتصال بواجهة API المتاحة:</p>
                                <pre dir="ltr" class="mt-2 bg-dark text-light p-2">$apiClient = new LaravelApiClient(
    'https://your-security-app.com/api',
    'YOUR_API_KEY'
);

$licenseInfo = $apiClient->get('license/check', [
    'license_key' => 'ABC-123-XYZ-789'
]);</pre>
                            </div>
                        </div>

                        <div class="card-footer bg-white py-3">
                            <div class="row">
                                <div class="col">
                                    <small class="text-muted">© {{ date('Y') }} - دليل التركيب - جميع الحقوق محفوظة</small>
                                </div>
                                <div class="col text-end">
                                    <small class="text-muted">إصدار الحزمة: 1.0.0</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
