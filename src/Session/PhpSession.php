<?php
/**
 * Project: citybike
 *
 * File: PhpSession.php
 * Created by Cristiano Cucco.
 * E-mail: cristiano.cucco@gmail.com
 * Date: 17/05/2016
 * Time: 16:20
 */

namespace Gr77\Session;


class PhpSession implements Session
{
    /**
     * Bot token used for session namespace
     * @var string
     */
    private $token;

    private $session_id = null;

    /**
     * PhpSession constructor.
     * @param int $session_id
     * @param string $token
     */
    public function __construct($session_id, $token)
    {
        if (!isset($_SESSION)) {
            $session_id = (string) $session_id;
            if (!preg_match('/[A-Za-z0-9-,]/', $session_id)) {
                throw new \BadMethodCallException("Invalid session ID: ".$session_id, 500);
            }
            $this->session_id = $session_id;
            session_id($session_id);
            session_start();
            if (!isset($_SESSION[$token])) {
                $_SESSION[$token] = array();
            }
            $this->token = $token;
        } else {
            throw new \BadMethodCallException("Session already initiated", 500);
        }
    }

    /**
     * @return mixed
     */
    public function getSessionId()
    {
        return $this->session_id;
    }

    /**
     * @param $var
     * @param $value
     * @return mixed
     */
    public function set($var, $value)
    {
        $_SESSION[$this->token][$var] = $value;
        return $this;
    }

    /**
     * @param $var
     * @return mixed
     */
    public function get($var, $default = null)
    {
        if (isset($_SESSION[$this->token][$var])) {
            return $_SESSION[$this->token][$var];
        }
        return $default;
    }

    /**
     * @param string $var
     * @return mixed
     */
    public function delete($var)
    {
        unset($_SESSION[$this->token][$var]);
    }


}