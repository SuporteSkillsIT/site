<?php

namespace Modulesgarden\Crm\mgLibs\exceptions;

use Modulesgarden\Crm as main;

/**
 * Base Module Exception
 * 
 * Use as base for other exceptions
 *
 * @author Michal Czech <michael@modulesgarden.com>
 * @codeCoverageIgnore
 */
class base extends \Exception {

    private $_token;

    public function __construct($message, $code, $previous = null) {
        parent::__construct($message, $code, $previous);
        $this->_token = md5(microtime());
    }

    public function getToken() {
        return $this->_token;
    }

}
