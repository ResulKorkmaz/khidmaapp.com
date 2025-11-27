<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إعادة إرسال رابط التأكيد - KhidmaApp</title>
    <link href="/assets/css/app.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Logo -->
        <div class="text-center mb-8">
            <a href="/" class="inline-flex items-center gap-2">
                <div class="w-12 h-12 bg-green-600 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <span class="text-2xl font-bold text-gray-900">KhidmaApp</span>
            </a>
        </div>

        <!-- Card -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-yellow-500 to-orange-500 p-6 text-center">
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h1 class="text-xl font-bold text-white">انتهت صلاحية الرابط</h1>
                <p class="text-yellow-100 mt-2 text-sm">يرجى طلب رابط تأكيد جديد</p>
            </div>

            <!-- Content -->
            <div class="p-6">
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-red-800 text-sm"><?= htmlspecialchars($_SESSION['error']) ?></p>
                        </div>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['success'])): ?>
                    <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-6">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-green-800 text-sm"><?= htmlspecialchars($_SESSION['success']) ?></p>
                        </div>
                    </div>
                    <?php unset($_SESSION['success']); ?>
                <?php endif; ?>

                <div class="text-center mb-6">
                    <p class="text-gray-600">
                        رابط تأكيد البريد الإلكتروني الذي أرسلناه لك قد انتهت صلاحيته.
                        اضغط على الزر أدناه لإرسال رابط جديد.
                    </p>
                </div>

                <form method="POST" action="/provider/resend-verification-guest">
                    <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                    
                    <button type="submit" 
                            class="w-full py-3 bg-gradient-to-r from-green-600 to-green-500 text-white font-bold rounded-xl hover:from-green-700 hover:to-green-600 transition-all flex items-center justify-center gap-2 shadow-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        إرسال رابط جديد
                    </button>
                </form>

                <div class="mt-6 text-center">
                    <a href="/provider/login" class="text-green-600 hover:text-green-700 font-medium text-sm">
                        ← العودة لتسجيل الدخول
                    </a>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-6">
            <p class="text-gray-500 text-sm">
                © <?= date('Y') ?> KhidmaApp. جميع الحقوق محفوظة.
            </p>
        </div>
    </div>
</body>
</html>

