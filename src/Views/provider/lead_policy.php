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
        <!-- Header - Tek renk, gradient yok -->
        <div class="p-6" style="background-color: #f59e0b;">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background-color: #d97706;">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-white">سياسة جودة الطلبات</h1>
                    <p class="text-white text-sm mt-1">يرجى قراءة هذه السياسة بعناية قبل الشراء</p>
                </div>
            </div>
        </div>

        <div class="p-6 space-y-6">
            <!-- Bölüm 1: Nasıl Çalışır -->
            <section>
                <h2 class="text-lg font-bold text-gray-900 mb-3 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full flex items-center justify-center text-white text-sm font-bold" style="background-color: #3b82f6;">1</span>
                    كيف نحصل على الطلبات؟
                </h2>
                <div class="rounded-lg p-4 text-sm space-y-2 border" style="background-color: #f9fafb; border-color: #e5e7eb; color: #374151;">
                    <p>• نحصل على طلبات العملاء من خلال <strong style="color: #111827;">إعلانات Google</strong> ونماذج الموقع</p>
                    <p>• يقوم العملاء بملء نموذج يحتوي على: نوع الخدمة، المدينة، رقم الهاتف، ووصف المشكلة</p>
                    <p>• نطلب من العميل إدخال رقم الهاتف مرتين للتأكيد</p>
                    <p>• نستخدم تقنيات لمنع الطلبات المزيفة (مثل حماية السبام)</p>
                </div>
            </section>

            <!-- Bölüm 2: Garanti Yok -->
            <section>
                <h2 class="text-lg font-bold text-gray-900 mb-3 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full flex items-center justify-center text-white text-sm font-bold" style="background-color: #f59e0b;">2</span>
                    ما الذي لا نضمنه؟
                </h2>
                <div class="rounded-lg p-4 text-sm space-y-2 border" style="background-color: #fffbeb; border-color: #fcd34d;">
                    <p style="color: #78350f;">⚠️ <strong>لا نضمن صحة 100% لبيانات العملاء</strong> للأسباب التالية:</p>
                    <ul class="list-disc list-inside mr-4 space-y-1" style="color: #92400e;">
                        <li>قد يخطئ العميل في كتابة رقم هاتفه</li>
                        <li>قد يستخدم العميل رقم هاتف مؤقت أو غير صحيح</li>
                        <li>قد يملأ شخص النموذج بدون نية حقيقية للخدمة</li>
                        <li>قد يغير العميل رأيه بعد إرسال الطلب</li>
                    </ul>
                </div>
            </section>

            <!-- ⭐ Bölüm 3: YENİ - İlk Satın Alma vs Sonraki -->
            <section>
                <h2 class="text-lg font-bold text-gray-900 mb-3 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full flex items-center justify-center text-white text-sm font-bold" style="background-color: #8b5cf6;">3</span>
                    سياسة استبدال الطلبات غير الصالحة ⭐
                </h2>
                <div class="rounded-lg p-4 text-sm border" style="background-color: #f5f3ff; border-color: #c4b5fd;">
                    
                    <!-- İlk Satın Alma -->
                    <div class="mb-4 p-3 rounded-lg border" style="background-color: #ffffff; border-color: #a78bfa;">
                        <p class="font-bold mb-2" style="color: #5b21b6;">🎁 الشراء الأول (مرة واحدة فقط):</p>
                        <p style="color: #6d28d9;">إذا كان هذا <strong>أول شراء لك</strong>، سيتم <strong>استبدال الطلبات غير الصالحة</strong> بطلبات جديدة.</p>
                        <p class="text-xs mt-2" style="color: #7c3aed;">✓ هذه الميزة متاحة مرة واحدة فقط للعملاء الجدد</p>
                    </div>
                    
                    <!-- Sonraki Satın Almalar -->
                    <div class="p-3 rounded-lg border" style="background-color: #ffffff; border-color: #a78bfa;">
                        <p class="font-bold mb-2" style="color: #5b21b6;">📦 الشراء الثاني وما بعده:</p>
                        <p style="color: #6d28d9;">في حالة الإبلاغ عن طلب غير صالح، ستحصل على <strong>+1 طلب إضافي</strong> كتعويض.</p>
                        <ul class="text-xs mt-2 space-y-1" style="color: #7c3aed;">
                            <li>• هذا التعويض <strong>مرة واحدة فقط</strong> لكل حزمة</li>
                            <li>• لا يمكن المطالبة بأكثر من طلب إضافي واحد</li>
                            <li>• يجب الإبلاغ خلال 48 ساعة من استلام الطلب</li>
                        </ul>
                    </div>
                    
                    <!-- Önemli Uyarı -->
                    <div class="mt-4 p-3 rounded-lg" style="background-color: #fef2f2; border: 1px solid #fca5a5;">
                        <p class="font-bold text-sm" style="color: #991b1b;">⚠️ تنبيه مهم:</p>
                        <p class="text-xs mt-1" style="color: #b91c1c;">
                            بشرائك للحزمة الثانية، فإنك تقر بأنك على علم بهذه السياسة ولا يحق لك تقديم شكوى أو المطالبة بأكثر من التعويض المحدد.
                        </p>
                    </div>
                </div>
            </section>

            <!-- Bölüm 4: Geçersiz Lead Bildirimi -->
            <section>
                <h2 class="text-lg font-bold text-gray-900 mb-3 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full flex items-center justify-center text-white text-sm font-bold" style="background-color: #22c55e;">4</span>
                    ماذا أفعل إذا كان الطلب غير صالح؟
                </h2>
                <div class="rounded-lg p-4 text-sm space-y-3 border" style="background-color: #f0fdf4; border-color: #86efac;">
                    <p style="color: #14532d;">✅ يمكنك الإبلاغ عن طلب غير صالح إذا:</p>
                    <ul class="list-disc list-inside mr-4 space-y-1" style="color: #166534;">
                        <li>رقم الهاتف غير موجود أو خارج الخدمة</li>
                        <li>الرقم لشخص آخر لا علاقة له بالطلب</li>
                        <li>العميل يؤكد أنه لم يطلب الخدمة</li>
                    </ul>
                    
                    <div class="rounded-lg p-3 mt-3 border" style="background-color: #ffffff; border-color: #86efac;">
                        <p class="font-bold mb-2" style="color: #14532d;">📋 خطوات الإبلاغ:</p>
                        <ol class="list-decimal list-inside space-y-1" style="color: #166534;">
                            <li>افتح تفاصيل الطلب من "طلباتي"</li>
                            <li>اضغط على "إبلاغ عن طلب غير صالح"</li>
                            <li>اختر سبب الإبلاغ</li>
                            <li>أرسل الإبلاغ خلال <strong style="color: #14532d;">48 ساعة</strong> من استلام الطلب</li>
                        </ol>
                    </div>
                    
                    <p class="text-xs mt-2" style="color: #15803d;">
                        * سيتم مراجعة الإبلاغ من قبل الإدارة. إذا تم قبوله، سيتم تطبيق سياسة التعويض المناسبة.
                    </p>
                </div>
            </section>

            <!-- Bölüm 5: Kurallar -->
            <section>
                <h2 class="text-lg font-bold text-gray-900 mb-3 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full flex items-center justify-center text-white text-sm font-bold" style="background-color: #ef4444;">5</span>
                    قواعد مهمة
                </h2>
                <div class="rounded-lg p-4 text-sm space-y-2 border" style="background-color: #fef2f2; border-color: #fca5a5;">
                    <p style="color: #7f1d1d;">🚫 <strong>لن يتم قبول الإبلاغ في الحالات التالية:</strong></p>
                    <ul class="list-disc list-inside mr-4 space-y-1" style="color: #991b1b;">
                        <li>مرور أكثر من 48 ساعة على استلام الطلب</li>
                        <li>العميل موجود لكنه غير مهتم أو رفض السعر</li>
                        <li>العميل لا يرد على الهاتف (قد يكون مشغولاً)</li>
                        <li>الإبلاغ بدون سبب واضح</li>
                        <li>تجاوز نسبة الإبلاغات 30% من طلباتك</li>
                        <li>المطالبة بأكثر من +1 طلب تعويض (للشراء الثاني وما بعده)</li>
                    </ul>
                </div>
            </section>

            <!-- Bölüm 6: İade Politikası -->
            <section>
                <h2 class="text-lg font-bold text-gray-900 mb-3 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full flex items-center justify-center text-white text-sm font-bold" style="background-color: #6b7280;">6</span>
                    سياسة الاسترداد
                </h2>
                <div class="rounded-lg p-4 text-sm border" style="background-color: #f9fafb; border-color: #d1d5db;">
                    <p style="color: #374151;">💰 <strong>لا يمكن استرداد المبلغ المدفوع</strong> بعد شراء الحزمة.</p>
                    <p class="mt-2" style="color: #4b5563;">بدلاً من ذلك، نقدم نظام التعويض المذكور أعلاه حسب نوع الشراء (أول مرة أو تكرار).</p>
                </div>
            </section>

            <!-- Kabul Butonu -->
            <div class="pt-4 border-t border-gray-200">
                <div class="p-4 rounded-lg mb-4" style="background-color: #ecfdf5; border: 2px solid #10b981;">
                    <p class="text-center text-sm font-bold" style="color: #065f46;">
                        ✅ بشرائك لأي حزمة، فإنك توافق على جميع الشروط والسياسات المذكورة أعلاه
                    </p>
                </div>
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
