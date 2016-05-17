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

    /**
     * PhpSession constructor.
     * @param int $session_id
     * @param string $token
     */
    public function __construct($session_id, $token)
    {
        if (!isset($_SESSION)) {
            session_id($session_id.$token);
            session_start();
            $_SESSION = array();
            $this->token = $token;
        } else {
            throw new \BadMethodCallException("Session already initiated", 500);
        }
    }

    /**
     * @param $var
     * @param $value
     * @return mixed
     */
    public function set($var, $value)
    {
        $_SESSION[$var] = $value;
        return $this;
    }

    /**
     * @param $var
     * @return mixed
     */
    public function get($var, $default = null)
    {
        if (isset($_SESSION[$var])) {
            return $_SESSION[$var];
        }
        return $default;
    }

    /**
     * @param string $var
     * @return mixed
     */
    public function delete($var)
    {
        unset($_SESSION[$var]);
    }

}