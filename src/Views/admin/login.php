<!DOCTYPE html>
<html lang="tr" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giriş Yap - Admin Paneli</title>
    <link href="/assets/css/app.css" rel="stylesheet">
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
            <!-- Logo/Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Admin Paneli</h1>
                <p class="text-gray-600">Yönetim paneline giriş yapın</p>
            </div>
            
            <!-- Error Message -->
            <?php if (!empty($error)): ?>
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
                    <p class="text-red-700 text-sm"><?= htmlspecialchars($error) ?></p>
                </div>
            <?php endif; ?>
            
            <!-- Login Form -->
            <form method="POST" action="/admin/login" class="space-y-6">
                <div>
                    <label for="username" class="block text-sm font-semibold text-gray-900 mb-2">
                        Kullanıcı Adı
                    </label>
                    <input type="text" 
                           id="username" 
                           name="username" 
                           required
                           autofocus
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                </div>
                
                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-900 mb-2">
                        Şifre
                    </label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           required
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                </div>
                
                <button type="submit" 
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg">
                    Giriş Yap
                </button>
            </form>
            
            <!-- Footer -->
            <div class="mt-8 text-center">
                <a href="/" class="text-sm text-gray-500 hover:text-gray-700 transition-colors">
                    Ana Sayfaya Dön
                </a>
            </div>
        </div>
    </div>
</body>
</html>

