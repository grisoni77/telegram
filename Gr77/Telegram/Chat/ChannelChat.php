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


class ChannelChat extends Chat
{
    /**
     * Optional. Title, for channels and group chats
     * @var string
     */
    public $title;
    /**
     * Optional. Username, for private chats and channels if available
     * @var string
     */
    public $username;


    /**
     * @param array $data
     * @return Chat
     */
    protected static function _mapFromArray($data)
    {
        $chat = parent::_mapFromArray($data);
        if (isset($data["title"])) {
            $chat->title = $data["title"];
        }
        if (isset($data["username"])) {
            $chat->username = $data["username"];
        }
        return $chat;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return ChannelChat
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
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
     * @return ChannelChat
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

}