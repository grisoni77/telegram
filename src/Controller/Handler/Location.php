<?php
/**
 * Created by PhpStorm.
 * User: cris77
 * Date: 04/10/2016
 * Time: 17:01
 */

namespace Gr77\Controller\Handler;


use Gr77\Controller\Handler;
use Gr77\Session\Session;
use Gr77\Telegram\Client;
use Gr77\Telegram\Update;
use Psr\Log\LoggerInterface;

class Location extends Handler
{
    /** @var  \ArrayObject */
    private $locationHandlers;


    public function __construct($config = array())
    {
        $this->locationHandlers = new \ArrayObject();

        if (isset($config["locationHandlers"]) && is_array($config["locationHandlers"]) && count($config["locationHandlers"])>0) {
            foreach ($config["locationHandlers"] as $locationHandlers) {
                settype($locationHandlers, 'array');
                foreach ($locationHandlers as $handler) {
                    $this->registerLocationHandler($handler);
                }
            }
        }
    }

    /**
     * @param string $handler classname of handler
     */
    public function registerLocationHandler($handler)
    {
        $key = md5($handler);
        if (!$this->locationHandlers->offsetExists($key)) {
            $this->locationHandlers->offsetSet($key, $handler);
        }
    }

    /**
     * Handle update and passes it to next in chain
     * @param Update $update
     * @param Client $client
     * @param Session $session
     * @param array $config
     * @param LoggerInterface $logger
     * @return void
     */
    public function handleUpdate(Update $update, Client $client, Session $session, $config = array(), LoggerInterface $logger = null)
    {
        $is_channel_bot = isset($config['config_bot']['channel_bot']) && $config['config_bot']['channel_bot'];
        $has_location = ($update->hasMessage() && $update->getMessage()->hasLocation())
            || ($is_channel_bot && $update->hasChannelPost() && $update->getChannelPost()->hasLocation());

        $handled = false;
        if ($has_location && count($this->locationHandlers)>0) {
            $handled = true;
            foreach ($this->locationHandlers as $handlerClassname) {
                /** @var \Gr77\Command\LocationHandler $handler */
                $handler = $handlerClassname::provide($client, $session, $config, $logger);
                if (false === $handler->handleLocation($update)) {
                    break;
                }
            }
        }
        if (!$handled) {
            parent::handleUpdate($update, $client, $session, $config, $logger);
        }
    }
}