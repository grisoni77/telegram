<?php
/**
 * Project: citybike
 *
 * File: InlineKeyboardButtonUrl.php
 * Created by Cristiano Cucco.
 * E-mail: cristiano.cucco@gmail.com
 * Date: 22/04/2016
 * Time: 17:04
 */

namespace Gr77\Telegram\ReplyMarkup;


class InlineKeyboardButtonUrl extends InlineKeyboardButton
{
    /**
     * Optional. HTTP url to be opened when button is pressed
     * @var string
     */
    public $url;

    /**
     * InlineKeyboardButtonUrl constructor.
     * @param string $url
     */
    public function __construct($text, $url)
    {
        $this->text = $text;
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return InlineKeyboardButtonUrl
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }



}