<?php
/**
 * Project: citybike
 *
 * File: Chat.php
 * Created by Cristiano Cucco.
 * E-mail: cristiano.cucco@gmail.com
 * Date: 22/04/2016
 * Time: 17:48
 */

namespace Gr77\Telegram\Chat;

/**
 * Class Chat
 * This object represents a chat.
 * @package Gr77\Telegram\Chat
 * @see https://core.telegram.org/bots/api#chat
 */
abstract class Chat
{
    /**
     * Unique identifier for this chat, not exceeding 1e13 by absolute value
     * @var int
     */
    public $id;
    /**
     * Type of chat, can be either “private”, “group”, “supergroup” or “channel”
     * @var string
     */
    public $type;

    /**
     * Chat constructor.
     * @param int $id
     * @param string $type
     */
    public function __construct($id, $type)
    {
        $this->id = $id;
        $this->type = $type;
    }

    /**
     * @param array $data
     * @return Chat
     */
    public static function mapFromArray($data)
    {
        if (isset($data["type"]))
        {
            $separator = '_';
            $className = __NAMESPACE__
                . "\\".str_replace($separator, '', implode('',array_map("ucfirst", explode('_', $data["type"]))))."Chat";
//            $className = __NAMESPACE__."\\".str_replace($separator, '', ucwords($data["type"], $separator));
            if (!class_exists($className)) {
                throw new \InvalidArgumentException("Invalid Chat type: ".$data["type"], 400);
            }
            return $className::_mapFromArray($data);
        }
        else {
            throw new \InvalidArgumentException("Empty Chat type", 400);
        }
    }

    /**
     * @param array $data
     * @return Chat
     */
    protected static function _mapFromArray($data)
    {
        if (!isset($data["id"]) || !isset($data["type"])) {
            throw new \InvalidArgumentException("Id and type are mandatory fields for Chat", 400);
        }
        $chat = new static($data["id"], $data["type"]);
        return $chat;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Chat
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return Chat
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }


}