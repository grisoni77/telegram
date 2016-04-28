<?php
/**
 * Project: citybike
 *
 * File: ReplyKeyboardHide.php
 * Created by Cristiano Cucco. * E-mail: cristiano.cucco@gmail.com.
 * Date: 21/04/2016
 * Time: 10:04
 */

namespace Gr77\Telegram\ReplyMarkup;

/**
 * Class ReplyKeyboardHide
 * Upon receiving a message with this object, Telegram clients will hide the current custom keyboard and display
 * the default letter-keyboard. By default, custom keyboards are displayed until a new keyboard is sent by a bot.
 * An exception is made for one-time keyboards that are hidden immediately after the user presses a button (see ReplyKeyboardMarkup).
 * @package Gr77\Telegram\ReplyMarkup
 */
class ReplyKeyboardHide extends ReplyMarkup
{
    /**
     * Optional. Use this parameter if you want to show the keyboard to specific users only.
     * Targets:
     * 1) users that are @mentioned in the text of the Message object;
     * 2) if the bot's message is a reply (has reply_to_message_id), sender of the original message.
     * Example: A user requests to change the bot‘s language, bot replies to the request with a keyboard to select
     * the new language. Other users in the group don’t see the keyboard.
     * @var bool
     */
    public $selective;

    /**
     * ReplyKeyboardHide constructor.
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
     * @return ReplyKeyboardHide
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
            "hide_keyboard" => true,
            "selective" => $this->selective,
        );
    }
}