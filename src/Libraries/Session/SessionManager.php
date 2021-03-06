<?php

/**
 * Quantum PHP Framework
 * 
 * An open source software development framework for PHP
 * 
 * @package Quantum
 * @author Arman Ag. <arman.ag@softberg.org>
 * @copyright Copyright (c) 2018 Softberg LLC (https://softberg.org)
 * @link http://quantum.softberg.org/
 * @since 1.0.0
 */

namespace Quantum\Libraries\Session;

use Quantum\Exceptions\ExceptionMessages;

/**
 * Session Manager class
 * 
 * @package Quantum
 * @subpackage Libraries.Session
 * @category Libraries
 */
class SessionManager {

    /**
     * Session driver
     * 
     * @var string 
     */
    private static $sessionDriver = NULL;
    
    /**
     * Session handler
     * 
     * @var object 
     */
    private static $sessionHandler = NULL;

    /**
     * Get session handler
     * 
     * @return object
     * @throws \Exception When session handler is not correctly configured
     */
    public static function getSessionHandler() {
        $sessionDriver = get_config('session_driver');
        
        self::$sessionDriver = $sessionDriver ? $sessionDriver : 'native';

        if (self::$sessionDriver) {
            switch (self::$sessionDriver) {
                case 'native':
                    self::$sessionHandler = new Session(get_config('session_timeout'));
                    break;
                case 'database':
                    self::$sessionHandler = new DbSession(get_config('session_timeout'));
                    break;
            }
        }

        if (self::$sessionHandler) {
            return self::$sessionHandler;
        } else {
            throw new \Exception(ExceptionMessages::MISCONFIGURED_SESSION_HANDLER);
        }
    }
    
}
