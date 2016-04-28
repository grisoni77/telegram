<?php
/**
 * Project: citybike
 *
 * File: InlineKeyboardButtonSwitchInlineQuery.php
 * Created by Cristiano Cucco.
 * E-mail: cristiano.cucco@gmail.com
 * Date: 22/04/2016
 * Time: 17:05
 */

namespace Gr77\Telegram\ReplyMarkup;


class InlineKeyboardButtonSwitchInlineQuery extends InlineKeyboardButton
{
    /**
     * Optional. If set, pressing the button will prompt the user to select one of their chats, open that chat and insert
     * the bot‘s username and the specified inline query in the input field. Can be empty, in which case just
     * the bot’s username will be inserted.
     * Note: This offers an easy way for users to start using your bot in inline mode when they are currently in a private
     * chat with it. Especially useful when combined with switch_pm… actions – in this case the user will be automatically
     * returned to the chat they switched from, skipping the chat selection screen.
     * @var string
     */
    public $switch_inline_query;

    /**
     * InlineKeyboardButtonSwitchInlineQuery constructor.
     * @param string $switch_inline_query
     */
    public function __construct($text, $switch_inline_query)
    {
        $this->text = $text;
        $this->switch_inline_query = $switch_inline_query;
    }

    /**
     * @return string
     */
    public function getSwitchInlineQuery()
    {
        return $this->switch_inline_query;
    }

    /**
     * @param string $switch_inline_query
     * @return InlineKeyboardButtonSwitchInlineQuery
     */
    public function setSwitchInlineQuery($switch_inline_query)
    {
        $this->switch_inline_query = $switch_inline_query;
        return $this;
    }


}