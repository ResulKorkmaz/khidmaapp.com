<?php
/**
 * Lead Kalite Politikası Sayfası
 * Usta'ların lead satın almadan önce bilmesi gereken kurallar
 */
ob_start();
?>

<div class="max-w-3xl mx-auto px-4 py-6">
    <!-- Geri Butonu -->
    <a href="/provider/browse-packages" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-blue-600 mb-4">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        العودة للحزم
    </a>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-amber-500 to-orange-500 p-6 text-white">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold">سياسة جودة الطلبات</h1>
                    <p class="text-amber-100 text-sm">يرجى قراءة هذه السياسة بعناية قبل الشراء</p>
                </div>
            </div>
        </div>

        <div class="p-6 space-y-6">
            <!-- Bölüm 1: Nasıl Çalışır -->
            <section>
                <h2 class="text-lg font-bold text-gray-900 mb-3 flex items-center gap-2">
                    <span class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 text-sm font-bold">1</span>
                    كيف نحصل على الطلبات؟
                </h2>
                <div class="bg-gray-50 rounded-lg p-4 text-sm text-gray-700 space-y-2">
                    <p>• نحصل على طلبات العملاء من خلال <strong>إعلانات Google</strong> ونماذج الموقع</p>
                    <p>• يقوم العملاء بملء نموذج يحتوي على: نوع الخدمة، المدينة، رقم الهاتف، ووصف المشكلة</p>
                    <p>• نطلب من العميل إدخال رقم الهاتف مرتين للتأكيد</p>
                    <p>• نستخدم تقنيات لمنع الطلبات المزيفة (مثل حماية السبام)</p>
                </div>
            </section>

            <!-- Bölüm 2: Garanti Yok -->
            <section>
                <h2 class="text-lg font-bold text-gray-900 mb-3 flex items-center gap-2">
                    <span class="w-6 h-6 bg-amber-100 rounded-full flex items-center justify-center text-amber-600 text-sm font-bold">2</span>
                    ما الذي لا نضمنه؟
                </h2>
                <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 text-sm text-amber-800 space-y-2">
                    <p>⚠️ <strong>لا نضمن صحة 100% لبيانات العملاء</strong> للأسباب التالية:</p>
                    <ul class="list-disc list-inside mr-4 space-y-1 text-amber-700">
                        <li>قد يخطئ العميل في كتابة رقم هاتفه</li>
                        <li>قد يستخدم العميل رقم هاتف مؤقت أو غير صحيح</li>
                        <li>قد يملأ شخص النموذج بدون نية حقيقية للخدمة</li>
                        <li>قد يغير العميل رأيه بعد إرسال الطلب</li>
                    </ul>
                </div>
            </section>

            <!-- Bölüm 3: Geçersiz Lead Bildirimi -->
            <section>
                <h2 class="text-lg font-bold text-gray-900 mb-3 flex items-center gap-2">
                    <span class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center text-green-600 text-sm font-bold">3</span>
                    ماذا أفعل إذا كان الطلب غير صالح؟
                </h2>
                <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-sm text-green-800 space-y-3">
                    <p>✅ يمكنك الإبلاغ عن طلب غير صالح إذا:</p>
                    <ul class="list-disc list-inside mr-4 space-y-1 text-green-700">
                        <li>رقم الهاتف غير موجود أو خارج الخدمة</li>
                        <li>الرقم لشخص آخر لا علاقة له بالطلب</li>
                        <li>العميل يؤكد أنه لم يطلب الخدمة</li>
                    </ul>
                    
                    <div class="bg-white rounded-lg p-3 mt-3 border border-green-200">
                        <p class="font-bold text-green-800 mb-2">📋 خطوات الإبلاغ:</p>
                        <ol class="list-decimal list-inside text-green-700 space-y-1">
                            <li>افتح تفاصيل الطلب من "طلباتي"</li>
                            <li>اضغط على "إبلاغ عن طلب غير صالح"</li>
                            <li>اختر سبب الإبلاغ</li>
                            <li>أرسل الإبلاغ خلال <strong>48 ساعة</strong> من استلام الطلب</li>
                        </ol>
                    </div>
                    
                    <p class="text-xs text-green-600 mt-2">
                        * سيتم مراجعة الإبلاغ من قبل الإدارة. إذا تم قبوله، سيتم إضافة طلب بديل لحسابك.
                    </p>
                </div>
            </section>

            <!-- Bölüm 4: Kurallar -->
            <section>
                <h2 class="text-lg font-bold text-gray-900 mb-3 flex items-center gap-2">
                    <span class="w-6 h-6 bg-red-100 rounded-full flex items-center justify-center text-red-600 text-sm font-bold">4</span>
                    قواعد مهمة
                </h2>
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 text-sm text-red-800 space-y-2">
                    <p>🚫 <strong>لن يتم قبول الإبلاغ في الحالات التالية:</strong></p>
                    <ul class="list-disc list-inside mr-4 space-y-1 text-red-700">
                        <li>مرور أكثر من 48 ساعة على استلام الطلب</li>
                        <li>العميل موجود لكنه غير مهتم أو رفض السعر</li>
                        <li>العميل لا يرد على الهاتف (قد يكون مشغولاً)</li>
                        <li>الإبلاغ بدون سبب واضح</li>
                        <li>تجاوز نسبة الإبلاغات 30% من طلباتك</li>
                    </ul>
                </div>
            </section>

            <!-- Bölüm 5: İade Politikası -->
            <section>
                <h2 class="text-lg font-bold text-gray-900 mb-3 flex items-center gap-2">
                    <span class="w-6 h-6 bg-purple-100 rounded-full flex items-center justify-center text-purple-600 text-sm font-bold">5</span>
                    سياسة الاسترداد
                </h2>
                <div class="bg-purple-50 border border-purple-200 rounded-lg p-4 text-sm text-purple-800">
                    <p>💰 <strong>لا يمكن استرداد المبلغ المدفوع</strong> بعد شراء الحزمة.</p>
                    <p class="mt-2 text-purple-700">بدلاً من ذلك، نقدم نظام "استبدال الطلب" للطلبات غير الصالحة المؤكدة.</p>
                </div>
            </section>

            <!-- Kabul Butonu -->
            <div class="pt-4 border-t border-gray-200">
                <p class="text-center text-gray-600 text-sm mb-4">
                    بشرائك لأي حزمة، فإنك توافق على جميع الشروط والسياسات المذكورة أعلاه
                </p>
                <a href="/provider/browse-packages" class="block w-full py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-lg text-center transition-all">
                    فهمت، العودة للحزم
                </a>
            </div>
        </div>
    </div>

    <!-- İletişim -->
    <div class="mt-4 text-center text-sm text-gray-500">
        <p>لديك سؤال؟ تواصل معنا عبر 
            <a href="https://wa.me/966500000000" target="_blank" class="text-green-600 hover:underline">واتساب</a>
        </p>
    </div>
</div>

<?php
$content = ob_get_clean();
$pageTitle = 'سياسة جودة الطلبات';
$currentPage = 'lead-policy';
require __DIR__ . '/layout.php';
?>

