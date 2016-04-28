<?php
/**
 * Project: citybike
 *
 * File: KeyboardButtonRow.php
 * Created by Cristiano Cucco. * E-mail: cristiano.cucco@gmail.com.
 * Date: 20/04/2016
 * Time: 18:20
 */

namespace Gr77\Telegram\ReplyMarkup;


class InlineKeyboardButtonRow extends \ArrayObject
{

    /**
     * @param InlineKeyboardButton $button
     */
    public function appendButton(InlineKeyboardButton $button)
    {
        $this->append($button);
    }

    /**
     * @param InlineKeyboardButton $button
     */
    public function prependButton(InlineKeyboardButton $button)
    {
        $this->prepend($button);
    }

    public function appendUrl($text, $url)
    {
        $this->appendButton(new InlineKeyboardButtonUrl($text, $url));
    }

    public function prependUrl($text, $url)
    {
        $this->prependButton(new InlineKeyboardButtonUrl($text, $url));
    }

    public function appendCallbackQuery($text, $url)
    {
        $this->appendButton(new InlineKeyboardButtonCallbackQuery($text, $url));
    }

    public function prependCallbackQuery($text, $url)
    {
        $this->prependButton(new InlineKeyboardButtonCallbackQuery($text, $url));
    }

    public function appendSwitchInlineQuery($text, $url)
    {
        $this->appendButton(new InlineKeyboardButtonSwitchInlineQuery($text, $url));
    }

    public function prependSwitchInlineQuery($text, $url)
    {
        $this->prependButton(new InlineKeyboardButtonSwitchInlineQuery($text, $url));
    }
}