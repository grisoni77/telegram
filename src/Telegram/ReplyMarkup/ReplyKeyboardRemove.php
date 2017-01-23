<?php
/**
 * Created by PhpStorm.
 * User: cris77
 * Date: 16/12/2016
 * Time: 17:33
 */

namespace Gr77\Telegram\ReplyMarkup;

/**
 * Class ReplyKeyboardRemove
 * Upon receiving a message with this object, Telegram clients will remove the current custom keyboard and display the
 * default letter-keyboard. By default, custom keyboards are displayed until a new keyboard is sent by a bot. An exception
 * is made for one-time keyboards that are hidden immediately after the user presses a button (see ReplyKeyboardMarkup).
 * @package Gr77\Telegram\ReplyMarkup
 */
class ReplyKeyboardRemove extends ReplyMarkup
{

    /**
     * Optional. Use this parameter if you want to remove the keyboard for specific users only.
     * Targets:
     * 1) users that are @mentioned in the text of the Message object;
     * 2) if the bot's message is a reply (has reply_to_message_id), sender of the original message.
     * Example: A user votes in a poll, bot returns confirmation message in reply to the vote and removes the keyboard
     * for that user, while still showing the keyboard with poll options to users who haven't voted yet.
     * @var bool
     */
    public $selective;

    /**
     * ReplyKeyboardRemove constructor.
     * @param $selective
     */
    public function __construct($selective = false)
    {
        $this->selective = $selective;
    }

    /**
     * @return boolean
     */
    public function isSelective()
    {
        return $this->selective;
    }

    /**
     * @param boolean $selective
     * @return ReplyKeyboardRemove
     */
    public function setSelective($selective)
    {
        $this->selective = $selective;
        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return array(
            // Requests clients to hide the custom keyboard
            "remove_keyboard" => true,
            "selective" => $this->selective,
        );
    }
}