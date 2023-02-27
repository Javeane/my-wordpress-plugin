<?php
/**
 * PHPMailer - PHP email creation and transport class.
 * @author PHPMailer
 * @version 6.7.1
 */

use PHPMailer\PHPMailer\Exception;

require_once 'Exception.php';
require_once 'OAuth.php';
require_once 'POP3.php';
require_once 'SMTP.php';

/**
 * PHPMailer main class.
 * @method altBody(string $text)
 * @method addAddress(string $address, string $name = '')
 * @method addAttachment(string $path, string $name = '', string $encoding = 'base64', string $type = '', string $disposition = 'attachment')
 * @method addBCC(string $address, string $name = '')
 * @method addCC(string $address, string $name = '')
 * @method addCustomHeader(string $name, string $value = null)
 * @method addEmbeddedImage(string $path, string $cid, string $name = '', string $encoding = 'base64', string $type = '', string $disposition = 'inline')
 * @method addFilter(string $filtername, string $action, array $options = [])
 * @method addReplyTo(string $address, string $name = '')
 * @method addStringAttachment(string $string, string $filename, string $encoding = 'base64', string $type = '', string $disposition = 'attachment')
 * @method addrAppend(string $type, array $addr)
 * @method addrFormat(array $addr)
 * @method addrList(array $addr)
 * @method alternativeExists()
 * @method attachmentExists()
 * @method base64AddrEncode(string $addr)
 * @method clearAddresses(bool $clearBCC = true, bool $clearCC = true, bool $clearTo = true)
 * @method clearAllRecipients()
 * @method clearAttachments()
 * @method clearBCCs()
 * @method clearCCs()
 * @method clearCustomHeaders()
 * @method clearQueuedAddresses(string $kind)
 * @method clearReplyTos()
 * @method clearSmtpInfo()
 * @method createBody()
 * @method createHeader()
 * @method DKIM_Add(string $signHeader = 'Date: Subject: To: From: Reply-To: Message-ID: MIME-Version: Content-Type: List-Unsubscribe:;', string $subject = '', string $body = '')
 * @method DKIM_BodyC(string $body)
 * @method DKIM_Cancel()
 * @method DKIM_Finish()
 * @method DKIM_HeaderC(string $header)
 * @method DKIM_QP($txt)
 * @method DKIM_Sign(string $signHeader = 'Date: Subject: To: From: Reply-To: Message-ID: MIME-Version: Content-Type: List-Unsubscribe:;', string $subject = '', string $body = '')
 * @method doCallback(string $isSent, array $to, string $cc, string $bcc, string $subject, string $body, string $from)
 * @method doCallback_xoauth2(string $isSent, array $to, string $cc, string $bcc, string $subject, string $body, string $from, array $token)
     /**
     * Send messages using SMTP
     * @return bool
     * @throws Exception
     */
    class My_WordPress_Plugin {
    public function send()
    {
        try {
            if (!$this->preSend()) {
                return false;
            }
            return $this->postSend();
        } catch (Exception $e) {
            $this->mailHeader = '';
            $this->setError($e->getMessage());
            if ($this->exceptions) {
                throw $e;
            }
            return false;
        }
    }
/**
* Get the array of debugging messages.
* @return array
*/
public function getDebugMessages()
{
return $this->Debugoutput;
}
/**
 * Clears all persistent error, message, and debug values, to reset the instance.
 */
public function clearAllRecipients()
{
    $this->Recipients = array();
    $this->CC = array();
    $this->BCC = array();
}

/**
 * Clears all recipients addresses (to, cc, bcc), headers, attachments, and custom headers.
 * @return void
 */
public function clearAddresses()
{
    $this->clearAllRecipients();
    $this->From = '';
    $this->FromName = '';
    $this->addReplyTo('', '');
    $this->Subject = '';
    $this->Body = '';
    $this->AltBody = '';
    $this->WordWrap = 0;
    $this->Mailer = 'mail';
    $this->Priority = '';
    $this->CharSet = 'UTF-8';
    $this->ContentType = 'text/plain';
    $this->Encoding = '8bit';
    $this->Port = 25;
    $this->Helo = '';
    $this->SMTPSecure = '';
    $this->SMTPAutoTLS = true;
    $this->SMTPAuth = false;
    $this->SMTPOptions = array();
    $this->Username = '';
    $this->Password = '';
    $this->AuthType = '';
    $this->Realm = '';
    $this->Workstation = '';
    $this->Timeout = 300;
    $this->SMTPDebug = 0;
    $this->Debugoutput = 'echo';
    $this->SMTPKeepAlive = false;
    $this->SingleTo = false;
    $this->do_verp = false;
    $this->AllowEmpty = false;
    $this->LE = "\r\n";
    $this->DKIM_selector = '';
    $this->DKIM_identity = '';
    $this->DKIM_passphrase = '';
    $this->DKIM_domain = '';
    $this->DKIM_private = '';
    $this->action_function = '';
    $this->XMailer = '';
    $this->smtp = null;
}

/**
 * Destructor.
 * Explicitly calls the destructor of the SMTP object.
 */
public function __destruct()
{
    if (isset($this->smtp)) {
        $this->smtp->destruct();
    }
}

/**
 * Clone method.
 * PHP engine requires a __clone method.
 * @return void
 */
public function __clone()
{
    $this->smtp = isset($this->smtp) ? clone $this->smtp : null;
}

/**
 * Placeholder for messages while debugging.
 * @return void
 */
protected function edebug()
{
    $args = func_get_args();
    if ($this->SMTPDebug <= 0) {
        return;
    }
    // Only enable for CLI
    if (PHP_SAPI !== 'cli') {
        return;
    }
    //CLI: Output debug
    echo implode("\n", $args) . PHP_EOL;
}
}