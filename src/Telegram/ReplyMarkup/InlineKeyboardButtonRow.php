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

    /**
     * @param string $text
     * @param array $data
     */
    public function appendCallbackQuery($text, $data)
    {
        $this->appendButton(new InlineKeyboardButtonCallbackQuery($text, $data));
    }

    /**
     * @param string $text
     * @param array $data
     */
    public function prependCallbackQuery($text, $data)
    {
        $this->prependButton(new InlineKeyboardButtonCallbackQuery($text, $data));
    }

    public function appendSwitchInlineQuery($text, $switch_inline_query)
    {
        $this->appendButton(new InlineKeyboardButtonSwitchInlineQuery($text, $switch_inline_query));
    }

    public function prependSwitchInlineQuery($text, $switch_inline_query)
    {
        $this->prependButton(new InlineKeyboardButtonSwitchInlineQuery($text, $switch_inline_query));
    }
}