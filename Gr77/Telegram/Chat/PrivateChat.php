<?php
/**
 * Project: citybike
 *
 * File: ChannelChat.php
 * Created by Cristiano Cucco.
 * E-mail: cristiano.cucco@gmail.com
 * Date: 22/04/2016
 * Time: 17:50
 */

namespace Gr77\Telegram\Chat;


class PrivateChat extends Chat
{
    /**
     * Optional. Username, for private chats and channels if available
     * @var string
     */
    public $username;
    /**
     * Optional. First name of the other party in a private chat
     * @var string
     */
    public $first_name;
    /**
     * Optional. Last name of the other party in a private chat
     * @var string
     */
    public $last_name;

    /**
     * @param array $data
     * @return Chat
     */
    protected static function _mapFromArray($data)
    {
        $chat = parent::_mapFromArray($data);
        if (isset($data["first_name"])) {
            $chat->first_name = $data["first_name"];
        }
        if (isset($data["last_name"])) {
            $chat->last_name = $data["last_name"];
        }
        if (isset($data["username"])) {
            $chat->username = $data["username"];
        }
        return $chat;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return PrivateChat
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * @param string $first_name
     * @return PrivateChat
     */
    public function setFirstName($first_name)
    {
        $this->first_name = $first_name;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * @param string $last_name
     * @return PrivateChat
     */
    public function setLastName($last_name)
    {
        $this->last_name = $last_name;
        return $this;
    }

}