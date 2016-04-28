<?php
/**
 * Project: citybike
 *
 * File: InlineKeyboardButton.php
 * Created by Cristiano Cucco.
 * E-mail: cristiano.cucco@gmail.com
 * Date: 22/04/2016
 * Time: 16:43
 */

namespace Gr77\Telegram\ReplyMarkup;

/**
 * Class InlineKeyboardButton
 * This object represents one button of an inline keyboard. You must use exactly one of the optional fields.
 * @package Gr77\Telegram\ReplyMarkup
 */
abstract class InlineKeyboardButton
{
    /**
     * Label text on the button
     * @var string
     */
    public $text;


    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $text
     * @return InlineKeyboardButton
     */
    public function setText($text)
    {
        $this->text = $text;
        return $this;
    }



}