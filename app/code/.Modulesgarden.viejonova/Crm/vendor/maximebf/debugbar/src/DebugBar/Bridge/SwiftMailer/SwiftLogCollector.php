<?php
/*
 * This file is part of the DebugBar package.
 *
 * (c) 2013 Maxime Bouroumeau-Fuseau
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DebugBar\Bridge\SwiftMailer;

use DebugBar\DataCollector\MessagesCollector;

use Swift_Plugins_LoggerPlugin;

/**
 * Collects log messages
 *
 * http://swiftmailer.org/
 */
class SwiftLogCollector extends MessagesCollector
{
    public function __construct( $mailer)
    {
        $mailer->registerPlugin(new Swift_Plugins_LoggerPlugin($this));
    }

    public function add($entry)
    {
        $this->addMessage($entry);
    }

    public function dump()
    {
        return implode(PHP_EOL, $this->_log);
    }

    public function getName()
    {
        return 'swiftmailer_logs';
    }
}
