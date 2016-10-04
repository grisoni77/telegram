<?php
/**
 * Created by PhpStorm.
 * User: cris77
 * Date: 04/10/2016
 * Time: 14:54
 */

namespace Gr77\Controller\Handler;


use Gr77\Controller\Handler;
use Gr77\Session\Session;
use Gr77\Telegram\Client;
use Gr77\Telegram\Update;
use Psr\Log\LoggerInterface;

class Text extends Handler
{
    /** @var  \ArrayObject */
    private $textHandlers;

    /**
     * Text constructor.
     * @param array $config
     */
    public function __construct($config = array())
    {
        $this->textHandlers = new \ArrayObject();

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
     * Handle update and passes it to next in chain
     * @param Update $update
     */
    public function handleUpdate(Update $update, Client $client, Session $session, $config = array(), LoggerInterface $logger = null)
    {
        $text = $update->getMessage()->text;
        if (false !== $handlers = $this->getTextHandlers($text)) {
            //var_dump($handlers);
            foreach ($handlers as $handlerClassname) {
                /** @var \Gr77\Command\TextHandler $handler */
                $handler = $handlerClassname::provide($client, $session, $config, $logger);
                if (false === $handler->handleText($update)) {
                    break;
                }
            }
        } else {
            $this->successor->handleUpdate($update, $client, $session, $config, $logger);
        }
    }
}