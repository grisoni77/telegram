<?php
/**
 * Project: citybike
 *
 * File: NullSession.php
 * Created by Cristiano Cucco.
 * E-mail: cristiano.cucco@gmail.com
 * Date: 17/05/2016
 * Time: 16:42
 */

namespace Gr77\Session;


class NullSession implements Session
{
    /**
     * NullSession constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param string $var
     * @param mixed $value
     * @return mixed
     */
    public function set($var, $value)
    {
        // TODO: Implement set() method.
    }

    /**
     * @param string $var
     * @param null $default
     * @return mixed
     */
    public function get($var, $default = null)
    {
        return null;
    }

    /**
     * @param string $var
     * @return mixed
     */
    public function delete($var)
    {
        // TODO: Implement set() method.
    }

}