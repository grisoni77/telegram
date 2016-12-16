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
        $handler = new static($client, $session, $config, $logger);

        // init settings if any
        if (isset($config['config_bot']['settings'])) {
            $settingsInitClass = $config['config_bot']['settings_namespace'].'Initialize';
            $settingsInitClass::init($handler);
        }

        return $handler;
    }

    /**
     * @return string
     */
    public static function getClassName() {
        return get_called_class();
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * @param LoggerInterface $logger
     * @return Base
     */
    public function setLogger($logger)
    {
        $this->logger = $logger;
        return $this;
    }

    /**
     * Returns chat_id derived from session
     * (it must always be set, otherwise an exception is thrown during session initialization)
     * @return string
     */
    public function getChatId()
    {
        return $this->session->getSessionId();
    }

    protected function setState($var, $value)
    {
        // protected states..
        if (in_array($var, ['settings'])) {
            return;
        }

        $this->session->set($var, $value);
    }

    protected function unsetState($var)
    {
        // protected states..
        if (in_array($var, ['settings'])) {
            return;
        }

        $this->session->delete($var);
    }

    protected function getState($var, $default = null)
    {
        return $this->session->get($var, $default);
    }

    /**
     * @param array $settings
     */
    public function setSettings($settings)
    {
        $this->session->set('settings', $settings);
    }

    /**
     * @param string $setting
     * @param mixed $default
     * @return mixed
     */
    public function getSetting($setting, $default = null)
    {
        $settings = $this->getState('settings', []);
        if (isset($settings[$setting])) {
            return $settings[$setting];
        } else {
            return null;
        }
    }

    /**
     * Utility to force the next answer to be sent to this handler
     */
    protected function setWaitingAnswer()
    {
        $class = $this->getClassName();
        $this->setState("handler_waiting", $class);
    }
}