<?php
/**
 * Project: citybike
 *
 * File: InlineKeyboardButtonCallbackQuery.php
 * Created by Cristiano Cucco.
 * E-mail: cristiano.cucco@gmail.com
 * Date: 22/04/2016
 * Time: 17:04
 */

namespace Gr77\Telegram\ReplyMarkup;


class InlineKeyboardButtonCallbackQuery extends InlineKeyboardButton
{
    /**
     * Optional. Data to be sent in a callback query to the bot when button is pressed, 1-64 bytes
     * @var string
     */
    public $callback_data;

    /**
     * InlineKeyboardButtonCallbackQuery constructor.
     * @param string $text
     * @param string|array $callback_data
     */
    public function __construct($text, $callback_data)
    {
        $this->text = $text;
        if (is_array($callback_data)) {
            $callback_data = InlineKeyboardButtonCallbackQuery::serializeData($callback_data);
        }
        $this->callback_data = $callback_data;
    }

    /**
     * @return string
     */
    public function getCallbackData()
    {
        return $this->callback_data;
    }

    /**
     * @param string $callback_data
     * @return InlineKeyboardButtonCallbackQuery
     */
    public function setCallbackData($callback_data)
    {
        $this->callback_data = $callback_data;
        return $this;
    }

    /**
     * @param array $data
     */
    public static function serializeData($data)
    {
        return implode("::", $data);
    }

    /**
     * @return array
     */
    public static function unserializedData($data)
    {
        return explode("::", $data);
    }

}