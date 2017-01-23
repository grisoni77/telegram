<?php
/**
 * Created by PhpStorm.
 * User: cris77
 * Date: 23/01/2017
 * Time: 14:41
 */

namespace Gr77\Controller\Handler;


use Gr77\Controller\Handler;
use Gr77\Session\Session;
use Gr77\Telegram\Client;
use Gr77\Telegram\Message\Content\PlainText;
use Gr77\Telegram\Update;
use Psr\Log\LoggerInterface;

/**
 * Class RepeatedUpdates
 * Track last handled update and filter out repeated ones
 * @package Gr77\Controller\Handler
 */
class RepeatedUpdates extends Handler
{
    public function handleUpdate(Update $update, Client $client, Session $session, $config = array(), LoggerInterface $logger = null)
    {
        $last_update_id = $session->get('last_update_id', null);
        $update_id = $update->getUpdateId();

        // some updates from this chat has been already tracked in this session
        if (isset($last_update_id)) {
            // repeated update...send void response
            if ($last_update_id==$update_id) {
                return $client->sendMessage($update->getChatId(), new PlainText(""));
            }
            // valid update id... track it and process it
            else {
                $session->set('last_update_id', $update_id);
                return parent::handleUpdate($update, $client, $session, $config, $logger);
            }
        }
        // first update in session for this chat
        else {
            $session->set('last_update_id', $update_id);
            return parent::handleUpdate($update, $client, $session, $config, $logger);
        }
    }

}