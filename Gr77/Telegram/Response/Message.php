<?php
/**
 * Project: citybike
 *
 * File: Message.php
 * Created by Cristiano Cucco. * E-mail: cristiano.cucco@gmail.com.
 * Date: 20/04/2016
 * Time: 11:00
 */

namespace Gr77\Telegram\Response;


class Message extends Response
{
    /** @var \Gr77\Telegram\Message\Message */
    protected $message;

    /**
     * @return \Gr77\Telegram\Message\Message
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param $result result field in telegram response
     */
    protected function parseResult($result)
    {
        $this->message = \Gr77\Telegram\Message\Message::mapFromArray($result);
    }
}