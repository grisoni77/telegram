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

use Gr77\Session\Session;
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
    /** @var Session  */
    protected $session;

    /**
     * Base constructor.
     * @param Client $client
     * @param Session $session
     * @param array $config
     * @param LoggerInterface|null $logger
     */
    public function __construct(Client $client, Session $session, $config = array(), LoggerInterface $logger = null)
    {
        $this->client = $client;
        if (null == $logger) {
            $this->logger = new NullLogger();
        } else {
            $this->logger = $logger;
        }
        $this->config = $config;
        $this->session = $session;
    }

    /**
     * @param Client $client
     * @param Session $session
     * @param array $config
     * @param LoggerInterface|null $logger
     * @return static
     */
    public static function provide(Client $client, Session $session, $config = array(), LoggerInterface $logger = null)
    {
        return new static($client, $session, $config, $logger);
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

    /**
     * @return Session
     */
//    public function getSession()
//    {
//        return $this->session;
//    }

    protected function setState($var, $value)
    {
        $this->session->set($var, $value);
    }

    protected function unsetState($var)
    {
        $this->session->delete($var);
    }

    protected function getState($var, $default = null)
    {
        return $this->session->get($var, $default);
    }

    protected function setWaitingAnswer()
    {
        $class = $this->getClassName();
        $this->setState("handler_waiting", $class);
    }
}