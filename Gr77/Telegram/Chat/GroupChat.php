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


class GroupChat extends Chat
{
    /**
     * Optional. Title, for channels and group chats
     * @var string
     */
    public $title;

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
     * @return GroupChat
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

}