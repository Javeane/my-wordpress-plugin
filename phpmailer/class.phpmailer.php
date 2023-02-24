<?php
/**
 * PHPMailer - PHP email creation and transport class.
 * PHP Version 8.0
 *
 * @package PHPMailer
 * @author PHPMailer Team
 * @copyright 2004-2019 PHPMailer
 * @license https://github.com/PHPMailer/PHPMailer/blob/master/LICENSE SMTP: The SMTP transport class and SMTP support class are licensed under the GNU Lesser General Public License as published by the Free Software Foundation; either version 2.1 of the License, or (at your option) any later version.
 * @link https://github.com/PHPMailer/PHPMailer/ The PHPMailer GitHub project
 * @version 6.1.7
 */

namespace PHPMailer\PHPMailer;

use Exception;

/**
 * PHPMailer - PHP email creation and transport class.
 * @package PHPMailer
 * @author PHPMailer Team
 */
class PHPMailer
{
    // ...

    /**
     * SMTPSecure: Sets the encryption system to be used on the SMTP server.
     * @var string
     */
    public $SMTPSecure = '';

    /**
     * Port: The default SMTP server port.
     * @var integer
     */
    public $Port = 25;

    /**
     * CharSet: The character set of the message.
     * @var string
     */
    public $CharSet = 'utf-8';

    /**
     * Encoding: The encoding to use for the message.
     * Options for this are '', '8bit', '7bit', 'binary', 'base64', and 'quoted-printable'.
     * @var string
     */
    public $Encoding = '8bit';

    /**
     * SMTPDebug: The level of debug output to show.
     * Options:
     *   - `SMTP::DEBUG_OFF` No debug output.
     *   - `SMTP::DEBUG_CLIENT` Client commands.
     *   - `SMTP::DEBUG_SERVER` Client commands and server responses.
     *   - `SMTP::DEBUG_CONNECTION` As `DEBUG_SERVER` plus connection status messages.
     *   - `SMTP::DEBUG_LOWLEVEL` Low-level data output.
     * @var integer
     */
    public $SMTPDebug = SMTP::DEBUG_OFF;

    /**
     * Debugoutput: The function/method to use for debugging output.
     * @var string
     */
    public $Debugoutput = 'echo';

    /**
     * SMTPKeepAlive: Enable or disable SMTP connection reuse.
     * @var boolean
     */
    public $SMTPKeepAlive = false;

    /**
     * SingleTo: Whether to generate message headers for Bcc-only messages.
     * @var boolean
     */
    public $SingleTo = false;

    // ...

    /**
     * Set the language for error messages.
     * Returns false if it cannot load the language file. The default language is English.
     * @param string $langcode ISO 639-1 2-character language code (e.g. French is "fr")
     * @param string $lang_path Path to the language file directory, with trailing separator
     * @return bool
     */
    public function setLanguage($langcode = 'en', $lang_path = '')
    {
        $PHPMAILER_LANG = array(
            'authenticate'         => 'SMTP Error: Could not authenticate.',
            'connect_host'         => 'SMTP Error: Could not connect to SMTP host.',
            'data_not_accepted'    => 'SMTP Error: Data not accepted.',
            'empty_message'       



