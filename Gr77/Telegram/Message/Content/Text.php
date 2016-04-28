<?php
/**
 * Project: citybike
 *
 * File: Text.php
 * Created by Cristiano Cucco.
 * E-mail: cristiano.cucco@gmail.com
 * Date: 22/04/2016
 * Time: 14:38
 */

namespace Gr77\Telegram\Message\Content;


class Text
{
    /**
     * @var string
     */
    protected $text;
    /**
     * @var string|null
     */
    protected $parse_mode;

    /**
     * Text constructor.
     * @param $text
     */
    public function __construct($text)
    {
        $this->text = $text;
        $this->parse_mode = null;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @return null|string
     */
    public function getParseMode()
    {
        return $this->parse_mode;
    }


    /**
     * @return string
     */
    public function __toString()
    {
        return $this->text;
    }
}