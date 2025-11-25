<?php

/**
 * KhidmaApp.com - Notification Service
 * 
 * Email ve SMS bildirimleri için merkezi servis
 */

class NotificationService 
{
    private $pdo;
    
    // Notification types
    const TYPE_EMAIL = 'email';
    const TYPE_SMS = 'sms';
    const TYPE_WHATSAPP = 'whatsapp';
    
    // Notification templates
    const TEMPLATE_NEW_LEAD = 'new_lead';
    const TEMPLATE_LEAD_STATUS = 'lead_status';
    const TEMPLATE_WELCOME = 'welcome';
    
    public function __construct($pdo = null) 
    {
        $this->pdo = $pdo ?? getDatabase();
    }
    
    /**
     * Yeni lead bildirimi gönder (Admin'e)
     */
    public function sendNewLeadNotification($leadId, array $leadData) 
    {
        try {
            $message = $this->buildNewLeadMessage($leadData);
            
            // Log notification
            $this->logNotification(
                self::TYPE_SMS,
                self::TEMPLATE_NEW_LEAD,
                $leadId,
                $message
            );
            
            // Send SMS to admin (future implementation)
            // $this->sendSMS(ADMIN_PHONE, $message);
            
            // Send Email to admin (future implementation)
            // $this->sendEmail(ADMIN_EMAIL, 'New Lead Received', $message);
            
            // Send WhatsApp notification (future implementation)
            // $this->sendWhatsApp(ADMIN_PHONE, $message);
            
            return true;
            
        } catch (Exception $e) {
            error_log("Notification error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Lead durum değişikliği bildirimi (Müşteriye)
     */
    public function sendLeadStatusNotification($leadId, $oldStatus, $newStatus, $customerPhone) 
    {
        try {
            $message = $this->buildStatusChangeMessage($oldStatus, $newStatus);
            
            // Log notification
            $this->logNotification(
                self::TYPE_SMS,
                self::TEMPLATE_LEAD_STATUS,
                $leadId,
                $message,
                $customerPhone
            );
            
            // Send SMS to customer (future implementation)
            // $this->sendSMS($customerPhone, $message);
            
            return true;
            
        } catch (Exception $e) {
            error_log("Status notification error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Build new lead message (Türkçe - Admin için)
     */
    private function buildNewLeadMessage(array $leadData) 
    {
        $services = getServiceTypes();
        $cities = getCities();
        
        $serviceName = $services[$leadData['service_type']]['tr'] ?? $leadData['service_type'];
        $cityName = $cities[$leadData['city']]['tr'] ?? $leadData['city'];
        
        $message = "Yeni Lead!\n";
        $message .= "Hizmet: {$serviceName}\n";
        $message .= "Şehir: {$cityName}\n";
        $message .= "Telefon: {$leadData['phone']}\n";
        
        if (!empty($leadData['description'])) {
            $message .= "Açıklama: " . substr($leadData['description'], 0, 100) . "...\n";
        }
        
        return $message;
    }
    
    /**
     * Build status change message (Müşteri için Arapça kalmalı)
     */
    private function buildStatusChangeMessage($oldStatus, $newStatus) 
    {
        $statusLabels = [
            'new' => 'جديد',
            'verified' => 'تم التحقق',
            'sold' => 'قيد المعالجة',
            'invalid' => 'ملغي'
        ];
        
        $newStatusLabel = $statusLabels[$newStatus] ?? $newStatus;
        
        // Müşteriye giden mesajlar Arapça olmalı
        $messages = [
            'verified' => "تم تأكيد طلبك. سنتواصل معك قريباً للبدء بالخدمة. - خدمة",
            'sold' => "طلبك قيد المعالجة. سيتواصل معك مقدم الخدمة قريباً. - خدمة",
            'invalid' => "عذراً، لم نتمكن من معالجة طلبك. يرجى التواصل معنا للمزيد من المعلومات. - خدمة"
        ];
        
        return $messages[$newStatus] ?? "تحديث: حالة طلبك تغيرت إلى {$newStatusLabel}. - خدمة";
    }
    
    /**
     * Send SMS (Mock implementation - integrate with provider)
     */
    private function sendSMS($phone, $message) 
    {
        // TODO: Integrate with SMS provider (Twilio, Vonage, etc.)
        // Example: Twilio
        /*
        $sid = env('TWILIO_SID');
        $token = env('TWILIO_TOKEN');
        $from = env('TWILIO_FROM');
        
        $client = new Twilio\Rest\Client($sid, $token);
        $client->messages->create($phone, [
            'from' => $from,
            'body' => $message
        ]);
        */
        
        error_log("SMS to {$phone}: {$message}");
        return true;
    }
    
    /**
     * Send Email (Mock implementation)
     */
    private function sendEmail($to, $subject, $body) 
    {
        // TODO: Integrate with email provider (PHPMailer, SendGrid, etc.)
        // Example: PHPMailer
        /*
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = env('MAIL_HOST');
        $mail->SMTPAuth = true;
        $mail->Username = env('MAIL_USERNAME');
        $mail->Password = env('MAIL_PASSWORD');
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = env('MAIL_PORT');
        
        $mail->setFrom(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
        $mail->addAddress($to);
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->send();
        */
        
        error_log("Email to {$to}: {$subject}");
        return true;
    }
    
    /**
     * Send WhatsApp notification (Future implementation)
     */
    private function sendWhatsApp($phone, $message) 
    {
        // TODO: Integrate with WhatsApp Business API
        // https://developers.facebook.com/docs/whatsapp
        
        error_log("WhatsApp to {$phone}: {$message}");
        return true;
    }
    
    /**
     * Log notification to database
     */
    private function logNotification($type, $template, $leadId, $message, $recipient = null) 
    {
        if (!$this->pdo) {
            return false;
        }
        
        try {
            // Create notifications table if needed
            $createTable = "CREATE TABLE IF NOT EXISTS notifications (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                type VARCHAR(20) NOT NULL,
                template VARCHAR(50) NOT NULL,
                lead_id INT UNSIGNED NULL,
                recipient VARCHAR(100) NULL,
                message TEXT NOT NULL,
                status ENUM('pending', 'sent', 'failed') DEFAULT 'pending',
                sent_at TIMESTAMP NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                INDEX idx_lead_id (lead_id),
                INDEX idx_status (status),
                INDEX idx_created_at (created_at)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
            
            $this->pdo->exec($createTable);
            
            // Insert notification log
            $sql = "INSERT INTO notifications (type, template, lead_id, recipient, message, status, created_at) 
                    VALUES (?, ?, ?, ?, ?, 'pending', NOW())";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                $type,
                $template,
                $leadId,
                $recipient,
                $message
            ]);
            
            return true;
            
        } catch (Exception $e) {
            error_log("Notification log error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get notification history
     */
    public function getNotificationHistory($leadId = null, $limit = 50) 
    {
        if (!$this->pdo) {
            return [];
        }
        
        try {
            $sql = "SELECT * FROM notifications";
            $params = [];
            
            if ($leadId) {
                $sql .= " WHERE lead_id = ?";
                $params[] = $leadId;
            }
            
            $sql .= " ORDER BY created_at DESC LIMIT ?";
            $params[] = $limit;
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            error_log("Notification history error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Mark notification as sent
     */
    public function markAsSent($notificationId) 
    {
        if (!$this->pdo) {
            return false;
        }
        
        try {
            $sql = "UPDATE notifications SET status = 'sent', sent_at = NOW() WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$notificationId]);
            
        } catch (Exception $e) {
            error_log("Mark sent error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get pending notifications
     */
    public function getPendingNotifications($limit = 100) 
    {
        if (!$this->pdo) {
            return [];
        }
        
        try {
            $sql = "SELECT * FROM notifications WHERE status = 'pending' ORDER BY created_at ASC LIMIT ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(1, $limit, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            error_log("Pending notifications error: " . $e->getMessage());
            return [];
        }
    }
}


/**
 * KhidmaApp.com - Notification Service
 * 
 * Email ve SMS bildirimleri için merkezi servis
 */

class NotificationService 
{
    private $pdo;
    
    // Notification types
    const TYPE_EMAIL = 'email';
    const TYPE_SMS = 'sms';
    const TYPE_WHATSAPP = 'whatsapp';
    
    // Notification templates
    const TEMPLATE_NEW_LEAD = 'new_lead';
    const TEMPLATE_LEAD_STATUS = 'lead_status';
    const TEMPLATE_WELCOME = 'welcome';
    
    public function __construct($pdo = null) 
    {
        $this->pdo = $pdo ?? getDatabase();
    }
    
    /**
     * Yeni lead bildirimi gönder (Admin'e)
     */
    public function sendNewLeadNotification($leadId, array $leadData) 
    {
        try {
            $message = $this->buildNewLeadMessage($leadData);
            
            // Log notification
            $this->logNotification(
                self::TYPE_SMS,
                self::TEMPLATE_NEW_LEAD,
                $leadId,
                $message
            );
            
            // Send SMS to admin (future implementation)
            // $this->sendSMS(ADMIN_PHONE, $message);
            
            // Send Email to admin (future implementation)
            // $this->sendEmail(ADMIN_EMAIL, 'New Lead Received', $message);
            
            // Send WhatsApp notification (future implementation)
            // $this->sendWhatsApp(ADMIN_PHONE, $message);
            
            return true;
            
        } catch (Exception $e) {
            error_log("Notification error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Lead durum değişikliği bildirimi (Müşteriye)
     */
    public function sendLeadStatusNotification($leadId, $oldStatus, $newStatus, $customerPhone) 
    {
        try {
            $message = $this->buildStatusChangeMessage($oldStatus, $newStatus);
            
            // Log notification
            $this->logNotification(
                self::TYPE_SMS,
                self::TEMPLATE_LEAD_STATUS,
                $leadId,
                $message,
                $customerPhone
            );
            
            // Send SMS to customer (future implementation)
            // $this->sendSMS($customerPhone, $message);
            
            return true;
            
        } catch (Exception $e) {
            error_log("Status notification error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Build new lead message (Türkçe - Admin için)
     */
    private function buildNewLeadMessage(array $leadData) 
    {
        $services = getServiceTypes();
        $cities = getCities();
        
        $serviceName = $services[$leadData['service_type']]['tr'] ?? $leadData['service_type'];
        $cityName = $cities[$leadData['city']]['tr'] ?? $leadData['city'];
        
        $message = "Yeni Lead!\n";
        $message .= "Hizmet: {$serviceName}\n";
        $message .= "Şehir: {$cityName}\n";
        $message .= "Telefon: {$leadData['phone']}\n";
        
        if (!empty($leadData['description'])) {
            $message .= "Açıklama: " . substr($leadData['description'], 0, 100) . "...\n";
        }
        
        return $message;
    }
    
    /**
     * Build status change message (Müşteri için Arapça kalmalı)
     */
    private function buildStatusChangeMessage($oldStatus, $newStatus) 
    {
        $statusLabels = [
            'new' => 'جديد',
            'verified' => 'تم التحقق',
            'sold' => 'قيد المعالجة',
            'invalid' => 'ملغي'
        ];
        
        $newStatusLabel = $statusLabels[$newStatus] ?? $newStatus;
        
        // Müşteriye giden mesajlar Arapça olmalı
        $messages = [
            'verified' => "تم تأكيد طلبك. سنتواصل معك قريباً للبدء بالخدمة. - خدمة",
            'sold' => "طلبك قيد المعالجة. سيتواصل معك مقدم الخدمة قريباً. - خدمة",
            'invalid' => "عذراً، لم نتمكن من معالجة طلبك. يرجى التواصل معنا للمزيد من المعلومات. - خدمة"
        ];
        
        return $messages[$newStatus] ?? "تحديث: حالة طلبك تغيرت إلى {$newStatusLabel}. - خدمة";
    }
    
    /**
     * Send SMS (Mock implementation - integrate with provider)
     */
    private function sendSMS($phone, $message) 
    {
        // TODO: Integrate with SMS provider (Twilio, Vonage, etc.)
        // Example: Twilio
        /*
        $sid = env('TWILIO_SID');
        $token = env('TWILIO_TOKEN');
        $from = env('TWILIO_FROM');
        
        $client = new Twilio\Rest\Client($sid, $token);
        $client->messages->create($phone, [
            'from' => $from,
            'body' => $message
        ]);
        */
        
        error_log("SMS to {$phone}: {$message}");
        return true;
    }
    
    /**
     * Send Email (Mock implementation)
     */
    private function sendEmail($to, $subject, $body) 
    {
        // TODO: Integrate with email provider (PHPMailer, SendGrid, etc.)
        // Example: PHPMailer
        /*
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = env('MAIL_HOST');
        $mail->SMTPAuth = true;
        $mail->Username = env('MAIL_USERNAME');
        $mail->Password = env('MAIL_PASSWORD');
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = env('MAIL_PORT');
        
        $mail->setFrom(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
        $mail->addAddress($to);
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->send();
        */
        
        error_log("Email to {$to}: {$subject}");
        return true;
    }
    
    /**
     * Send WhatsApp notification (Future implementation)
     */
    private function sendWhatsApp($phone, $message) 
    {
        // TODO: Integrate with WhatsApp Business API
        // https://developers.facebook.com/docs/whatsapp
        
        error_log("WhatsApp to {$phone}: {$message}");
        return true;
    }
    
    /**
     * Log notification to database
     */
    private function logNotification($type, $template, $leadId, $message, $recipient = null) 
    {
        if (!$this->pdo) {
            return false;
        }
        
        try {
            // Create notifications table if needed
            $createTable = "CREATE TABLE IF NOT EXISTS notifications (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                type VARCHAR(20) NOT NULL,
                template VARCHAR(50) NOT NULL,
                lead_id INT UNSIGNED NULL,
                recipient VARCHAR(100) NULL,
                message TEXT NOT NULL,
                status ENUM('pending', 'sent', 'failed') DEFAULT 'pending',
                sent_at TIMESTAMP NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                INDEX idx_lead_id (lead_id),
                INDEX idx_status (status),
                INDEX idx_created_at (created_at)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
            
            $this->pdo->exec($createTable);
            
            // Insert notification log
            $sql = "INSERT INTO notifications (type, template, lead_id, recipient, message, status, created_at) 
                    VALUES (?, ?, ?, ?, ?, 'pending', NOW())";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                $type,
                $template,
                $leadId,
                $recipient,
                $message
            ]);
            
            return true;
            
        } catch (Exception $e) {
            error_log("Notification log error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get notification history
     */
    public function getNotificationHistory($leadId = null, $limit = 50) 
    {
        if (!$this->pdo) {
            return [];
        }
        
        try {
            $sql = "SELECT * FROM notifications";
            $params = [];
            
            if ($leadId) {
                $sql .= " WHERE lead_id = ?";
                $params[] = $leadId;
            }
            
            $sql .= " ORDER BY created_at DESC LIMIT ?";
            $params[] = $limit;
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            error_log("Notification history error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Mark notification as sent
     */
    public function markAsSent($notificationId) 
    {
        if (!$this->pdo) {
            return false;
        }
        
        try {
            $sql = "UPDATE notifications SET status = 'sent', sent_at = NOW() WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$notificationId]);
            
        } catch (Exception $e) {
            error_log("Mark sent error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get pending notifications
     */
    public function getPendingNotifications($limit = 100) 
    {
        if (!$this->pdo) {
            return [];
        }
        
        try {
            $sql = "SELECT * FROM notifications WHERE status = 'pending' ORDER BY created_at ASC LIMIT ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(1, $limit, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            error_log("Pending notifications error: " . $e->getMessage());
            return [];
        }
    }
}



