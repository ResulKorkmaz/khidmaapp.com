<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تأكيد البريد الإلكتروني - KhidmaApp</title>
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
            <div class="bg-gradient-to-r from-green-600 to-green-500 p-6 text-center">
                <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h1 class="text-xl font-bold text-white">تأكيد البريد الإلكتروني</h1>
                <p class="text-green-100 mt-2 text-sm">خطوة أخيرة لتفعيل حسابك</p>
            </div>

            <!-- Content -->
            <div class="p-6">
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

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-red-800 text-sm"><?= $_SESSION['error'] ?></p>
                        </div>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['warning'])): ?>
                    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 mb-6">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-yellow-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <p class="text-yellow-800 text-sm"><?= $_SESSION['warning'] ?></p>
                        </div>
                    </div>
                    <?php unset($_SESSION['warning']); ?>
                <?php endif; ?>

                <!-- E-posta Bilgisi -->
                <?php if (isset($_SESSION['pending_verification_email'])): ?>
                <div class="text-center mb-6">
                    <p class="text-gray-600 mb-2">تم إرسال رابط التأكيد إلى:</p>
                    <p class="text-lg font-bold text-gray-900 bg-gray-100 rounded-lg px-4 py-2 inline-block">
                        <?= htmlspecialchars($_SESSION['pending_verification_email']) ?>
                    </p>
                </div>
                <?php endif; ?>

                <!-- Talimatlar -->
                <div class="bg-blue-50 rounded-xl p-4 mb-6">
                    <h3 class="font-bold text-blue-900 mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        الخطوات التالية:
                    </h3>
                    <ol class="text-sm text-blue-800 space-y-2">
                        <li class="flex items-start gap-2">
                            <span class="bg-blue-200 text-blue-800 rounded-full w-5 h-5 flex items-center justify-center flex-shrink-0 text-xs font-bold">1</span>
                            افتح بريدك الإلكتروني
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="bg-blue-200 text-blue-800 rounded-full w-5 h-5 flex items-center justify-center flex-shrink-0 text-xs font-bold">2</span>
                            ابحث عن رسالة من KhidmaApp
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="bg-blue-200 text-blue-800 rounded-full w-5 h-5 flex items-center justify-center flex-shrink-0 text-xs font-bold">3</span>
                            اضغط على زر "تأكيد البريد الإلكتروني"
                        </li>
                    </ol>
                </div>

                <!-- Spam Uyarısı -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 mb-6">
                    <div class="flex items-start gap-3">
                        <span class="text-xl">⚠️</span>
                        <div>
                            <p class="font-bold text-yellow-800 text-sm">لم تجد الرسالة؟</p>
                            <p class="text-yellow-700 text-xs mt-1">تحقق من مجلد الرسائل غير المرغوب فيها (Spam) أو البريد غير الهام (Junk)</p>
                        </div>
                    </div>
                </div>

                <!-- Yeniden Gönder Butonu -->
                <form method="POST" action="/provider/resend-verification-guest" id="resendForm">
                    <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                    
                    <button type="submit" 
                            id="resendBtn"
                            class="w-full py-3 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200 transition-all flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        <span id="resendBtnText">إعادة إرسال رابط التأكيد</span>
                    </button>
                </form>

                <!-- Giriş Linki -->
                <div class="mt-6 text-center">
                    <p class="text-gray-500 text-sm mb-2">هل قمت بتأكيد بريدك الإلكتروني؟</p>
                    <a href="/" class="text-green-600 hover:text-green-700 font-bold">
                        تسجيل الدخول ←
                    </a>
                </div>
            </div>
        </div>

        <!-- Güvenlik Bilgisi -->
        <div class="mt-6 text-center">
            <div class="inline-flex items-center gap-2 text-gray-500 text-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                تأكيد البريد الإلكتروني يحمي حسابك من الاستخدام غير المصرح به
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-4">
            <p class="text-gray-500 text-sm">
                © <?= date('Y') ?> KhidmaApp. جميع الحقوق محفوظة.
            </p>
        </div>
    </div>

    <script>
        // Cooldown için sayaç
        let cooldownSeconds = 0;
        const resendBtn = document.getElementById('resendBtn');
        const resendBtnText = document.getElementById('resendBtnText');
        
        // Form submit
        document.getElementById('resendForm').addEventListener('submit', function(e) {
            if (cooldownSeconds > 0) {
                e.preventDefault();
                return;
            }
            
            // 2 dakika cooldown başlat
            cooldownSeconds = 120;
            resendBtn.disabled = true;
            updateCooldownText();
            
            const interval = setInterval(() => {
                cooldownSeconds--;
                if (cooldownSeconds <= 0) {
                    clearInterval(interval);
                    resendBtn.disabled = false;
                    resendBtnText.textContent = 'إعادة إرسال رابط التأكيد';
                } else {
                    updateCooldownText();
                }
            }, 1000);
        });
        
        function updateCooldownText() {
            const minutes = Math.floor(cooldownSeconds / 60);
            const seconds = cooldownSeconds % 60;
            resendBtnText.textContent = `انتظر ${minutes}:${seconds.toString().padStart(2, '0')}`;
        }
    </script>
</body>
</html>

