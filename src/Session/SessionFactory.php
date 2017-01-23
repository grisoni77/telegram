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
     * @param array $config
     * @return Session
     */
    public static function create($session_id, $type, $token, $config = array())
    {
        switch ($type)
        {
            case 'php':
                return new PhpSession($session_id, $token);

            case 'in-memory':
                return new InMemorySession($session_id);

            default:
                return new NullSession();

        }
    }
}