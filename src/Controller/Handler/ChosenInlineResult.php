<?php
/**
 * Project: telegram
 *
 * File: ChosenInlineResult.php
 * Created by Cristiano Cucco.
 * E-mail: cristiano.cucco@gmail.com
 * Date: 05/10/2016
 * Time: 22:18
 */

namespace Gr77\Controller\Handler;


use Gr77\Controller\Handler;
use Gr77\Session\Session;
use Gr77\Telegram\Client;
use Gr77\Telegram\Update;
use Psr\Log\LoggerInterface;

class ChosenInlineResult extends Handler
{
    /** @var  \ArrayObject */
    private $chosenInlineResultHandlers;

    /**
     * Text constructor.
     * @param array $config
     */
    public function __construct($config = array())
    {
        $this->chosenInlineResultHandlers = new \ArrayObject();

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
        if (!$this->chosenInlineResultHandlers->offsetExists($regexp)) {
            $this->chosenInlineResultHandlers->offsetSet($regexp, new \ArrayObject());
        }
        $this->chosenInlineResultHandlers[$regexp]->append($handler);
    }

    /**
     * @param string $text
     * @param string $handler classname dell'handler
     */
    public function registerTextHandler($text, $handler)
    {
        $regexp = sprintf("/^%s$/i", preg_quote($text));
        if (!$this->chosenInlineResultHandlers->offsetExists($regexp)) {
            $this->chosenInlineResultHandlers->offsetSet($regexp, new \ArrayObject());
        }
        $this->chosenInlineResultHandlers[$regexp]->append($handler);
    }

    /**
     * @param string $text testo passato al bot
     * @return \ArrayObject|bool ritorna false se non ci sono handler per questo comando
     */
    protected function getChosenInlineResultHandlers($text)
    {
        $results = new \ArrayObject();
        $regexps = array_keys($this->chosenInlineResultHandlers->getArrayCopy());
        foreach ($regexps as $regexp) {
            if (preg_match($regexp, $text) === 1) {
                foreach ($this->chosenInlineResultHandlers[$regexp] as $handler) {
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
        if ($update->hasChosenInlineResult()) {
            $chosenInlineResult = $update->getChosenInlineResult();
            //print_r($chosenInlineResult);
            $query = $chosenInlineResult->getQuery();
            if (false !== $handlers = $this->getChosenInlineResultHandlers($query)) {
                //var_dump($handlers);
                foreach ($handlers as $handlerClassname) {
                    /** @var \Gr77\Command\ChosenInlineResultHandler $handler */
                    $handler = $handlerClassname::provide($client, $session, $config, $logger);
                    $logger->debug(__METHOD__.": handled by ".$handlerClassname);
                    if (false === $handler->handleChosenInlineResult($update)) {
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