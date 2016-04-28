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
    /** @var  array config specific for bot */
    protected $config_bot = array();


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
        // registra gli handlers dei messaggi
        $this->registerHandlers($config);

        // config bot
        if (isset($config["config_bot"])) {
            $this->config_bot = $config["config_bot"];
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
    }


    /**
     * @param string $word
     * @param array|string $handler classname dell'handler
     */
    public function registerCommandHandler($word, $handler)
    {
        if (!$this->commandHandlers->offsetGet($word)) {
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
        return array_key_exists($word, $this->commandHandlers);
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
        if (!$this->textHandlers->offsetGet($regexp)) {
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
        $regexp = sprintf("/%s/", preg_quote($text));
        if (!$this->textHandlers->offsetGet($regexp)) {
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
     * Gestisce risposta da Telegram api
     *
     * @param Response $response
     */
    public function handleUpdate(Update $update)
    {
        if ($update->hasMessage()) {
            $this->handleMessage($update);
        }
        if ($update->hasCallbackQuery()) {
            $this->handleCallbackQuery($update);
        }
        if ($update->hasInlineQuery()) {
            $this->handleInlineQuery($update);
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
            $this->handleCommand($update);
        }
        else {
            $this->handleText($update);
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
                $handler = $handlerClassname::provide($this->client, $this->config_bot);
                if (false === $handler($update)) {
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
                $handler = $handlerClassname::provide($this->client, $this->config_bot);
                if (false === $handler($update)) {
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
        $data = $callbackQuery->getData();
        list($class,$method) = explode("::", $data);
        $className = "\\CbBot\\Handler\\".ucfirst($class);
        $methodName = "handle".ucfirst($method);
        if (class_exists($className)) {
            $handler = $className::provide($this->client, $this->config_bot);
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
                $handler = $handlerClassname::provide($this->client, $this->config_bot);
                if (false === $handler($update)) {
                    break;
                }
            }
        }
    }
}