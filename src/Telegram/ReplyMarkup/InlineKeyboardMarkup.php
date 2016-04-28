<?php
/**
 * Project: citybike
 *
 * File: InlineKeyboardMarkup.php
 * Created by Cristiano Cucco.
 * E-mail: cristiano.cucco@gmail.com
 * Date: 22/04/2016
 * Time: 16:41
 */

namespace Gr77\Telegram\ReplyMarkup;

/**
 * Class InlineKeyboardMarkup
 * This object represents an inline keyboard that appears right next to the message it belongs to.
 * @package Gr77\Telegram\ReplyMarkup
 * @see https://core.telegram.org/bots/api#inlinekeyboardmarkup
 */
class InlineKeyboardMarkup extends ReplyMarkup
{
    /**
     * Array of button rows, each represented by an Array of InlineKeyboardButton objects
     * @var InlineKeyboardButtonRow[]
     */
    public $inline_keyboard;

    /**
     * InlineKeyboardMarkup constructor.
     */
    public function __construct()
    {
        $this->inline_keyboard = new \ArrayObject();
    }

    /**
     * @param InlineKeyboardButtonRow $row
     */
    public function appendRow(InlineKeyboardButtonRow $row)
    {
        $this->inline_keyboard->append($row);
    }

    /**
     * @return InlineKeyboardButtonRow[]
     */
    public function getInlineKeyboard()
    {
        return $this->inline_keyboard;
    }

    /**
     * @param InlineKeyboardButtonRow[] $inline_keyboard
     * @return InlineKeyboardMarkup
     */
    public function setInlineKeyboard($inline_keyboard)
    {
        $this->inline_keyboard = $inline_keyboard;
        return $this;
    }



    public function toArray()
    {
        $data = array(
            "inline_keyboard" => array(),
        );
        foreach ($this->getInlineKeyboard() as $row) {
            $data["inline_keyboard"][] = $row->getArrayCopy();
        }
        return $data;
    }
}