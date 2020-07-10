<?php

/* * *************************************************************************************
 *
 *
 *                  ██████╗██████╗ ███╗   ███╗         Customer
 *                 ██╔════╝██╔══██╗████╗ ████║         Relations
 *                 ██║     ██████╔╝██╔████╔██║         Manager
 *                 ██║     ██╔══██╗██║╚██╔╝██║
 *                 ╚██████╗██║  ██║██║ ╚═╝ ██║         For Magento
 *                  ╚═════╝╚═╝  ╚═╝╚═╝     ╚═╝
 *
 *
 * @author      Piotr Sarzyński <piotr.sa@modulesgarden.com> / < >
 *
 *
 * @link        http://www.docs.modulesgarden.com/CRM_For_WHMCS for documenation
 * @link        http://modulesgarden.com ModulesGarden
 *              Top Quality Custom Software Development
 * @copyright   Copyright (c) ModulesGarden, INBS Group Brand,
 *              All Rights Reserved (http://modulesgarden.com)
 *
 * This software is furnished under a license and mxay be used and copied only  in
 * accordance  with  the  terms  of such  license and with the inclusion of the above
 * copyright notice.  This software  or any other copies thereof may not be provided
 * or otherwise made available to any other person.  No title to and  ownership of
 * the  software is hereby transferred.
 *
 * ************************************************************************************ */

//namespace {
//    // tricky way to get whmcs service
//    if (!class_exists('\Smarty')) {
//        require_once ROOTDIR . '/init.php';
//        require_once ROOTDIR . '/includes/adminfunctions.php';
//    }
//}

namespace Modulesgarden\Crm\Services {

//    use \Modulesgarden\Crm\Integration\Slim\SlimApp;
    use \Modulesgarden\Crm\Models\Magento\EmailTemplates;
    use \Modulesgarden\Crm\Models\Validators\Common;
    use \Modulesgarden\Crm\Models\Mailbox;
    use \Modulesgarden\Crm\Models\EmailLog;
    use \Modulesgarden\Crm\Repositories\PermissionRoles;
    use \Illuminate\Database\Capsule\Manager as DB;
    use \Carbon\Carbon;
    use \Exception;

    /**
     * docs will be later
     *
     * @author Piotr Sarzyński <piotr.sa@modulesgarden.com> < >
     */
    class Mailer
    {

        /**
         * base possibility
         *
         * @var array
         */
        private static $config = array(
                // need obtain from whmcs config
        );

        /**
         * Keep single instance of translation object
         * We do not want to create many many translators object's
         *
         * @var Lang instance
         */
        private static $instance;

        /**
         * Constuct
         */
        private function __construct()
        {
            self::$instance = $this;
            self::getConfig();
        }

        /**
         * Disable clones
         */
        private function __clone()
        {
            
        }

        /**
         * Keep Singletron pattern
         *
         * @return Lang object
         */
        public static function getInstance($mail = null)
        {
            // singletron!
            if (empty(self::$instance)) {
                $class = get_called_class();
                self::$instance = new $class();
                self::getConfig($mail);
            }
            return self::$instance;
        }

        public static function getValidEncodings()
        {
            return array(
                0 => '8bit',
                1 => '7bit',
                2 => 'binary',
                3 => 'base64',
                4 => 'quoted-printable',
            );
        }

        /**
         * Return bolean if admin is logged in
         *
         */
        protected static function getConfig($mail = null)
        {
            if (!$mail) {
                return true;
            }
            $get = DB::table('crm_mail_configuration')->where('email', '=', $mail)->get();
//
//        $SMTPPassword = array_get($get, 'SMTPPassword');
//        r( decrypt($SMTPPassword) );
//
//        ~r($get);
//
//        // get this shit from whmcs database
            self::$config = $get[0];
            return true;
        }

        /**
         * Set up and create PHPMailer
         * by various of configuration
         *
         * @global type $CONFIG
         * @return \PHPMailer
         */
        public function getMailerObject($smtp = false)
        {
            // get access to config
//            global $CONFIG;
            // create mailer
            $mail = new \PHPMailer(true);

            // dont you dare do sth without these turned on
//        $mail->SMTPDebug = true;
            $mail->SMTPDebug = false;
            // grab system email
            // dont change this since server might not sent email from some "random" mail server needs to be configured properly to do that
//            $mail->From = (!empty($CONFIG['Email'])) ? $CONFIG['Email'] : $CONFIG['SystemEmailsFromEmail'];
            // company name from global settings
//            $mail->FromName = $CONFIG['CompanyName'];
            // also charset
            $mail->CharSet = 'utf-8';
            // determinate mail type
            if (!$smtp) {
                $mail->Mailer = "mail";
            } else {
                // or smtp
                $mail->isSMTP();
                $mail->Host = self::$config['SMTPHost'];
                $mail->Port = self::$config['SMTPPort'];
                //$mail->Hostname = $_SERVER['SERVER_NAME'];
                // turn on ssl if so
                if (self::$config['SMTPSSL']) {
                    $mail->SMTPSecure = self::$config['SMTPSSL'];
                }
                // set up smtp params to connect
                if (self::$config['SMTPUsername']) {
                    $mail->SMTPAuth = true;
                    $mail->Username = self::$config['SMTPUsername'];
                    $mail->Password = base64_decode(self::$config['SMTPPassword']);
                }

                // set up proper encoding options
                if (!empty(self::$config['MailEncoding']) and self::$config['MailEncoding'] != 0) {
                    $encodings = self::getValidEncodings();
                    $key = intval(self::$config['MailEncoding']);

                    if (isset($encodings[$key]) and ! empty($encodings[$key])) {
                        $mail->Encoding = $encodings[$key];
                    }
                }
            }

            return $mail;
        }

        /**
         * Bring me global variables
         *
         * @global $CONFIG
         * @global type $customadminpath
         * @return type
         */
//        public static function getGlobalEmailVariables()
//        {
//
//            global $CONFIG;
//            global $customadminpath;
//
//            $vars = array();
//
//            $vars['company_name'] = $CONFIG['CompanyName'];
//            $vars['company_domain'] = $CONFIG['Domain'];
//            $vars['company_logo_url'] = $CONFIG['LogoURL'];
//            $vars['whmcs_url'] = $CONFIG['SystemURL'];
//            $vars['whmcs_link'] = "<a href='{$CONFIG['SystemURL']}'>{$CONFIG['SystemURL']}</a>";
//            $vars['whmcs_admin_url'] = $CONFIG['SystemURL'] . '/' . $customadminpath;
//            $vars['whmcs_admin_link'] = "<a href='{$CONFIG['SystemURL']}/{$customadminpath}'>{$CONFIG['SystemURL']}/{$customadminpath}</a>";
//            $vars['signature'] = $CONFIG['Signature'];
//            $vars['date'] = date("Y-m-d");
//            $vars['time'] = date("Y-m-d H:i:s");
//
//            return $vars;
//        }

        /**
         * Create new Smarty object
         *
         * @global type $templates_compiledir
         * @return \Smarty
         */
//        public static function makeSmartyObject()
//        {
//
//            global $templates_compiledir;
//
//            $smarty = new \Smarty();
//            $smarty->template_dir = ROOTDIR . DS . 'templates' . DS;
//            $smarty->caching = false;
//            // debug
//            $smarty->error_reporting = 0;
//
//            if (self::isItWindows()) {
//                if (!strpos($templates_compiledir, ':\\') && !strpos($templates_compiledir, ':/') && !starts_with($templates_compiledir, '\\')) {
//                    $smarty->compile_dir = ROOTDIR . DS . $templates_compiledir;
//                } else {
//                    $smarty->compile_dir = $templates_compiledir;
//                }
//            } else {
//                if (substr($templates_compiledir, (strlen($templates_compiledir) - 1), 1) == '/') {
//                    $templates_compiledir = substr($templates_compiledir, 0, -1);
//                }
//
//                if (substr($templates_compiledir, 0, 1) == '/') {
//                    $smarty->compile_dir = $templates_compiledir;
//                } else {
//                    $smarty->compile_dir = ROOTDIR . DS . $templates_compiledir;
//                }
//            }
//
//            return $smarty;
//        }

        /**
         * check if we running under windows platform
         *
         * @return bolean
         */
        public static function isItWindows()
        {
            if (in_array(PHP_OS, array('WINNT', 'Windows', 'WIN', 'WIN32'))) {
                return true;
            }

            return false;
        }

        /**
         * Prepare and send email based on template
         *
         * @param type $emailTemplate
         * @param array $recipients
         * @param array $replyTo
         * @param array $cc
         * @param array $smartyVariables
         */
        public function sentEmailFromTemplate($emailTemplate,
                array $recipients = array(), array $replyTo = array(),
                array $cc = array(), array $smartyVariables = array(),
                $addtional = array())
        {
            // just checkint
            if (empty($recipients))
                throw new Exception('Cant sent email without recipment'); {
                
            };
            // get template
            $template = EmailTemplates::findOrFail($emailTemplate);
            // merge inputed variables with global
//            $smartyVariables = array_merge($smartyVariables, self::getGlobalEmailVariables());
//            // get smarty
//            $smarty = self::makeSmartyObject();
//            // assign variables
//            $smarty->assign($smartyVariables);
            // parse by smarty email subject
            $subject = $template->template_subject;
            // parse by smarty email body content
            $message = $template->template_text;


//            global $CONFIG;
//            $globalHeader = self::smartyStringFetch($smarty, $CONFIG['EmailGlobalHeader']);
//            $globalFooter = self::smartyStringFetch($smarty, $CONFIG['EmailGlobalFooter']);
            // finally put this thing together
            $message = html_entity_decode($message);
            $message_text = str_replace("<p>", "", $message);
            $message_text = str_replace("</p>", "\r\n\r\n", $message_text);
            $message_text = str_replace("<br>", "\r\n", $message_text);
            $message_text = str_replace("<br />", "\r\n", $message_text);
            $message_text = strip_tags($message_text);

            // dont forget styles
//            if (!empty($CONFIG['EmailCSS'])) {
//                $message = "<style>\r\n" . $CONFIG['EmailCSS'] . "\r\n</style>\r\n" . $message;
//            }
            // its time to yell for mailer
            $mailer = self::getMailerObject(array_get($addtional, 'smtp', null));

            // set up everything
            $mailer->Subject = $subject;
            $mailer->Body = $message;
            $mailer->AltBody = $message_text;

            // most important part, set up recipments
            foreach ($recipients as $recipment) {
                $mailer->AddAddress($recipment);
            }

            // Set up From Names configured directly in template configuration
//            if (!empty($template->fromname)) {
//                $mailer->FromName = $template->fromname;
//            }
            // Set up From Email pushed to this function, must be valid in server sm
            $fromCustom = array_get($addtional, 'from_email', null);
            if (!empty($fromCustom) && Common::isValidEmail($fromCustom)) {
                $mailer->From = $fromCustom;
                $name = Mailbox::where('email', '=', $fromCustom)->select('name')->take(1)->first();
                $mailer->FromName = is_object($name) ? $name->name : $fromCustom;
            } elseif (!empty($template->template_sender_email) && Common::isValidEmail($template->template_sender_email)) {
                // Set up From Email configured directly in template configuration
                $mailer->From = $template->template_sender_email;
            }

            // reply to parsing
            if (!empty($replyTo) && Common::isValidEmail(array_get($replyTo, 'email', null))) {
                if (array_get($replyTo, 'name', null) != null) {
                    $mailer->AddReplyTo(array_get($replyTo, 'email'), array_get($replyTo, 'name'));
                } else {
                    $mailer->AddReplyTo(array_get($replyTo, 'email'));
                }
            }
//
//
//
//            $copyto = array();
//            // parse default WHMCS feature copy to
//            if ($template->copyto) {
//                $copytoarray = explode(',', $template->copyto);
//                if (is_array($copytoarray)) {
//                    foreach ($copytoarray as $c) {
//                        $mailer->AddCC(trim($c));
//                        $copyto[] = trim($c);
//                    }
//                }
//            }
            // cc parsing
            $copyto = null;
            if (!empty($cc)) {
                foreach ($cc as $c) {
                    if (Common::isValidEmail(array_get($c, 'email', null))) {
                        $copyto[] = trim(array_get($c, 'email', null));
                        if (array_get($c, 'name', null) != null) {
                            $mailer->addCC(trim(array_get($c, 'email', null)), array_get($c, 'name'));
                        } else {
                            $mailer->addCC(trim(array_get($c, 'email', null)));
                        }
                    }
                }
            }

//            // parse attachments
//            $templateAttachments = explode(',', $template->attachments);
//            // attach files
//            $attachments = array();
//            if (!empty($templateAttachments) && is_array($templateAttachments)) {
//                // get template dir
//                $attachmentsDir = SlimApp::getInstance()->whmcs->getDownloadsDir();
//                // check and assign attachments to mailer (they need to exist)
//                foreach ($templateAttachments as $file) {
//                    if (file_exists($attachmentsDir . $file) && !empty($file)) {
//                        $mailer->AddAttachment($attachmentsDir . $file, $file);
//                        $attachments[] = $file;
//                    }
//                }
//            }
//           exit(var_dump(self::$config));
            if ($mailer->Send()) {
                // create email log if
                if ($addtional['resource_id'] || $addtional['followup_id'] || $addtional['reminder_id']) {
                    // łiii add log...
                    $emailLog = new EmailLog(array(
                        'resource_id' => $addtional['resource_id'],
                        'followup_id' => $addtional['followup_id'],
                        'reminder_id' => $addtional['reminder_id'],
                        'date' => Carbon::now(),
                        'subject' => $subject,
                        'message' => $message,
                        'to' => implode(',', $recipients),
                        'cc' => empty($copyto) ? null : implode(',', $copyto),
//                        'attachments' => implode(',', $attachments),
                    ));
                    $emailLog->save();
                }

                return true;
            }
            // what now ?

            return false;
        }

//     * Prepare and send email based on raw informations

        public function sentRawEmail(
        array $recipients = array(), array $replyTo = array(),
                array $cc = array(), $from = null, $subjectRaw = null,
                $contentRaw = null, array $files = array(),
                array $smartyVariables = array(), $addtional = array())
        {
            // just check
            if (empty($recipients))
                throw new Exception('Cant sent emtil without recipment'); {
                
            }

            // merge inputed variables with global
//            $smartyVariables = array_merge($smartyVariables, self::getGlobalEmailVariables());
//            // get smarty
//            $smarty = self::makeSmartyObject();
//            // assign variables
//            $smarty->assign($smartyVariables);
            // parse by smarty email subject
            $subject = $subjectRaw;
            // parse by smarty email body content
            $message = $contentRaw;


//            global $CONFIG;
//            $globalHeader = self::smartyStringFetch($smarty, $CONFIG['EmailGlobalHeader']);
//            $globalFooter = self::smartyStringFetch($smarty, $CONFIG['EmailGlobalFooter']);
            // finally put this thing together
            $message = $message . '<br/>';
            $message_text = str_replace("<p>", "", $message);
            $message_text = str_replace("</p>", "\r\n\r\n", $message_text);
            $message_text = str_replace("<br>", "\r\n", $message_text);
            $message_text = str_replace("<br />", "\r\n", $message_text);
            $message_text = strip_tags($message_text);

            // dont forget styles
//            if (!empty($CONFIG['EmailCSS'])) {
//                $message = "<style>\r\n" . $CONFIG['EmailCSS'] . "\r\n</style>\r\n" . $message;
//            }
            // its time to yell for mailer
            $mailer = self::getMailerObject(array_get($addtional, 'smtp', false));

            // set up everything
            $mailer->Subject = $subject;
            $mailer->Body = $message;
            $mailer->AltBody = $message_text;

            // most important part, set up recipments
            foreach ($recipients as $recipment) {
                $mailer->AddAddress($recipment);
            }

            // Set up From Email configured directly in template configuration
            if (!empty($from) && Common::isValidEmail($from)) {
                $mailer->From = $from;
                $name = Mailbox::where('email', '=', $from)->select('name')->take(1)->first();
                $mailer->FromName = is_object($name) ? $name->name : $from;
            }

            // reply to parsing
            if (!empty($replyTo) && Common::isValidEmail(array_get($replyTo, 'email', null))) {
                if (array_get($replyTo, 'name', null) != null) {
                    $mailer->AddReplyTo(array_get($replyTo, 'email'), array_get($replyTo, 'name'));
                } else {
                    $mailer->AddReplyTo(array_get($replyTo, 'email'));
                }
            }

            $copyto = array();
            // parse default WHMCS feature copy to
//            if ($template->copyto) {
//                $copytoarray = explode(',', $template->copyto);
//                if (is_array($copytoarray)) {
//                    foreach ($copytoarray as $c) {
//                        $mailer->AddCC(trim($c));
//                        $copyto[] = trim($c);
//                    }
//                }
//            }
            // cc parsing
            if (!empty($cc)) {
                foreach ($cc as $c) {
                    if (Common::isValidEmail(array_get($c, 'email', null))) {
                        $copyto[] = trim(array_get($c, 'email', null));
                        if (array_get($c, 'name', null) != null) {
                            $mailer->addCC(trim(array_get($c, 'email', null)), array_get($c, 'name'));
                        } else {
                            $mailer->addCC(trim(array_get($c, 'email', null)));
                        }
                    }
                }
            }

            // attach files
            $attachments = array();
            if (!empty($files) && is_array($files)) {
                foreach ($files as $file) {
                    if (file_exists(array_get($file, 'tmp_name')) && array_get($file, 'name', null) != null) {
                        $mailer->AddAttachment($file['tmp_name'], $file['name']);
                        $attachments[] = $file['name'];
                    }
                }
            }


            if ($mailer->Send()) {
                // create email log if
                if (isset($addtional['resource_id']) || isset($addtional['followup_id']) || isset($addtional['reminder_id'])) {
                    // łiii add log...
                    $emailLog = new EmailLog(array(
                        'resource_id' => $addtional['resource_id'],
                        'followup_id' => $addtional['followup_id'],
                        'reminder_id' => $addtional['reminder_id'],
                        'date' => Carbon::now(),
                        'subject' => $subject,
                        'message' => $message,
                        'to' => implode(',', $recipients),
                        'cc' => implode(',', $copyto),
                        'attachments' => implode(',', $attachments),
                    ));
                    $emailLog->save();
                }
                // if this is provided, we want to create whmcs email log based by this email
//                if ($addtional['client_id']) {
//                    // łiii add log...
//                    ClientEmailLog::insertLog(array(
//                        'userid' => $addtional['client_id'],
//                        'subject' => $subject,
//                        'message' => $message,
//                        'to' => implode(',', $recipients),
//                        'cc' => implode(',', $copyto),
//                        'bcc' => '',
//                    ));
//                }

                return true;
            }
            // what now ?

            return false;
        }

        /**
         * Email to sent based by reminder
         *
         * @param type $emailTemplate
         * @param array $recipients
         * @param array $replyTo
         * @param array $cc
         * @param array $smartyVariables
         */
        public function sentReminderEmailFromTemplate(
        $emailTemplate, array $recipients = array(), array $replyTo,
                array $cc = array(), array $smartyVariables = array(),
                $addtional = array())
        {
            // kind of tricky way, but later will be usefull to separate other logic
            // and maintain email logs in clear way


            $parsed = array(
                'resource_id' => array_get($addtional, 'resource_id', null),
                'followup_id' => array_get($addtional, 'followup_id', null),
                'reminder_id' => array_get($addtional, 'reminder_id', null)
            );

            return $this->sentEmailFromTemplate($emailTemplate, $recipients, $replyTo, $cc, $smartyVariables, $parsed);
        }

        /**
         * Email to sent based by form to direct contact
         *
         * @param type $from
         * @param type $to
         * @param type $subject
         * @param type $content
         * @param array $files
         * @param type $smartyVariables
         * @param type $addtional
         * @return type
         */
        public function sentEmailFromRawData($from, $to, $subject, $content,
                array $files = array(), $smartyVariables, $addtional = array())
        {
            // just for relations
            $parsed = array(
                'resource_id' => array_get($addtional, 'resource_id', null),
                'followup_id' => array_get($addtional, 'followup_id', null),
                'reminder_id' => array_get($addtional, 'reminder_id', null),
                'smtp' => array_get($addtional, 'smtp', null)
            );
            // prepare files to good syntax since we inject here whole $_FILES array

            $parsedFiles = array();
            $tmpFiles = array_get($files, 'files', array());
            if (!empty($tmpFiles)) {
                foreach ($tmpFiles['error'] as $key => $error) {
                    if ($error == UPLOAD_ERR_OK) {
                        $parsedFiles[] = array(
                            'name' => array_get($tmpFiles, "name.{$key}", null),
                            'tmp_name' => array_get($tmpFiles, "tmp_name.{$key}", null),
                        );
                    }
                }
            }

            return $this->sentRawEmail(
                            array($to), // single recipment
                            array(), // dont set reply to, will be used default from we sent
                            array(), // no cc
                            $from, // from email
                            $subject, // subject as plain text
                            $content, // subject as plain text
                            $parsedFiles, // $files if any
                            $smartyVariables, // passed variables to use
                            $parsed                            // additional params, contain relations to create email log entry
            );
        }

        public function sentRawEmailFromTemplate($from, $to, $template,
                $smartyVariables, $addtional = array())
        {
            // just for relations
            $parsed = array(
                'resource_id' => array_get($addtional, 'resource_id', null),
                'followup_id' => array_get($addtional, 'followup_id', null),
                'reminder_id' => array_get($addtional, 'reminder_id', null),
                'from_email' => $from,
                'smtp' => array_get($addtional, 'smtp', null)
            );


            return $this->sentEmailFromTemplate(
                            $template, // choosed template
                            array($to), // single recipment
                            array(), // dont set reply to, will be used default from we sent
                            array(), // no cc
                            $smartyVariables, // passed variables to use
                            $parsed                            // additional params, contain relations to create email log entry
            );
        }

        public static function registerResources($smarty)
        {
            $smarty->register_resource("fromstr", array(
                array(get_called_class(), 'fromstr_template'),
                array(get_called_class(), 'fromstr_timestamp'),
                array(get_called_class(), 'fromstr_secure'),
                array(get_called_class(), 'fromstr_trusted'),
                    )
            );
        }

        public static function fromstr_template($tpl_name, &$tpl_source,
                &$smarty_obj)
        {
            $tpl_source = $smarty_obj->get_template_vars($tpl_name);
            return empty($tpl_source) ? false : true;
        }

        public static function fromstr_timestamp($tpl_name, &$tpl_timestamp,
                &$smarty_obj)
        {
            $tpl_timestamp = time();
            return true;
        }

        public static function fromstr_secure($tpl_name, &$smarty_obj)
        {
            return true;
        }

        public static function fromstr_trusted($tpl_name, &$smarty_obj)
        {
            
        }

        /*
         * not needed in smarty v3,
         *
         * this fix is awesome... lol no need to create temporary file :)
         */

//        public static function smartyStringFetch(&$smarty, $content)
//        {
//            // in order to be able in request generate other body etc
//            $smarty->compile_id = md5($content . time());
//
//            try {
//                if (defined("Smarty::SMARTY_VERSION")) {
//                    $ver = $smarty::SMARTY_VERSION;
//                    $versioncheck = strpos($ver, '-3.') !== false;
//                } else {
//                    $versioncheck = false;
//                }
//            } catch (Exception $ex) {
//                $versioncheck = false;
//            }
//
////        if(SlimApp::getInstance()->whmcs->isVersion6())
//            // tricky way to determinate if this is smarty v3
//            // since in smarty v3 this variable is null
//            // there could be better approach but for now its this trick
//            if ($versioncheck) {
//                $result = $smarty->fetch("string:" . $content);
//            } else {
//                // register the resource name "string"
//                self::registerResources($smarty);
//
//                $uid = uniqid(); //caching prevent
//                $smarty->assign($uid, $content);
//                $result = $smarty->fetch('fromstr:' . $uid);
//
//                $smarty->unregister_resource('fromstr');
//            }
//
//            return $result;
//        }
    }

}