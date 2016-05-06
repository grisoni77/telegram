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
    public $title;
    /**
     * Content of the message to be sent
     * @var \Gr77\Telegram\InlineQuery\Input\InputMessageContent
     */
    public $input_message_content;

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
        return array(
            "id" => $this->id,
            "type" => $this->type,
            "title" => $this->title,
            "input_message_content" => $this->input_message_content,
        );
    }
}