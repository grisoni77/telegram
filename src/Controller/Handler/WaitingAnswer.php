<?php
/**
 * Project: telegram
 *
 * File: WaitingAnswer.php
 * Created by Cristiano Cucco.
 * E-mail: cristiano.cucco@gmail.com
 * Date: 05/10/2016
 * Time: 21:59
 */

namespace Gr77\Controller\Handler;


use Gr77\Controller\Handler;
use Gr77\Session\Session;
use Gr77\Telegram\Client;
use Gr77\Telegram\Update;
use Psr\Log\LoggerInterface;

class WaitingAnswer extends Handler
{

    public function __construct()
    {
    }

    /**
     * Check for handler waiting for answer
     * @return bool
     */
    private function isHandlerWaitingForAnswer(Session $session)
    {
        $handler_waiting = $session->get("handler_waiting");
        return isset($handler_waiting);
    }

    /**
     * @param Update $update
     * @param Client $client
     * @param Session $session
     * @param array $config
     * @param LoggerInterface|null $logger
     */
    public function handleUpdate(
        Update $update,
        Client $client,
        Session $session,
        $config = array(),
        LoggerInterface $logger = null
    ) {
        if ($this->isHandlerWaitingForAnswer($session)) {
            $handler_waiting =  $session->get("handler_waiting");
            $session->delete("handler_waiting");
            $handlerClassname = $handler_waiting;
            /** @var \Gr77\Command\AnswerHandler $handler */
            $handler = $handlerClassname::provide($client, $session, $config, $logger);
            return $handler->handleAnswer($update);
        } else {
            parent::handleUpdate($update, $client, $session, $config, $logger);
        }
    }

}