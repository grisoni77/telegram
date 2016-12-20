<?php
/**
 * Created by PhpStorm.
 * User: cris77
 * Date: 04/10/2016
 * Time: 17:07
 */

namespace Gr77\Controller\Handler;


use Gr77\Command\Intent\WitAiIntent;
use Gr77\Controller\Handler;
use Gr77\Session\Session;
use Gr77\Telegram\Client;
use Gr77\Telegram\Update;
use Psr\Log\LoggerInterface;

class Intent extends Handler
{
    /** @var  \ArrayObject */
    private $intentHandlers;

    private $wit_ai_secret;

    public function __construct($config = array())
    {
        $this->intentHandlers = new \ArrayObject();

        if (!isset($config['config_bot']['wit_ai_secret'])) {
            throw new \BadMethodCallException("Wit.ai secret key missing in config", 400);
        }
        $this->wit_ai_secret = $config['config_bot']['wit_ai_secret'];

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
        return $this->intentHandlers->offsetExists($intent) ? $this->intentHandlers->offsetGet($intent) : false;
    }

    /**
     * try to return intent from text through Wit.ai API
     * @param string $text
     * @param string $thread_id
     * @return bool|string false if not an intent
     */
    private function getIntentFromText($text, $thread_id)
    {
        // handle user intent wit.ai api
        $witaiClient = new \Tgallice\Wit\Client($this->wit_ai_secret);
        $response = $witaiClient->get("/message", array(
            "q" => $text,
            "thread_id" => $thread_id,
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
            return isset($intentType) ? $intentType : false;
        }
        return false;
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
        $is_channel_bot = isset($config['config_bot']['channel_bot']) && $config['config_bot']['channel_bot'];
        if ($update->hasMessage() && $update->getMessage()->hasText()) {
            $text = $update->getMessage()->getText();
        } elseif ($is_channel_bot && $update->hasChannelPost() && $update->getChannelPost()->hasText()) {
            $text = $update->getChannelPost()->getText();
        }

        $handled = false;
        if (isset($text)) {
            $intentType = $this->getIntentFromText($text, $session->getSessionId());
            if (false !== $intentType && false !== $handlers = $this->getIntentHandlers($intentType)) {
                $handled = true;
                foreach ($handlers as $handlerClassname) {
                    /** @var \Gr77\Command\IntentHandler $handler */
                    $handler = $handlerClassname::provide($client, $session, $config, $logger);
                    $intent = new WitAiIntent($intentType, $update->getMessage());
                    $logger->debug(__METHOD__.": ".$intentType." handled by ".$handlerClassname);
                    if (false === $handler->handleIntent($update, $intent)) {
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