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

namespace Quantum\Libraries\Sessions;

use Quantum\Routes\RouteController;
use Quantum\Libraries\Database\Database;

/**
 * DB Sessions class
 * 
 * @package Quantum
 * @subpackage Libraries.Sessions
 * @category Libraries
 */
class DbSessions extends DbSessionHandler implements SessionInterface {

    /**
     * DB Session table
     * @var string 
     */
    public $sessions_table = 'sessions';

    /**
     * Class constructor
     * 
     * @param int $timeout_duration
     * @return void
     */
    public function __construct($timeout_duration = 1800) {

        session_set_save_handler(
                array($this, "_open"), 
                array($this, "_close"), 
                array($this, "_read"), 
                array($this, "_write"), 
                array($this, "_destroy"), 
                array($this, "_gc")
        );

        new Database(RouteController::$currentRoute);

        if (!session_id()) {
            @session_start();
        }

        if (isset($_SESSION['LAST_ACTIVITY']) && time() - $_SESSION['LAST_ACTIVITY'] > $timeout_duration) {
            @session_unset();
            @session_destroy();
        }

        $_SESSION['LAST_ACTIVITY'] = time();
    }

    /**
     * Gets value from session by key
     * 
     * @param type $key
     * @return type
     */
    public function get($key) {
        return $this->has($key) ? $_SESSION[$key] : NULL;
    }

    /*
     * Gets whole session data
     */
    public function all() {
        return $_SESSION;
    }

    /**
     * Check if session contains a key
     * 
     * @param string $key
     * @return bool
     */
    public function has($key) {
        return isset($_SESSION[$key]) ? true : false;
    }

    /**
     * Sets session value with given key
     * 
     * @param string $key
     * @param mixed $value
     */
    public function set($key, $value) {
        $_SESSION[$key] = $value;
    }

    /**
     * Gets flesh values by given key
     * 
     * @param string $key
     * @return mixed
     */
    public function getFlesh($key) {
        if ($this->has($key)) {
            $fleshData = $_SESSION[$key];
            $this->delete($key);
            return $fleshData;
        }
    }

    /**
     * Sets flesh values with given key
     * 
     * @param string $key
     * @param mixed $value
     */
    public function setFlesh($key, $value) {
        $this->set($key, $value);
    }

    /**
     * Delete data with given key from session
     * 
     * @param string $key
     */
    public function delete($key) {
        if ($this->has($key)) {
            unset($_SESSION[$key]);
        }
    }

    /**
     * Deletes whole session data
     */
    public function flush() {
        @session_destroy();
    }

}