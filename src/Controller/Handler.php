<?php
/**
 * Created by PhpStorm.
 * User: cris77
 * Date: 04/10/2016
 * Time: 14:51
 */

namespace Gr77\Controller;


use Gr77\Session\Session;
use Gr77\Telegram\Client;
use Gr77\Telegram\Update;
use Psr\Log\LoggerInterface;

abstract class Handler
{
    /** @var Handler $successor */
    protected $successor;

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
        if (isset($this->successor)) {
            $this->successor->handleUpdate($update, $client, $session, $config, $logger);
        }
    }

    /**
     * @return Handler
     */
    public function getSuccessor()
    {
        return $this->successor;
    }

    /**
     * @param Handler $successor
     * @return Handler
     */
    public function setSuccessor(Handler $successor)
    {
        $this->successor = $successor;
        return $this;
    }

}