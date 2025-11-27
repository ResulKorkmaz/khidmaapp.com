<?php
/**
 * KhidmaApp.com - Email Service
 * 
 * E-posta gönderimi için profesyonel servis
 * Hostinger SMTP veya PHP mail() ile çalışır
 */

class EmailService
{
    private string $fromEmail;
    private string $fromName;
    private bool $useSmtp;
    private array $smtpConfig;
    
    public function __construct()
    {
        // Config'den e-posta ayarlarını al
        $this->fromEmail = defined('MAIL_FROM_EMAIL') ? MAIL_FROM_EMAIL : 'noreply@khidmaapp.com';
        $this->fromName = defined('MAIL_FROM_NAME') ? MAIL_FROM_NAME : 'KhidmaApp';
        $this->useSmtp = defined('MAIL_USE_SMTP') ? MAIL_USE_SMTP : false;
        
        $this->smtpConfig = [
            'host' => defined('SMTP_HOST') ? SMTP_HOST : 'smtp.hostinger.com',
            'port' => defined('SMTP_PORT') ? SMTP_PORT : 465,
            'username' => defined('SMTP_USERNAME') ? SMTP_USERNAME : '',
            'password' => defined('SMTP_PASSWORD') ? SMTP_PASSWORD : '',
            'encryption' => defined('SMTP_ENCRYPTION') ? SMTP_ENCRYPTION : 'ssl',
        ];
    }
    
    /**
     * E-posta gönder
     */
    public function send(string $to, string $subject, string $htmlBody, ?string $textBody = null): bool
    {
        if ($this->useSmtp && !empty($this->smtpConfig['username'])) {
            return $this->sendViaSMTP($to, $subject, $htmlBody, $textBody);
        }
        
        return $this->sendViaMail($to, $subject, $htmlBody, $textBody);
    }
    
    /**
     * PHP mail() ile gönder
     */
    private function sendViaMail(string $to, string $subject, string $htmlBody, ?string $textBody = null): bool
    {
        $boundary = md5(time());
        
        $headers = [
            'From: ' . $this->fromName . ' <' . $this->fromEmail . '>',
            'Reply-To: ' . $this->fromEmail,
            'MIME-Version: 1.0',
            'Content-Type: multipart/alternative; boundary="' . $boundary . '"',
            'X-Mailer: KhidmaApp/1.0',
        ];
        
        $textBody = $textBody ?? strip_tags($htmlBody);
        
        $message = "--{$boundary}\r\n";
        $message .= "Content-Type: text/plain; charset=UTF-8\r\n";
        $message .= "Content-Transfer-Encoding: base64\r\n\r\n";
        $message .= chunk_split(base64_encode($textBody)) . "\r\n";
        
        $message .= "--{$boundary}\r\n";
        $message .= "Content-Type: text/html; charset=UTF-8\r\n";
        $message .= "Content-Transfer-Encoding: base64\r\n\r\n";
        $message .= chunk_split(base64_encode($htmlBody)) . "\r\n";
        
        $message .= "--{$boundary}--";
        
        $result = @mail($to, '=?UTF-8?B?' . base64_encode($subject) . '?=', $message, implode("\r\n", $headers));
        
        if (!$result) {
            error_log("Mail send failed to: {$to}");
        }
        
        return $result;
    }
    
    /**
     * SMTP ile gönder (PHPMailer olmadan basit SMTP)
     */
    private function sendViaSMTP(string $to, string $subject, string $htmlBody, ?string $textBody = null): bool
    {
        try {
            $socket = @fsockopen(
                ($this->smtpConfig['encryption'] === 'ssl' ? 'ssl://' : '') . $this->smtpConfig['host'],
                $this->smtpConfig['port'],
                $errno,
                $errstr,
                30
            );
            
            if (!$socket) {
                error_log("SMTP connection failed: {$errstr} ({$errno})");
                return $this->sendViaMail($to, $subject, $htmlBody, $textBody);
            }
            
            $this->smtpRead($socket);
            
            // EHLO
            $this->smtpCommand($socket, "EHLO " . gethostname());
            
            // TLS for port 587
            if ($this->smtpConfig['port'] == 587 && $this->smtpConfig['encryption'] === 'tls') {
                $this->smtpCommand($socket, "STARTTLS");
                stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
                $this->smtpCommand($socket, "EHLO " . gethostname());
            }
            
            // AUTH LOGIN
            $this->smtpCommand($socket, "AUTH LOGIN");
            $this->smtpCommand($socket, base64_encode($this->smtpConfig['username']));
            $this->smtpCommand($socket, base64_encode($this->smtpConfig['password']));
            
            // MAIL FROM
            $this->smtpCommand($socket, "MAIL FROM:<{$this->fromEmail}>");
            
            // RCPT TO
            $this->smtpCommand($socket, "RCPT TO:<{$to}>");
            
            // DATA
            $this->smtpCommand($socket, "DATA");
            
            // Message
            $boundary = md5(time());
            $textBody = $textBody ?? strip_tags($htmlBody);
            
            $message = "From: {$this->fromName} <{$this->fromEmail}>\r\n";
            $message .= "To: {$to}\r\n";
            $message .= "Subject: =?UTF-8?B?" . base64_encode($subject) . "?=\r\n";
            $message .= "MIME-Version: 1.0\r\n";
            $message .= "Content-Type: multipart/alternative; boundary=\"{$boundary}\"\r\n\r\n";
            
            $message .= "--{$boundary}\r\n";
            $message .= "Content-Type: text/plain; charset=UTF-8\r\n";
            $message .= "Content-Transfer-Encoding: base64\r\n\r\n";
            $message .= chunk_split(base64_encode($textBody)) . "\r\n";
            
            $message .= "--{$boundary}\r\n";
            $message .= "Content-Type: text/html; charset=UTF-8\r\n";
            $message .= "Content-Transfer-Encoding: base64\r\n\r\n";
            $message .= chunk_split(base64_encode($htmlBody)) . "\r\n";
            
            $message .= "--{$boundary}--\r\n";
            $message .= ".";
            
            $this->smtpCommand($socket, $message);
            
            // QUIT
            $this->smtpCommand($socket, "QUIT");
            
            fclose($socket);
            return true;
            
        } catch (Exception $e) {
            error_log("SMTP error: " . $e->getMessage());
            return $this->sendViaMail($to, $subject, $htmlBody, $textBody);
        }
    }
    
    private function smtpCommand($socket, string $command): string
    {
        fwrite($socket, $command . "\r\n");
        return $this->smtpRead($socket);
    }
    
    private function smtpRead($socket): string
    {
        $response = '';
        while ($line = fgets($socket, 515)) {
            $response .= $line;
            if (substr($line, 3, 1) == ' ') break;
        }
        return $response;
    }
}

