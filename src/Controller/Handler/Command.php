<?php
/**
 * Created by PhpStorm.
 * User: cris77
 * Date: 04/10/2016
 * Time: 17:04
 */

namespace Gr77\Controller\Handler;


use Gr77\Command\CommandHandler;
use Gr77\Controller\Handler;
use Gr77\Session\Session;
use Gr77\Telegram\Client;
use Gr77\Telegram\Update;
use Psr\Log\LoggerInterface;

class Command extends Handler
{
    /** @var  \ArrayObject */
    private $commandHandlers;

    public function __construct($config = array())
    {
        $this->commandHandlers = new \ArrayObject();

        if (isset($config["commandHandlers"]) && is_array($config["commandHandlers"]) && count($config["commandHandlers"])>0) {
            foreach ($config["commandHandlers"] as $command => $commandHandler) {
                settype($commandHandler, 'array');
                foreach ($commandHandler as $handler) {
                    $this->registerCommandHandler($command, $handler);
                }
            }
        }
    }

    /**
     * @param string $word
     * @param array|string $handler classname dell'handler
     */
    public function registerCommandHandler($word, $handler)
    {
        if (!$this->commandHandlers->offsetExists($word)) {
            $this->commandHandlers->offsetSet($word, new \ArrayObject());
        }
        $this->commandHandlers[$word]->append($handler);
    }

    /**
     * @param $word
     * @return bool
     */
    protected function hasCommandHandlers($word)
    {
        return $this->commandHandlers->offsetExists($word);
    }

    /**
     * @param string word rappresentante il comando (es. start)
     * @return Callable|bool ritorna false se non ci sono handler per questo comando
     */
    protected function getCommandHandlers($word)
    {
        return $this->commandHandlers->offsetGet($word);
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
        $handled = false;
        if ($update->hasMessage() && $update->getMessage()->isCommand()) {
            $command = $update->getMessage()->getCommand();
            $text = $command->getValue();
            if ($this->hasCommandHandlers($text)) {
                $handled = true;
                $handlers = $this->getCommandHandlers($text);
                //var_dump($handlers);
                foreach ($handlers as $handlerClassname) {
                    /** @var CommandHandler $handler */
                    $handler = $handlerClassname::provide($client, $session, $config, $logger);
                    if (false === $handler->handleCommand($update)) {
                        break;
                    }
                }
            }
        }
        if (!$handled) {
            parent::handleUpdate($update, $client, $session, $config, $logger);
        }
    }
}