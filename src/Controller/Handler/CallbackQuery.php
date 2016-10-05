<?php
/**
 * Project: telegram
 *
 * File: CallbackQuery.php
 * Created by Cristiano Cucco.
 * E-mail: cristiano.cucco@gmail.com
 * Date: 05/10/2016
 * Time: 22:05
 */

namespace Gr77\Controller\Handler;


use Gr77\Controller\Handler;
use Gr77\Session\Session;
use Gr77\Telegram\Client;
use Gr77\Telegram\ReplyMarkup\InlineKeyboardButtonCallbackQuery;
use Gr77\Telegram\Update;
use Psr\Log\LoggerInterface;

class CallbackQuery extends Handler
{
    /**
     * Handle update and passes it to next in chain
     * @param Update $update
     * @param Client $client
     * @param Session $session
     * @param array $config
     * @param LoggerInterface|null $logger
     */
    public function handleUpdate(Update $update, Client $client, Session $session, $config = array(), LoggerInterface $logger = null)
    {
        if ($update->hasCallbackQuery())
        {
            $callbackQuery = $update->getCallbackQuery();
            $data = InlineKeyboardButtonCallbackQuery::unserializedData($callbackQuery->getData());
            $className = $config["handler_namespace"].$data[0];
            $method = $data[1];
            $methodName = "handle".ucfirst($method);
            if (class_exists($className)) {
                $handler = $className::provide($client, $session, $config, $logger);
                if (method_exists($handler, $methodName)) {
                    return call_user_func(array($handler, $methodName), $update);
                }
            }
        }
        else {
            parent::handleUpdate($update, $client, $session, $config, $logger);
        }
    }
}