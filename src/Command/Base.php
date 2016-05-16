<?php
/**
 * Project: citybike
 *
 * File: Base.php
 * Created by Cristiano Cucco. * E-mail: cristiano.cucco@gmail.com.
 * Date: 20/04/2016
 * Time: 16:36
 */

namespace Gr77\Command;

use Gr77\Telegram\Client;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

abstract class Base implements Handler
{
    /** @var \Gr77\Telegram\Client  */
    protected $client;
    /** @var  \Psr\Log\LoggerInterface */
    protected $logger;
    /** @var  array config bot */
    protected $config;

//    protected $session;

    /**
     * Base constructor.
     * @param Client $client
     * @param LoggerInterface|null $logger
     * @param array $config
     */
    public function __construct(Client $client, $config = array(), LoggerInterface $logger = null)
    {
        $this->client = $client;
        if (null == $logger) {
            $this->logger = new NullLogger();
        } else {
            $this->logger = $logger;
        }
        $this->config = $config;
    }

    /**
     * @param Client $client
     * @param LoggerInterface|null $logger
     * @param array $config
     * @return static
     */
    public static function provide(Client $client, $config = array(), LoggerInterface $logger = null)
    {
        $handler = new static($client, $config, $logger);
        return $handler;
    }

    /**
     * @return string
     */
    public static function getClassName() {
        return get_called_class();
    }

//    protected function initSession($session_id)
//    {
//        session_id($session_id);
//        session_start();
//        $this->session = $_SESSION;
//        return $this->session;
//    }
//
//    /**
//     * @return mixed
//     */
//    protected function getSession()
//    {
//        return $this->session;
//    }

    protected function setState($var, $value)
    {
        if (isset($_SESSION)) {
            $_SESSION[$var] = $value;
        } else {
            throw new \BadMethodCallException("Session is not initialized");
        }
    }

    protected function getState($var, $default = null)
    {
        if (isset($_SESSION)) {
            if (isset($_SESSION[$var])) {
                return $_SESSION[$var];
            } else {
                return $default;
            }
        } else {
            throw new \BadMethodCallException("Session is not initialized");
        }
    }
}