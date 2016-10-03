<?php
/**
 * Project: citybike
 *
 * File: Session.php
 * Created by Cristiano Cucco.
 * E-mail: cristiano.cucco@gmail.com
 * Date: 17/05/2016
 * Time: 16:06
 */

namespace Gr77\Session;


interface Session
{
//    /**
//     * @param int $session_id
//     * @param array $config
//     * @var string $config["token"] bot token used for session namespace
//     * @return bool
//     */
//    public function init($session_id, $config = array());

    /**
     * @return mixed
     */
    public function getSessionId();

    /**
     * @param string $var
     * @param mixed $value
     * @return mixed
     */
    public function set($var, $value);

    /**
     * @param string $var
     * @param mixed $var
     * @return mixed
     */
    public function get($var, $default = null);

    /**
     * @param string $var
     * @return mixed
     */
    public function delete($var);
}