<?php
/**
 * Project: citybike
 *
 * File: Message.php
 * Created by Cristiano Cucco.
 * E-mail: cristiano.cucco@gmail.com.
 * Date: 19/04/2016
 * Time: 16:08
 */

namespace Gr77\Telegram\Message;


use Gr77\Telegram\Chat\Chat;
use Gr77\Telegram\Message\Content\Location;
use Gr77\Telegram\Message\Content\PhotoSize;
use Gr77\Telegram\Message\Entity\BotCommand;
use Gr77\Telegram\Message\Entity\MessageEntity;
use Gr77\Telegram\User;

/**
 * Class Message
 * This object represents a message.
 * @package Gr77\Telegram\Message
 * @see https://core.telegram.org/bots/api#message
 */
class Message
{
    /**
     * @var int
     */
    private $message_id;
    /**
     * Optional. Sender, can be empty for messages sent to channels
     * @var \Gr77\Telegram\User
     */
    private $from;
    /**
     * Conversation the message belongs to
     * @var \Gr77\Telegram\Chat\Chat
     */
    private $chat;
    /**
     * Date the message was sent in Unix time
     * @var int
     */
    private $date;
    /**
     * Optional. For replies, the original message.
     * Note that the Message object in this field will not contain further reply_to_message fields even if it itself is a reply.
     * @var Message
     */
    private $reply_to_message;
    /**
     * @var string
     */
    private $text;
    /**
     * Optional. For text messages, special entities like usernames, URLs, bot commands, etc. that appear in the text
     * @var Entity\MessageEntity[]
     */
    private $entities;
    /**
     * Optional. Message is a shared location, information about the location
     * @var Content\Location
     */
    private $location;
    /**
     * Optional. Message is a photo, available sizes of the photo
     * @var PhotoSize[]
     */
    private $photo;

    private $data;

    private function __construct()
    {
    }

    public static function mapFromArray($data)
    {
        $message = new self();
        $message->setMessageId($data["message_id"]);
        $message->setChat(Chat::mapFromArray($data["chat"]));
        $message->setDate($data["date"]);
        if (isset($data["from"])) {
            $message->setFrom(User::mapFromArray($data["from"]));
        }
        if (isset($data["reply_to_message"])) {
            $message->setReplyToMessage(self::mapFromArray($data["reply_to_message"]));
        }
        if (isset($data["text"])) {
            $message->setText($data["text"]);
        }
        if (isset($data["entities"])) {
            $message->setEntities(new \ArrayObject());
            foreach ($data["entities"] as $entity) {
                $message->addEntity(MessageEntity::mapFromArray($entity, $data["text"]));
            }
        }
        if (isset($data["location"])) {
            $message->setLocation(Location::mapFromArray($data["location"]));
        }
        if (isset($data["photo"]) && is_array($data["photo"]) && count($data["photo"])>0) {
            $message->setPhoto(new \ArrayObject());
            foreach ($data["photo"] as $photosize) {
                $message->addPhotosize(PhotoSize::mapFromArray($photosize));
            }
        }

        $message->setData($data);
        return $message;
    }

    /**
     * @return mixed
     */
    public function getMessageId()
    {
        return $this->message_id;
    }

    /**
     * @param mixed $message_id
     */
    public function setMessageId($message_id)
    {
        $this->message_id = $message_id;
        return $this;
    }

    /**
     * @return int
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param int $date
     * @return Message
     */
    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return Message
     */
    public function getReplyToMessage()
    {
        return $this->reply_to_message;
    }

    /**
     * @param Message $reply_to_message
     * @return Message
     */
    public function setReplyToMessage($reply_to_message)
    {
        $this->reply_to_message = $reply_to_message;
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
     * @return Message
     */
    public function setFrom($from)
    {
        $this->from = $from;
        return $this;
    }

    /**
     * @return \Gr77\Telegram\Chat\Chat
     */
    public function getChat()
    {
        return $this->chat;
    }

    /**
     * @param \Gr77\Telegram\Chat\Chat $chat
     * @return Message
     */
    public function setChat($chat)
    {
        $this->chat = $chat;
        return $this;
    }

    /**
     * @return Entity\MessageEntity[]
     */
    public function getEntities()
    {
        return $this->entities;
    }

    /**
     * @param Entity\MessageEntity[] $entities
     * @return Message
     */
    public function setEntities($entities)
    {
        $this->entities = $entities;
        return $this;
    }

    /**
     * @param MessageEntity $entity
     * @return $this
     */
    public function addEntity(MessageEntity $entity)
    {
        $this->entities->append($entity);
        return $this;
    }

    /**
     * @return Content\PhotoSize[]
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * @param Content\PhotoSize[] $photo
     * @return Message
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;
        return $this;
    }

    /**
     * @param PhotoSize $photosize
     * @return $this
     */
    public function addPhotosize(PhotoSize $photosize)
    {
        $this->photo->append($photosize);
        return $this;
    }


    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $text
     * @return Message
     */
    public function setText($text)
    {
        $this->text = $text;
        return $this;
    }

    /**
     * @return Location
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param Location $location
     * @return Message
     */
    public function setLocation($location)
    {
        $this->location = $location;
        return $this;
    }



    /**
     * Ritorna true se la prima delle entity è un command
     * @return bool
     */
    public function isCommand()
    {
        if (!isset($this->entities)) {
            return false;
        }
        foreach ($this->entities as $entity) {
            if ($entity->getOffset()==0) {
                return $entity instanceof BotCommand;
            }
        }
        return false;
    }

    public function getCommand()
    {
        foreach ($this->entities as $entity) {
            if ($entity->getOffset()==0) {
                if ($entity instanceof BotCommand) {
                    return $entity;
                } else {
                    return false;
                }
            }
        }
        return false;
    }

    /**
     * @return bool
     */
    public function hasText()
    {
        return isset($this->text);
    }

    /**
     * @return bool
     */
    public function hasLocation()
    {
        return isset($this->location);
    }

    /**
     * Magic method per recuperare var non definite direttamente tramite membri della classe
     * ma presenti nell'array membro $data
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }
        throw new \OutOfBoundsException('Property is not set in message: '.$name, 400);
    }
}