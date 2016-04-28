<?php
/**
 * Project: citybike
 *
 * File: ForceReply.php
 * Created by Cristiano Cucco. * E-mail: cristiano.cucco@gmail.com.
 * Date: 21/04/2016
 * Time: 10:04
 */

namespace Gr77\Telegram\ReplyMarkup;

/**
 * Class ForceReply
 * Upon receiving a message with this object, Telegram clients will display a reply interface to the user
 * (act as if the user has selected the bot‘s message and tapped ’Reply').
 * This can be extremely useful if you want to create user-friendly step-by-step interfaces
 * without having to sacrifice privacy mode.
 * @package Gr77\Telegram\ReplyMarkup
 * @see https://core.telegram.org/bots/api#forcereply
 */
class ForceReply extends ReplyMarkup
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
            "force_reply" => true,
            "selective" => $this->selective,
        );
    }
}