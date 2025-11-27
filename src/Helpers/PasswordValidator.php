<?php
/**
 * KhidmaApp.com - Password Validator
 * 
 * Güçlü şifre doğrulama kuralları:
 * - En az 8 karakter
 * - En az 1 büyük harf
 * - En az 1 küçük harf
 * - En az 1 rakam
 * - En az 1 özel karakter
 * - Art arda 2'den fazla aynı karakter yasak (111, aaa)
 * - Art arda 2'den fazla ardışık sayı yasak (123, 321)
 * - Art arda 2'den fazla ardışık harf yasak (abc, cba)
 */

class PasswordValidator
{
    private array $errors = [];
    private string $password;
    
    // Minimum şifre uzunluğu
    const MIN_LENGTH = 8;
    
    // Maksimum art arda tekrar sayısı
    const MAX_CONSECUTIVE_REPEAT = 2;
    
    // Maksimum ardışık karakter sayısı
    const MAX_SEQUENTIAL = 2;
    
    /**
     * Şifreyi doğrula
     */
    public function validate(string $password): bool
    {
        $this->password = $password;
        $this->errors = [];
        
        $this->checkLength();
        $this->checkUppercase();
        $this->checkLowercase();
        $this->checkNumber();
        $this->checkSpecialChar();
        $this->checkConsecutiveRepeats();
        $this->checkSequentialNumbers();
        $this->checkSequentialLetters();
        
        return empty($this->errors);
    }
    
    /**
     * Hata mesajlarını al
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
    
    /**
     * İlk hata mesajını al
     */
    public function getFirstError(): ?string
    {
        return $this->errors[0] ?? null;
    }
    
    /**
     * Tüm hataları tek string olarak al
     */
    public function getErrorsAsString(string $separator = ', '): string
    {
        return implode($separator, $this->errors);
    }
    
    /**
     * Minimum uzunluk kontrolü
     */
    private function checkLength(): void
    {
        if (strlen($this->password) < self::MIN_LENGTH) {
            $this->errors[] = 'Şifre en az ' . self::MIN_LENGTH . ' karakter olmalıdır';
        }
    }
    
    /**
     * Büyük harf kontrolü
     */
    private function checkUppercase(): void
    {
        if (!preg_match('/[A-Z]/', $this->password)) {
            $this->errors[] = 'Şifre en az 1 büyük harf içermelidir';
        }
    }
    
    /**
     * Küçük harf kontrolü
     */
    private function checkLowercase(): void
    {
        if (!preg_match('/[a-z]/', $this->password)) {
            $this->errors[] = 'Şifre en az 1 küçük harf içermelidir';
        }
    }
    
    /**
     * Rakam kontrolü
     */
    private function checkNumber(): void
    {
        if (!preg_match('/[0-9]/', $this->password)) {
            $this->errors[] = 'Şifre en az 1 rakam içermelidir';
        }
    }
    
    /**
     * Özel karakter kontrolü
     */
    private function checkSpecialChar(): void
    {
        if (!preg_match('/[!@#$%^&*()_+\-=\[\]{};\':"\\|,.<>\/?~`]/', $this->password)) {
            $this->errors[] = 'Şifre en az 1 özel karakter içermelidir (!@#$%^&*...)';
        }
    }
    
    /**
     * Art arda tekrarlayan karakter kontrolü (111, aaa, !!!)
     */
    private function checkConsecutiveRepeats(): void
    {
        $length = strlen($this->password);
        $consecutiveCount = 1;
        
        for ($i = 1; $i < $length; $i++) {
            if ($this->password[$i] === $this->password[$i - 1]) {
                $consecutiveCount++;
                if ($consecutiveCount > self::MAX_CONSECUTIVE_REPEAT) {
                    $this->errors[] = 'Aynı karakter art arda ' . (self::MAX_CONSECUTIVE_REPEAT + 1) . ' veya daha fazla kez kullanılamaz';
                    return;
                }
            } else {
                $consecutiveCount = 1;
            }
        }
    }
    
    /**
     * Ardışık sayı kontrolü (123, 234, 321, 432)
     */
    private function checkSequentialNumbers(): void
    {
        $length = strlen($this->password);
        $ascendingCount = 1;
        $descendingCount = 1;
        
        for ($i = 1; $i < $length; $i++) {
            $current = $this->password[$i];
            $previous = $this->password[$i - 1];
            
            // Sadece rakamlar için kontrol
            if (is_numeric($current) && is_numeric($previous)) {
                $diff = ord($current) - ord($previous);
                
                // Artan sıra (1, 2, 3)
                if ($diff === 1) {
                    $ascendingCount++;
                    $descendingCount = 1;
                    if ($ascendingCount > self::MAX_SEQUENTIAL) {
                        $this->errors[] = 'Art arda ' . (self::MAX_SEQUENTIAL + 1) . ' veya daha fazla ardışık sayı kullanılamaz (örn: 123)';
                        return;
                    }
                }
                // Azalan sıra (3, 2, 1)
                elseif ($diff === -1) {
                    $descendingCount++;
                    $ascendingCount = 1;
                    if ($descendingCount > self::MAX_SEQUENTIAL) {
                        $this->errors[] = 'Art arda ' . (self::MAX_SEQUENTIAL + 1) . ' veya daha fazla ardışık sayı kullanılamaz (örn: 321)';
                        return;
                    }
                }
                else {
                    $ascendingCount = 1;
                    $descendingCount = 1;
                }
            } else {
                $ascendingCount = 1;
                $descendingCount = 1;
            }
        }
    }
    
    /**
     * Ardışık harf kontrolü (abc, bcd, cba, dcb)
     */
    private function checkSequentialLetters(): void
    {
        $length = strlen($this->password);
        $ascendingCount = 1;
        $descendingCount = 1;
        
        for ($i = 1; $i < $length; $i++) {
            $current = strtolower($this->password[$i]);
            $previous = strtolower($this->password[$i - 1]);
            
            // Sadece harfler için kontrol
            if (ctype_alpha($current) && ctype_alpha($previous)) {
                $diff = ord($current) - ord($previous);
                
                // Artan sıra (a, b, c)
                if ($diff === 1) {
                    $ascendingCount++;
                    $descendingCount = 1;
                    if ($ascendingCount > self::MAX_SEQUENTIAL) {
                        $this->errors[] = 'Art arda ' . (self::MAX_SEQUENTIAL + 1) . ' veya daha fazla ardışık harf kullanılamaz (örn: abc)';
                        return;
                    }
                }
                // Azalan sıra (c, b, a)
                elseif ($diff === -1) {
                    $descendingCount++;
                    $ascendingCount = 1;
                    if ($descendingCount > self::MAX_SEQUENTIAL) {
                        $this->errors[] = 'Art arda ' . (self::MAX_SEQUENTIAL + 1) . ' veya daha fazla ardışık harf kullanılamaz (örn: cba)';
                        return;
                    }
                }
                else {
                    $ascendingCount = 1;
                    $descendingCount = 1;
                }
            } else {
                $ascendingCount = 1;
                $descendingCount = 1;
            }
        }
    }
    
    /**
     * Şifre gücünü hesapla (0-100)
     */
    public function getStrength(string $password): int
    {
        $score = 0;
        
        // Uzunluk puanı (max 25)
        $length = strlen($password);
        if ($length >= 8) $score += 10;
        if ($length >= 12) $score += 10;
        if ($length >= 16) $score += 5;
        
        // Büyük harf (max 15)
        if (preg_match('/[A-Z]/', $password)) $score += 10;
        if (preg_match('/[A-Z].*[A-Z]/', $password)) $score += 5;
        
        // Küçük harf (max 15)
        if (preg_match('/[a-z]/', $password)) $score += 10;
        if (preg_match('/[a-z].*[a-z]/', $password)) $score += 5;
        
        // Rakam (max 15)
        if (preg_match('/[0-9]/', $password)) $score += 10;
        if (preg_match('/[0-9].*[0-9]/', $password)) $score += 5;
        
        // Özel karakter (max 20)
        if (preg_match('/[!@#$%^&*()_+\-=\[\]{};\':"\\|,.<>\/?~`]/', $password)) $score += 15;
        if (preg_match('/[!@#$%^&*()_+\-=\[\]{};\':"\\|,.<>\/?~`].*[!@#$%^&*()_+\-=\[\]{};\':"\\|,.<>\/?~`]/', $password)) $score += 5;
        
        // Ceza puanları
        // Art arda tekrar (-10)
        if (preg_match('/(.)\1{2,}/', $password)) $score -= 10;
        
        // Ardışık sayılar (-10)
        if (preg_match('/(012|123|234|345|456|567|678|789|890|987|876|765|654|543|432|321|210)/', $password)) $score -= 10;
        
        return max(0, min(100, $score));
    }
    
    /**
     * Şifre gücü seviyesi
     */
    public function getStrengthLevel(string $password): string
    {
        $score = $this->getStrength($password);
        
        if ($score < 30) return 'weak';
        if ($score < 50) return 'fair';
        if ($score < 70) return 'good';
        if ($score < 90) return 'strong';
        return 'excellent';
    }
    
    /**
     * Şifre gücü etiketi (Türkçe)
     */
    public function getStrengthLabel(string $password): string
    {
        $level = $this->getStrengthLevel($password);
        
        $labels = [
            'weak' => 'Zayıf',
            'fair' => 'Orta',
            'good' => 'İyi',
            'strong' => 'Güçlü',
            'excellent' => 'Çok Güçlü'
        ];
        
        return $labels[$level] ?? 'Bilinmiyor';
    }
    
    /**
     * Statik doğrulama metodu
     */
    public static function isValid(string $password, ?array &$errors = null): bool
    {
        $validator = new self();
        $isValid = $validator->validate($password);
        $errors = $validator->getErrors();
        return $isValid;
    }
}

