<?php

return [

    'components' => [

        'breadcrumbs' => [

            'home' => 'Ana Sayfa',

        ],

        'global_search' => [

            'placeholder' => 'Arama yapın',

        ],

        'layout' => [

            'header' => [

                'theme_switcher' => [
                    'dark_mode' => 'Karanlık mod',
                    'light_mode' => 'Aydınlık mod',
                    'system_mode' => 'Sistem modu',
                ],

            ],

        ],

    ],

    'pages' => [

        'auth' => [

            'login' => [

                'actions' => [

                    'register' => [
                        'before' => 'veya',
                        'label' => 'hesap oluşturun',
                    ],

                    'request_password_reset' => [
                        'label' => 'Şifrenizi mi unuttunuz?',
                    ],

                ],

                'form' => [

                    'email' => [
                        'label' => 'E-posta adresi',
                    ],

                    'password' => [
                        'label' => 'Şifre',
                    ],

                    'remember' => [
                        'label' => 'Beni hatırla',
                    ],

                ],

                'heading' => 'Giriş yap',

                'messages' => [

                    'failed' => 'Bu kimlik bilgileri kayıtlarımızla eşleşmiyor.',

                ],

                'notifications' => [

                    'throttled' => [
                        'title' => 'Çok fazla giriş denemesi',
                        'body' => ':seconds saniye sonra tekrar deneyin.',
                    ],

                ],

            ],

            'password_reset' => [

                'request' => [

                    'actions' => [

                        'login' => [
                            'label' => 'giriş sayfasına dön',
                        ],

                    ],

                    'form' => [

                        'email' => [
                            'label' => 'E-posta adresi',
                        ],

                    ],

                    'heading' => 'Şifremi unuttum',

                    'notifications' => [

                        'throttled' => [
                            'title' => 'Çok fazla istek',
                            'body' => ':seconds saniye sonra tekrar deneyin.',
                        ],

                    ],

                ],

                'reset' => [

                    'form' => [

                        'email' => [
                            'label' => 'E-posta adresi',
                        ],

                        'password' => [
                            'label' => 'Şifre',
                            'validation_attribute' => 'şifre',
                        ],

                        'password_confirmation' => [
                            'label' => 'Şifre onayı',
                        ],

                    ],

                    'heading' => 'Şifreyi sıfırla',

                    'notifications' => [

                        'invalid_token' => [
                            'title' => 'Geçersiz token',
                            'body' => 'Şifre sıfırlama token\'ı geçersiz. Lütfen yeniden deneyin.',
                        ],

                        'throttled' => [
                            'title' => 'Çok fazla istek',
                            'body' => ':seconds saniye sonra tekrar deneyin.',
                        ],

                    ],

                ],

            ],

        ],

        'dashboard' => [

            'actions' => [

                'filter' => [

                    'form' => [

                        'actions' => [
                            'apply' => 'Uygula',
                            'reset' => 'Sıfırla',
                        ],

                    ],

                ],

            ],

            'heading' => 'Gösterge Paneli',

        ],

    ],

    'resources' => [

        'pages' => [

            'create_record' => [

                'title' => ':label Oluştur',

            ],

            'edit_record' => [

                'title' => ':label Düzenle',

            ],

            'list_records' => [

                'title' => ':label',

            ],

        ],

    ],

    'widgets' => [

        'account_widget' => [

            'actions' => [

                'profile' => [
                    'label' => 'Profil',
                ],

                'logout' => [
                    'label' => 'Çıkış',
                ],

            ],

        ],

        'filament_info_widget' => [

            'actions' => [

                'open_documentation' => [
                    'label' => 'Dökümantasyon',
                ],

                'open_github' => [
                    'label' => 'GitHub',
                ],

            ],

        ],

    ],

];
