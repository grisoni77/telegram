<?php
/**
 * Project: citybike
 *
 * File: Controller.php
 * Created by Cristiano Cucco. * E-mail: cristiano.cucco@gmail.com.
 * Date: 20/04/2016
 * Time: 15:09
 */

namespace Gr77\Controller;


use Gr77\Command\GenericHandler;
use Gr77\Command\LocationHandler;
use Gr77\Session\NullSession;
use Gr77\Session\Session;
use Gr77\Session\SessionFactory;
use Gr77\Telegram\ReplyMarkup\InlineKeyboardButtonCallbackQuery;
use Gr77\Telegram\Response\Response;
use Gr77\Telegram\Response\Updates;
use Gr77\Telegram\Update;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class Controller
{
    /** @var  string */
    protected $token;
    /** @var \Gr77\Telegram\Client  */
    protected $client;
    /** @var  \Psr\Log\LoggerInterface */
    protected $logger;
    /** @var  \ArrayObject */
    protected $commandHandlers;
    /** @var  \ArrayObject */
    protected $textHandlers;
    /** @var  \ArrayObject */
    protected $locationHandlers;
    /** @var  \ArrayObject */
    protected $genericHandlers;
    /** @var  array config specific for bot */
    protected $config_bot = array();
    /** @var  Session */
    protected $session;

    /**
     * Controller constructor.
     * @param $token
     * @param \Gr77\Telegram\Client $client
     * @param LoggerInterface|null $logger
     */
    public function __construct($token, \Gr77\Telegram\Client $client, $config = array(), LoggerInterface $logger = null)
    {
        $this->token = $token;
        $this->client = $client;
        $this->client->setToken($token);
        if (isset($logger)) {
            $this->logger = $logger;
        } else {
            $this->logger = new NullLogger();
        }
        $this->commandHandlers = new \ArrayObject();
        $this->textHandlers = new \ArrayObject();
        $this->locationHandlers = new \ArrayObject();
        $this->genericHandlers = new \ArrayObject();
        // registra gli handlers dei messaggi
        $this->registerHandlers($config);

        // config bot
        if (isset($config["config_bot"])) {
            $this->config_bot = $config["config_bot"];
        }

        // config session type
        if (!isset($config["session_type"])) {
            $this->config_bot["session_type"] = "null";
        }
    }


    /**
     * Dentro questa funzione le classi concrete registrano gli handler specifici
     * dei comandi che devono poter gestire
     * @params array $config
     * @return void
     */
    protected function registerHandlers($config = array())
    {
        if (isset($config["commandHandlers"]) && is_array($config["commandHandlers"]) && count($config["commandHandlers"])>0) {
            foreach ($config["commandHandlers"] as $command => $commandHandler) {
                settype($commandHandler, 'array');
                foreach ($commandHandler as $handler) {
                    $this->registerCommandHandler($command, $handler);
                }
            }
        }
        if (isset($config["textHandlers"]) && is_array($config["textHandlers"]) && count($config["textHandlers"])>0) {
            foreach ($config["textHandlers"] as $text => $textHandler) {
                settype($textHandler, 'array');
                foreach ($textHandler as $handler) {
                    $this->registerTextHandler($text, $handler);
                }
            }
        }
        if (isset($config["regexpHandlers"]) && is_array($config["regexpHandlers"]) && count($config["regexpHandlers"])>0) {
            foreach ($config["regexpHandlers"] as $regexp => $regexpHandler) {
                settype($regexpHandler, 'array');
                foreach ($regexpHandler as $handler) {
                    $this->registerRegexpHandler($regexp, $handler);
                }
            }
        }
        if (isset($config["locationHandlers"]) && is_array($config["locationHandlers"]) && count($config["locationHandlers"])>0) {
            foreach ($config["locationHandlers"] as $locationHandlers) {
                settype($locationHandlers, 'array');
                foreach ($locationHandlers as $handler) {
                    $this->registerLocationHandler($handler);
                }
            }
        }
        if (isset($config["genericHandlers"]) && is_array($config["genericHandlers"]) && count($config["genericHandlers"])>0) {
            foreach ($config["genericHandlers"] as $genericHandlers) {
                settype($genericHandlers, 'array');
                foreach ($genericHandlers as $handler) {
                    $this->registerLocationHandler($handler);
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
     * @param string $regexp
     * @param string $handler classname dell'handler
     */
    public function registerRegexpHandler($regexp, $handler)
    {
        if (!$this->textHandlers->offsetExists($regexp)) {
            $this->textHandlers->offsetSet($regexp, new \ArrayObject());
        }
        $this->textHandlers[$regexp]->append($handler);
    }

    /**
     * @param string $text
     * @param string $handler classname dell'handler
     */
    public function registerTextHandler($text, $handler)
    {
        $regexp = sprintf("/^%s$/i", preg_quote($text));
        if (!$this->textHandlers->offsetExists($regexp)) {
            $this->textHandlers->offsetSet($regexp, new \ArrayObject());
        }
        $this->textHandlers[$regexp]->append($handler);
    }

    /**
     * @param string $text testo passato al bot
     * @return \ArrayObject|bool ritorna false se non ci sono handler per questo comando
     */
    protected function getTextHandlers($text)
    {
        $results = new \ArrayObject();
        $regexps = array_keys($this->textHandlers->getArrayCopy());
        foreach ($regexps as $regexp) {
            if (preg_match($regexp, $text) === 1) {
                foreach ($this->textHandlers[$regexp] as $handler) {
                    $results->append($handler);
                }
            }
        }
        if (count($results)==0) {
            return false;
        }
        return $results;
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
     * @param GenericHandler $handler
     */
    public function registerGenericHandler(GenericHandler $handler)
    {
        $key = md5($handler);
        if (!$this->genericHandlers->offsetExists($key)) {
            $this->genericHandlers->offsetSet($key, $handler);
        }
    }



    /**
     * Gestisce risposta da Telegram api
     *
     * @param Response $response
     */
    public function handle(Response $response)
    {
        if ($response instanceof Updates) {
            /** @var \Gr77\Telegram\Update $update */
            foreach ($response->getUpdates() as $update) {
                $this->handleUpdate($update);
            }
        }
    }

    /**
     * Init session based on chat_id
     * @param Update $update
     */
    private function initChatSession(Update $update)
    {
        $session_id = $this->getSessionId($update);
//        session_id($session_id);
//        session_start();
        $this->session = SessionFactory::create($session_id, $this->config_bot["session_type"], $this->token);
    }

    /**
     * Check for handler waiting for answer
     * @return bool
     */
    private function isHandlerWaitingForAnswer()
    {
        $handler_waiting = $this->session->get("handler_waiting");
        return isset($handler_waiting);
    }

    /**
     * Gestisce risposta da Telegram api
     *
     * @param Response $response
     */
    public function handleUpdate(Update $update)
    {
        $this->initChatSession($update);

        if ($this->isHandlerWaitingForAnswer()) {
            return $this->handleWaitedAnswer($update);
        }
        if ($update->hasMessage()) {
            return $this->handleMessage($update);
        }
        if ($update->hasCallbackQuery()) {
            return $this->handleCallbackQuery($update);
        }
        if ($update->hasInlineQuery()) {
            return $this->handleInlineQuery($update);
        }
        if ($update->hasChosenInlineResult()) {
            return $this->handleChosenInlineResult($update);
        }
    }

    /**
     * Gestisce Update di tipo message
     *
     * @param \Gr77\Telegram\Update $update
     */
    protected function handleMessage(Update $update)
    {
        $message = $update->getMessage();
        // manage bot command
        if ($message->isCommand()) {
            return $this->handleCommand($update);
        }
        elseif ($message->hasText()) {
            return $this->handleText($update);
        }
        elseif ($message->hasLocation()) {
            return $this->handleLocation($update);
        }
        else {
            return $this->handleGeneric($update);
        }

    }

    /**
     * Gestisce Bot command
     *
     * @param Update $update
     * @param $command
     */
    protected function handleCommand(Update $update)
    {
        $command = $update->getMessage()->getCommand();
        $text = $command->getValue();
        if ($this->hasCommandHandlers($text)) {
            $handlers = $this->getCommandHandlers($text);
            //var_dump($handlers);
            foreach ($handlers as $handlerClassname) {
                $handler = $handlerClassname::provide($this->client, $this->session, $this->config_bot, $this->logger);
                if (false === $handler->handleCommand($update)) {
                    break;
                }
            }
        }
    }

    /**
     * Gestisce testo generico inviato al bot
     *
     * @param Update $update
     * @param $command
     */
    protected function handleText(Update $update)
    {
        $text = $update->getMessage()->text;
        if (false !== $handlers = $this->getTextHandlers($text)) {
            //var_dump($handlers);
            foreach ($handlers as $handlerClassname) {
                /** @var \Gr77\Command\TextHandler $handler */
                $handler = $handlerClassname::provide($this->client, $this->session, $this->config_bot, $this->logger);
                if (false === $handler->handleText($update)) {
                    break;
                }
            }
        }
    }


    /**
     * Gestisce Update di tipo CallbackQuery
     *
     * @param \Gr77\Telegram\Update $update
     */
    protected function handleCallbackQuery(Update $update)
    {
        $callbackQuery = $update->getCallbackQuery();
        //print_r($callbackQuery);
//        $data = $callbackQuery->getData();
//        list($className,$method) = explode("::", $data);
        $data = InlineKeyboardButtonCallbackQuery::unserializedData($callbackQuery->getData());
        $className = $this->config_bot["handler_namespace"].$data[0];
        $method = $data[1];
        $methodName = "handle".ucfirst($method);
        if (class_exists($className)) {
            /** @var \Gr77\Command\CommandHandler $handler */
            $handler = $className::provide($this->client, $this->session, $this->config_bot, $this->logger);
            if (method_exists($handler, $methodName)) {
                return call_user_func(array($handler, $methodName), $update);
            }
        }
    }


    /**
     * Gestisce Update di tipo InlineQuery
     *
     * @param \Gr77\Telegram\Update $update
     */
    protected function handleInlineQuery(Update $update)
    {
        $inlineQuery = $update->getInlineQuery();
        //print_r($callbackQuery);
        $query = $inlineQuery->getQuery();
        if (false !== $handlers = $this->getTextHandlers($query)) {
            //var_dump($handlers);
            foreach ($handlers as $handlerClassname) {
                /** @var \Gr77\Command\InlineQueryHandler $handler */
                $handler = $handlerClassname::provide($this->client, $this->session, $this->config_bot, $this->logger);
                if (false === $handler->handleInlineQuery($update)) {
                    break;
                }
            }
        }
    }

    /**
     * Gestisce Update di tipo InlineQuery
     *
     * @param \Gr77\Telegram\Update $update
     */
    protected function handleChosenInlineResult(Update $update)
    {
        $chosenInlineResult = $update->getChosenInlineResult();
        //print_r($chosenInlineResult);
        $query = $chosenInlineResult->getQuery();
        if (false !== $handlers = $this->getTextHandlers($query)) {
            //var_dump($handlers);
            foreach ($handlers as $handlerClassname) {
                /** @var \Gr77\Command\InlineQueryHandler $handler */
                $handler = $handlerClassname::provide($this->client, $this->session, $this->config_bot, $this->logger);
                if (false === $handler->handleChosenInlineResult($update)) {
                    break;
                }
            }
        }
    }


    /**
     * Handle Location sent to Bot
     *
     * @param Update $update
     * @param $command
     */
    protected function handleLocation(Update $update)
    {
        foreach ($this->locationHandlers as $handlerClassname) {
            /** @var \Gr77\Command\LocationHandler $handler */
            $handler = $handlerClassname::provide($this->client, $this->session, $this->config_bot, $this->logger);
            if (false === $handler->handleLocation($update)) {
                break;
            }
        }
    }

    /**
     * Handle generic message sent to bot
     *
     * @param Update $update
     * @param $command
     */
    protected function handleGeneric(Update $update)
    {
        foreach ($this->genericHandlers as $handlerClassname) {
            /** @var \Gr77\Command\GenericHandler $handler */
            $handler = $handlerClassname::provide($this->client, $this->session, $this->config_bot, $this->logger);
            if (false === $handler->handleGeneric($update)) {
                break;
            }
        }
    }


    /**
     * Handle generic message sent to bot
     *
     * @param Update $update
     * @param $command
     */
    protected function handleWaitedAnswer(Update $update)
    {
        $handler_waiting =  $this->session->get("handler_waiting");
        $this->session->unset("handler_waiting");
        $handlerClassname = $handler_waiting;
        /** @var \Gr77\Command\AnswerHandler $handler */
        $handler = $handlerClassname::provide($this->client, $this->session, $this->config_bot, $this->logger);
        return $handler->handleAnswer($update);
    }

    /**
     * @param Update $update
     * @return int
     */
    private function getSessionId(Update $update)
    {
        if ($update->hasMessage()) {
            $session_id = $update->getMessage()->getChat()->getId();
            return $session_id;
        } elseif ($update->hasCallbackQuery()) {
            $session_id = $update->getCallbackQuery()->getMessage()->getChat()->getId();
            return $session_id;
        } elseif ($update->hasInlineQuery()) {
            $session_id = $update->getInlineQuery()->getFrom()->getId();
            return $session_id;
        } elseif ($update->hasChosenInlineResult()) {
            $session_id = $update->getChosenInlineResult()->getFrom()->getId();
            return $session_id;
        }
        return $session_id;
    }
}