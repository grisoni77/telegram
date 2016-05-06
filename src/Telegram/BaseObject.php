<?php
/**
 * Project: crp_bot
 *
 * File: BaseObject.php
 * Created by Cristiano Cucco.
 * E-mail: cristiano.cucco@gmail.com
 * Date: 06/05/2016
 * Time: 10:55
 */

namespace Gr77\Telegram;


abstract class BaseObject
{
    public static function getProperties() {
        return array();
    }

    public function __get($name)
    {
        if (method_exists($this, 'get'.ucfirst($name))) {
            return call_user_func(array($this, 'get'.ucfirst($name)));
        } elseif (isset($this->$name)) {
            return $this->$name;
        } else {
            throw new \RuntimeException("Property ".$name." does not exist in class ". get_called_class(), 500);
        }
    }
}