<?php
/**
 * Created by PhpStorm.
 * User: cris77
 * Date: 23/01/2017
 * Time: 15:14
 */

namespace Gr77\Session;


class InMemorySession implements Session
{
    private $id;
    private $data;

    public function __construct($session_id)
    {
        $this->id = $session_id;
        $this->data = new \ArrayObject();
    }

    /**
     * @return mixed
     */
    public function getSessionId()
    {
        return $this->id;
    }

    /**
     * @param string $var
     * @param mixed $value
     * @return mixed
     */
    public function set($var, $value)
    {
        $this->data->offsetSet($var, $value);
    }

    /**
     * @param string $var
     * @param mixed $var
     * @return mixed
     */
    public function get($var, $default = null)
    {
        return $this->data->offsetExists($var) ? $this->data->offsetGet($var) : $default;
    }

    /**
     * @param string $var
     * @return mixed
     */
    public function delete($var)
    {
        $this->data->offsetUnset($var);
    }
}