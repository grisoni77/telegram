<?php
/**
 * Project: citybike
 *
 * File: ReplyKeyboardMarkup.php
 * Created by Cristiano Cucco. * E-mail: cristiano.cucco@gmail.com.
 * Date: 20/04/2016
 * Time: 18:13
 */

namespace Gr77\Telegram\ReplyMarkup;

/**
 * Class ReplyKeyboardMarkup
 * This object represents a custom keyboard with reply options (see Introduction to bots for details and examples).
 *
 * @package Gr77\Telegram\ReplyMarkup
 */
class ReplyKeyboardMarkup extends ReplyMarkup
{
    /**
     * Array of button rows, each represented by an Array of KeyboardButton objects
     * @var KeyboardButtonRow[]
     */
    public $keyboard;
    /**
     * Optional. Requests clients to resize the keyboard vertically for optimal fit (e.g., make the keyboard smaller
     * if there are just two rows of buttons). Defaults to false, in which case the custom keyboard is always of the
     * same height as the app's standard keyboard.
     * @var bool
     */
    public $resize_keyboard;
    /**
     * Optional. Requests clients to hide the keyboard as soon as it's been used. The keyboard will still be available,
     * but clients will automatically display the usual letter-keyboard in the chat – the user can press a special button
     * in the input field to see the custom keyboard again. Defaults to false.
     * @var bool
     */
    public $one_time_keyboard;
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
     * ReplyKeyboardMarkup constructor.
     */
    public function __construct()
    {
        $this->keyboard = new \ArrayObject();
    }

    /**
     * @param KeyboardButtonRow $row
     */
    public function appendRow(KeyboardButtonRow $row)
    {
        $this->keyboard->append($row);
    }


    /**
     * @return KeyboardButtonRow[]
     */
    public function getKeyboard()
    {
        return $this->keyboard;
    }

    /**
     * @param KeyboardButtonRow[] $keyboard
     * @return ReplyKeyboardMarkup
     */
    public function setKeyboard($keyboard)
    {
        $this->keyboard = $keyboard;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isResizeKeyboard()
    {
        return $this->resize_keyboard;
    }

    /**
     * @param boolean $resize_keyboard
     * @return ReplyKeyboardMarkup
     */
    public function setResizeKeyboard($resize_keyboard)
    {
        $this->resize_keyboard = $resize_keyboard;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isOneTimeKeyboard()
    {
        return $this->one_time_keyboard;
    }

    /**
     * @param boolean $one_time_keyboard
     * @return ReplyKeyboardMarkup
     */
    public function setOneTimeKeyboard($one_time_keyboard)
    {
        $this->one_time_keyboard = $one_time_keyboard;
        return $this;
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
     * @return ReplyKeyboardMarkup
     */
    public function setSelective($selective)
    {
        $this->selective = $selective;
        return $this;
    }


    public function toArray()
    {
        $data = array(
            "keyboard" => array(),
        );
        foreach ($this->getKeyboard() as $row) {
            $data["keyboard"][] = $row->getArrayCopy();
        }
        if (isset($this->one_time_keyboard)) {
            $data["one_time_keyboard"] = $this->isOneTimeKeyboard();
        }
        if (isset($this->resize_keyboard)) {
            $data["resize_keyboard"] = $this->isResizeKeyboard();
        }
        if (isset($this->selective)) {
            $data["selective"] = $this->isSelective();
        }
        return $data;
    }


}