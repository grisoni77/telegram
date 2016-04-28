<?php
/**
 * Project: citybike
 *
 * File: CallbackQuery.php
 * Created by Cristiano Cucco.
 * E-mail: cristiano.cucco@gmail.com
 * Date: 22/04/2016
 * Time: 17:21
 */

namespace Gr77\Telegram\CallbackQuery;


use Gr77\Telegram\Message\Message;
use Gr77\Telegram\User;

class CallbackQuery
{
    /**
     * Unique identifier for this query
     * @var string
     */
    private $id;
    /**
     * Sender
     * @var \Gr77\Telegram\User
     */
    private $from;
    /**
     * Optional. Message with the callback button that originated the query. Note that message content and message date
     * will not be available if the message is too old
     * @var \Gr77\Telegram\Message\Message
     */
    private $message;
    /**
     * Optional. Identifier of the message sent via the bot in inline mode, that originated the query
     * @var string
     */
    private $inline_message_id;
    /**
     * Data associated with the callback button. Be aware that a bad client can send arbitrary data in this field
     * @var string
     */
    private $data;

    /**
     * CallbackQuery constructor.
     * @param $id
     * @param User $from
     */
    private function __construct($id, User $from)
    {
        $this->id = $id;
        $this->from = $from;
    }

    /**
     * @param $data
     * @return CallbackQuery
     */
    public static function mapFromArray($data)
    {
        if (!isset($data["id"]) || !isset($data["from"])) {
            throw new \InvalidArgumentException("Id and from are mandatory fields for CallbackQuery", 400);
        }
        $callbackQuery = new self($data["id"], User::mapFromArray($data["from"]));
        if (isset($data["message"])) {
            $callbackQuery->setMessage(Message::mapFromArray($data["message"]));
        }
        if (isset($data["inline_message_id"])) {
            $callbackQuery->setInlineMessageId($data["inline_message_id"]);
        }
        $callbackQuery->setData($data['data']);
        return $callbackQuery;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return CallbackQuery
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return \Gr77\Telegram\User
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param \Gr77\Telegram\User $from
     * @return CallbackQuery
     */
    public function setFrom($from)
    {
        $this->from = $from;
        return $this;
    }

    /**
     * @return \Gr77\Telegram\Message\Message
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param \Gr77\Telegram\Message\Message $message
     * @return CallbackQuery
     */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return string
     */
    public function getInlineMessageId()
    {
        return $this->inline_message_id;
    }

    /**
     * @param string $inline_message_id
     * @return CallbackQuery
     */
    public function setInlineMessageId($inline_message_id)
    {
        $this->inline_message_id = $inline_message_id;
        return $this;
    }

    /**
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param string $data
     * @return CallbackQuery
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }


}