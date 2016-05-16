<?php
/**
 * Project: crp_bot
 *
 * File: InlineQueryResultArticle.php
 * Created by Cristiano Cucco.
 * E-mail: cristiano.cucco@gmail.com
 * Date: 26/04/2016
 * Time: 18:38
 */

namespace Gr77\Telegram\InlineQuery\InlineQueryResult;

use Gr77\Telegram\InlineQuery\Input\InputMessageContent;

/**
 * Class InlineQueryResultArticle
 * Represents a link to an article or web page.
 * @package Gr77\Telegram\InlineQuery\InlineQueryResult
 * @see https://core.telegram.org/bots/api#inlinequeryresultarticle
 */
class InlineQueryResultArticle extends InlineQueryResult
{
    /**
     *
     * @var string
     */
    private $title;
    /**
     * @var string
     */
    private $url;
    /**
     * @var bool
     */
    private $hide_url;
    /**
     * @var string
     */
    private $description;
    /**
     * @var string
     */
    private $thumb_url;
    /**
     * @var int
     */
    private $thumb_width;
    /**
     * @var int
     */
    private $thumb_height;

    /**
     * @param array $data
     * @return Chat
     */
    protected static function _mapFromArray($data)
    {
        $item = parent::_mapFromArray($data);
        if (isset($data["title"])) {
            $item->title = $data["title"];
        }
        if (isset($data["input_message_content"])) {
            if (is_array($data["input_message_content"])) {
                $item->input_message_content  = InputMessageContent::mapFromArray($data["input_message_content"]);
            } elseif ($data["input_message_content"] instanceof InputMessageContent) {
                $item->input_message_content = $data["input_message_content"];
            }
        }
        if (isset($data["url"])) {
            $item->url = $data["url"];
        }
        if (isset($data["hide_url"])) {
            $item->hide_url = $data["hide_url"];
        }
        if (isset($data["description"])) {
            $item->description = $data["description"];
        }
        if (isset($data["thumb_url"])) {
            $item->thumb_url = $data["thumb_url"];
        }
        if (isset($data["thumb_width"])) {
            $item->thumb_width = $data["thumb_width"];
        }
        if (isset($data["thumb_height"])) {
            $item->thumb_height = $data["thumb_height"];
        }
        return $item;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    function jsonSerialize()
    {
        $data = array(
            "id" => $this->id,
            "type" => $this->type,
            "title" => $this->title,
            "input_message_content" => $this->input_message_content,
        );
        if (isset($this->reply_markup)) {
            $data["reply_markup"] = $this->reply_markup;
        }
        if (isset($this->url)) {
            $data["url"] = $this->url;
        }
        if (isset($this->hide_url)) {
            $data["hide_url"] = $this->hide_url;
        }
        if (isset($this->description)) {
            $data["description"] = $this->description;
        }
        if (isset($this->thumb_url)) {
            $data["thumb_url"] = $this->thumb_url;
        }
        if (isset($this->thumb_width)) {
            $data["thumb_width"] = $this->thumb_width;
        }
        if (isset($this->thumb_height)) {
            $data["thumb_height"] = $this->thumb_height;
        }

        return $data;
    }
}