<?php
/**
 * Project: telegram
 *
 * File: InlineQuery.php
 * Created by Cristiano Cucco.
 * E-mail: cristiano.cucco@gmail.com
 * Date: 05/10/2016
 * Time: 22:18
 */

namespace Gr77\Controller\Handler;


use Gr77\Command\InlineQueryHandler;
use Gr77\Controller\Handler;
use Gr77\Session\Session;
use Gr77\Telegram\Client;
use Gr77\Telegram\Update;
use Psr\Log\LoggerInterface;

class InlineQuery extends Handler
{
    /** @var  \ArrayObject */
    private $inlineQueryHandlers;

    /**
     * Text constructor.
     * @param array $config
     */
    public function __construct($config = array())
    {
        $this->inlineQueryHandlers = new \ArrayObject();

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
        if (isset($config["inlinequeryHandlers"]) && is_array($config["inlinequeryHandlers"]) && count($config["inlinequeryHandlers"])>0) {
            foreach ($config["inlinequeryHandlers"] as $handler) {
                $this->registerInlineQueryHandler($handler);
            }
        }
    }

    /**
     * @param string $handler classname dell'handler
     */
    public function registerInlineQueryHandler($handler)
    {
        if (!$this->inlineQueryHandlers->offsetExists('all_handlers')) {
            $this->inlineQueryHandlers->offsetSet('all_handlers', new \ArrayObject());
        }
        $this->inlineQueryHandlers['all_handlers']->append($handler);
    }

    /**
     * @param string $regexp
     * @param string $handler classname dell'handler
     */
    public function registerRegexpHandler($regexp, $handler)
    {
        if (!$this->inlineQueryHandlers->offsetExists($regexp)) {
            $this->inlineQueryHandlers->offsetSet($regexp, new \ArrayObject());
        }
        $this->inlineQueryHandlers[$regexp]->append($handler);
    }

    /**
     * @param string $text
     * @param string $handler classname dell'handler
     */
    public function registerTextHandler($text, $handler)
    {
        $regexp = sprintf("/^%s$/i", preg_quote($text));
        if (!$this->inlineQueryHandlers->offsetExists($regexp)) {
            $this->inlineQueryHandlers->offsetSet($regexp, new \ArrayObject());
        }
        $this->inlineQueryHandlers[$regexp]->append($handler);
    }

    /**
     * @param string $text testo passato al bot
     * @return \ArrayObject|bool ritorna false se non ci sono handler per questo comando
     */
    protected function getInlineQueryHandlers($text)
    {
        $results = new \ArrayObject();
        $allHandlers = $this->inlineQueryHandlers->offsetGet('all_handlers');
        foreach ($allHandlers as $allHandler) {
            $results[] = $allHandler;
        }
        $regexps = array_keys($this->inlineQueryHandlers->getArrayCopy());
        foreach ($regexps as $regexp) {
            if (preg_match($regexp, $text) === 1) {
                foreach ($this->inlineQueryHandlers[$regexp] as $handler) {
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
        if ($update->hasInlineQuery()) {
            $inlineQuery = $update->getInlineQuery();
            //print_r($callbackQuery);
            $query = $inlineQuery->getQuery();
            if (false !== $handlers = $this->getInlineQueryHandlers($query)) {
                //var_dump($handlers);
                foreach ($handlers as $handlerClassname) {
                    /** @var \Gr77\Command\InlineQueryHandler $handler */
                    $handler = $handlerClassname::provide(client, $session, $config, $logger);
                    if ($handler instanceof InlineQueryHandler && false === $handler->handleInlineQuery($update)) {
                        break;
                    }
                }
            }
        }
        else {
            parent::handleUpdate($update, $client, $session, $config, $logger);
        }
    }

}