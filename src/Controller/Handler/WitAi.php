<?php
/**
 * Created by PhpStorm.
 * User: cris77
 * Date: 04/10/2016
 * Time: 17:07
 */

namespace Gr77\Controller\Handler;


use Gr77\Controller\Handler;
use Gr77\Session\Session;
use Gr77\Telegram\Client;
use Gr77\Telegram\Update;
use Guzzle\Common\Exception\BadMethodCallException;
use Psr\Log\LoggerInterface;

class WitAi extends Handler
{
    /** @var  \ArrayObject */
    private $intentHandlers;

    private $wit_ai_secret;

    public function __construct($config = array())
    {
        $this->intentHandlers = new \ArrayObject();

        if (!isset($config['wit_ai_secret'])) {
            throw new BadMethodCallException("Wit.ai secret key is mandatory", 400);
        }
        $this->wit_ai_secret = $config['wit_ai_secret']);

        if (isset($config["intentHandlers"]) && is_array($config["intentHandlers"]) && count($config["intentHandlers"])>0) {
            foreach ($config["intentHandlers"] as $intent => $intentHandlers) {
                settype($intentHandlers, 'array');
                foreach ($intentHandlers as $handler) {
                    $this->registerIntentHandler($intent, $handler);
                }
            }
        }
    }

    /**
     * @param string $intent
     * @param string $handler
     */
    public function registerIntentHandler($intent, $handler)
    {
        if (!$this->intentHandlers->offsetExists($intent)) {
            $this->intentHandlers->offsetSet($intent, new \ArrayObject());
        }
        $this->intentHandlers[$intent]->append($handler);
    }

    /**
     * @param string $intent
     * @return \ArrayObject|bool ritorna false se non ci sono handler per questo intent
     */
    protected function getIntentHandlers($intent)
    {
        return $this->intentHandlers->offsetGet($intent);
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
        $text = $update->getMessage()->hasText() ? $update->getMessage()->text : false;
        if (false !== $text) {
            // handle user intent wit.ai api
            $witaiClient = new \Tgallice\Wit\Client($this->wit_ai_secret);
            $response = $witaiClient->get("/message", array(
                "q" => $text,
                "thread_id" => $this->session->getSessionId(),
            ));
            $message = json_decode((string) $response->getBody(), true);
            if (isset($message["entities"]["Intent"])) {
                $confidence = 0;
                $intentType = null;
                foreach ($message["entities"]["Intent"] as $item) {
                    if ($item["confidence"] > $confidence) {
                        $intentType = $item["value"];
                        $confidence = $item["confidence"];
                    }
                }
                if (isset($intentType) && null !== $handlers = $this->getIntentHandlers($intentType)) {
                    //var_dump($handlers);
                    foreach ($handlers as $handlerClassname) {
                        /** @var \Gr77\Command\TextHandler $handler */
                        $handler = $handlerClassname::provide($client, $session, $config_bot, $logger);
                        $intent = new WitAiIntent($intentType, $message);
                        if (false === $handler->handleIntent($update, $intent)) {
                            break;
                        }
                    }
                }
            }
        } else {
            parent::handleUpdate($update, $client, $session, $config, $logger);
        }
    }
}