<?php
/**
 * SMTP server configuration parameters setting
 */

class SMTP {
    
    /**
     * SMTP server host name
     *
     * @var string
     */
    public $Host = 'smtp.example.com';
    
    /**
     * SMTP server port
     *
     * @var int
     */
    public $Port = 587;
    
    /**
     * SMTP connection timeout
     *
     * @var int
     */
    public $Timeout = 30;
    
    /**
     * Whether to use SMTP authentication
     *
     * @var bool
     */
    public $SMTPAuth = true;
    
    /**
     * SMTP username
     *
     * @var string
     */
    public $Username = 'username';
    
    /**
     * SMTP password
     *
     * @var string
     */
    public $Password = 'password';
    
    /**
     * Security mode
     *
     * @var string 'ssl', 'tls' or ''
     */
    public $SMTPSecure = 'tls';
    
    /**
     * Enable/disable SMTP debugging
     *
     * @var bool
     */
    public $SMTPDebug = false;
    
    //...
    
}
/**
 * Connect to an SMTP server.
 *
 * @access public
 * @param string $host SMTP server IP or host name
 * @param int $port The port number to connect to
 * @param int $timeout How long to wait for a connection to the server
 * @param array $options An array of options for the connection context, see http://php.net/manual/en/context.ssl.php
 * @throws phpmailerException
 * @return bool True if the connection was successful, false otherwise
 */
public function connect($host, $port = null, $timeout = 30, $options = array()) {
    try {
        // Try to connect to the SMTP server.
        if ($this->smtp->connect(($this->SMTPSecure ? 'ssl://' : '') . $host, $port, $timeout, $options)) {
            // Check for a response from the server.
            $this->smtp->get_lines();
            // Perform SMTP handshake.
            $this->smtp->hello($this->serverHostname());
            // If we're using SMTP authentication, attempt to authenticate.
            if ($this->SMTPAuth) {
                if (!$this->smtp->authenticate($this->Username, $this->Password)) {
                    throw new phpmailerException('SMTP Error: Could not authenticate.');
                }
            }
            return true;
        }
    } catch (Exception $e) {
        // SMTP connection failed.
        throw new phpmailerException('SMTP connection failed: ' . $e->getMessage());
    }
    // If we get here, the connection was not successful.
    return false;
}

/**
 * Close the active SMTP session if one exists.
 *
 * @access public
 * @return void
 */
public function disconnect() {
    if ($this->smtp !== null) {
        $this->smtp->quit();
        $this->smtp->close();
    }
}
/**
 * SMTP 身份验证的实现
 *
 * @access public
 * @return bool
 * @throws Exception
 */
public function authenticate() {
    if (!$this->smtpConnect()) {
        throw new Exception('SMTP Error: Could not connect to SMTP server.');
    }
    try {
        $this->smtpSend('EHLO ' . $this->serverHostname);
        if ($this->smtpAuth) {
            if (!$this->smtpSend('AUTH LOGIN', '334')) {
                throw new Exception('SMTP Error: Could not authenticate.');
            }
            if (!$this->smtpSend(base64_encode($this->username), '334')) {
                throw new Exception('SMTP Error: Username not accepted from server.');
            }
            if (!$this->smtpSend(base64_encode($this->password), '235')) {
                throw new Exception('SMTP Error: Password not accepted from server.');
            }
        }
    } catch (Exception $e) {
        $this->smtpClose();
        throw $e;
    }
    return true;
}
<?php

/**
 * PHPMailer SMTP发送邮件实现
 *
 * @version 6.5.0
 * 
 */

class SMTP
{
    // ... 其他代码

    /**
     * 发送邮件
     * @param string $from 发件人邮箱
     * @param string $to 收件人邮箱，多个地址使用逗号分隔
     * @param string $subject 邮件主题
     * @param string $body 邮件正文
     * @param string $altBody 可选，当邮件客户端不支持HTML邮件时显示的普通文本
     * @param array $customHeaders 可选，自定义邮件头部信息，如Reply-To
     * @return bool 成功返回 true，失败返回 false
     * @throws Exception
     */
    public function send($from, $to, $subject, $body, $altBody = '', $customHeaders = array())
    {
        if (!$this->connected()) {
            throw new Exception('SMTP连接未建立');
        }

        // 邮件发送前处理，例如身份验证
        if (!$this->smtpSend($this->lastMessage())) {
            throw new Exception('SMTP发送前处理失败');
        }

        $header = $this->headerLine('Date', $this->rfcDate());
        $header .= $this->headerLine('Return-Path', $this->addressFormat($this->Sender));
        foreach ($to as $t) {
            $header .= $this->headerLine('To', $this->addressFormat($t));
        }
        foreach ($this->cc as $cc) {
            $header .= $this->headerLine('Cc', $this->addressFormat($cc));
        }
        foreach ($this->bcc as $bcc) {
            $header .= $this->headerLine('Bcc', $this->addressFormat($bcc));
        }
        $header .= $this->headerLine('From', $this->addressFormat($from));
        $header .= $this->headerLine('Subject', $subject);

        foreach ($customHeaders as $headerName => $headerValue) {
            $header .= $this->headerLine($headerName, $headerValue);
        }

        // 将邮件头和邮件体拼接到一起
        $body = $header . $this->LE . $body;

        if ($altBody !== '') {
            $altBody = $this->textLine('Content-Type: text/plain; charset=UTF-8') . $this->LE . $this->LE;
            $altBody .= $this->normalizeBreaks($altBody);
        }

        // 将邮件正文和可选的普通文本拼接到一起
        $body .= $altBody;

        $recipients = array_merge($to, $this->cc, $this->bcc);

        if (!$this->smtpSend($this->headerLine('To', implode(',', $recipients)) . $this->LE . $body)) {
            throw new Exception('SMTP发送失败');
        }

        // 断开 SMTP 连接
        $this->smtpClose();

        // 将邮件正文和可选的普通文本拼接到一起
        $body .= $altBody;

        $recipients = array_merge($to, $this->cc, $this->bcc);

        if (!$this->smtpSend($this->headerLine('To', implode(',', $recipients)) . $this->LE . $body)) {
            throw new Exception('SMTP发送失败');
        }

        // 断开 SMTP 连接
        $this->smtpClose();

        // 重置所有属性为初始值，以便下一次使用
        $this->clearAllRecipients();
        $this->clearAttachments();
        $this->clearCustomHeaders();
        $this->clearReplyTos();
    }

    /**
     * 向 SMTP 服务器发送邮件内容
     *
     * @param string $header 邮件头和正文
     * @return bool
     */
    public function smtpSend($header)
    {
        // 检查是否已经建立连接
        if (!$this->smtpConnect()) {
            throw new Exception('SMTP连接失败');
        }

        // 发送 EHLO 命令
        if (!$this->smtpHello()) {
            throw new Exception('EHLO命令发送失败');
        }

        // 发送身份验证信息
        if ($this->smtpAuth($this->Username, $this->Password) === false) {
            throw new Exception('SMTP身份验证失败');
        }

        // 发送 MAIL FROM 命令
        if (!$this->smtpMail($this->Sender === '' ? $this->From : $this->Sender)) {
            throw new Exception('MAIL FROM命令发送失败');
        }

        // 发送 RCPT TO 命令
        foreach ($this->to as $to) {
            if (!$this->smtpRecipient($to)) {
                throw new Exception('RCPT TO命令发送失败');
            }
        }

        foreach ($this->cc as $cc) {
            if (!$this->smtpRecipient($cc)) {
                throw new Exception('RCPT TO命令发送失败');
            }
        }

        foreach ($this->bcc as $bcc) {
            if (!$this->smtpRecipient($bcc)) {
                throw new Exception('RCPT TO命令发送失败');
            }
        }

        // 发送 DATA 命令
        if (!$this->smtpData($header)) {
            throw new Exception('DATA命令发送失败');
        }

        // 发送完成，返回 true
        return true;
    }
}
/**
 * 异常处理
 *
 * @param Exception $exception 异常对象
 *
 * @throws Exception 抛出异常
 */
protected function exceptionHandler($exception)
{
    if (!$this->exceptionsEnabled) {
        return;
    }
    throw $exception;
}
