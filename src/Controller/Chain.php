<?php
/**
 * Created by PhpStorm.
 * User: cris77
 * Date: 04/10/2016
 * Time: 14:41
 */

namespace Gr77\Controller;

use Gr77\Session\Session;
use Gr77\Session\SessionFactory;
use Gr77\Telegram\Update;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class Chain
{
    /** @var  string */
    private $name;

    /** @var \Gr77\Telegram\Client  */
    protected $client;

    /** @var  array */
    private $config;

    /** @var Handler[] */
    private $handlers;

    /** @var  Session */
    private $session;

    /** @var  \Psr\Log\LoggerInterface */
    protected $logger;

    /**
     * Chain constructor.
     * @param $name
     * @param \Gr77\Telegram\Client $client
     * @param array $config
     * @param LoggerInterface $logger
     */
    public function __construct($name, \Gr77\Telegram\Client $client, $config = array(), LoggerInterface $logger = null)
    {
        $this->name = $name;
        $this->client = $client;
        if (!isset($config["session_type"])) {
            $config["session_type"] = "php";
        }
        $this->config = $config;
        if (isset($logger)) {
            $this->logger = $logger;
        } else {
            $this->logger = new NullLogger();
        }
        $this->handlers = array();
    }

    /**
     * @param Handler $handler
     */
    public function addHandler(Handler $handler) {
        if (count($this->handlers)>0) {
            $last = end($this->handlers);
            $last->setSuccessor($handler);
        }
        $this->handlers[] = $handler;
    }

    /**
     * Handle Telegram Update
     * @param Update $update
     * @return void
     */
    public function handle(Update $update)
    {
        // init chat session
        $this->initChatSession($update);

        // send update to the chain
        $this->handlers[0]->handleUpdate($update, $this->client, $this->session, $this->config, $this->logger);
    }


    /**
     * Init session based on chat_id
     * @param Update $update
     */
    private function initChatSession(Update $update)
    {
        $session_id = $this->getSessionId($update);
        $this->session = SessionFactory::create($session_id, $this->config["session_type"], $this->name);
    }

    /**
     * @param Update $update
     * @return int
     */
    private function getSessionId(Update $update)
    {
        if (false !== $chat_id = $update->getChatId()) {
            return $chat_id;
        }
        return session_id();
    }

}