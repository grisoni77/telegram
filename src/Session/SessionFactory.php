<?php
/**
 * Project: citybike
 *
 * File: SessionFactory.php
 * Created by Cristiano Cucco.
 * E-mail: cristiano.cucco@gmail.com
 * Date: 17/05/2016
 * Time: 16:15
 */

namespace Gr77\Session;


abstract class SessionFactory
{
    /**
     * @param int $session_id
     * @param string $type Session handler type
     * @param string $token bot token
     * @return Session
     */
    public static function create($session_id, $type, $token)
    {
        switch ($type)
        {
            case 'php':
                return new PhpSession($session_id, $token);

            default:
                return new NullSession();

        }
    }
}