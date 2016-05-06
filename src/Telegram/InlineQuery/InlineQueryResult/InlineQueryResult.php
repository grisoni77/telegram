<?php
/**
 * Project: crp_bot
 *
 * File: InlineQueryResult.php
 * Created by Cristiano Cucco.
 * E-mail: cristiano.cucco@gmail.com
 * Date: 26/04/2016
 * Time: 18:34
 */

namespace Gr77\Telegram\InlineQuery\InlineQueryResult;


abstract class InlineQueryResult implements \JsonSerializable
{
    /**
     * Type of the result, must be article
     * @var string
     */
    public $type;
    /**
     * Unique identifier for this result, 1-64 Bytes
     * @var string
     */
    public $id;
    /**
     * Optional. Inline keyboard attached to the message
     * @var \Gr77\Telegram\ReplyMarkup\InlineKeyboardMarkup
     */
    public $reply_markup;
    /**
     * Optional. Content of the message to be sent instead of the contact
     * @var \Gr77\Telegram\InlineQuery\Input\InputMessageContent
     */
    public $input_message_content;

    /**
     * InlineQueryResult constructor.
     * @param string $type
     * @param string $id
     */
    public function __construct($id, $type)
    {
        $this->type = $type;
        $this->id = $id;
    }


    /**
     * @param array $data
     * @return InlineQueryResult
     */
    public static function mapFromArray($data)
    {
        if (isset($data["type"]))
        {
            $separator = '_';
            $className = __NAMESPACE__
                . "\\InlineQueryResult".str_replace($separator, '', implode('',array_map("ucfirst", explode('_', $data["type"]))));
//            $className = __NAMESPACE__."\\".str_replace($separator, '', ucwords($data["type"], $separator));
            if (!class_exists($className)) {
                throw new \InvalidArgumentException("Invalid inline query result type: ".$data["type"], 400);
            }
            return $className::_mapFromArray($data);
        }
        else {
            throw new \InvalidArgumentException("Empty inline query result type", 400);
        }
    }

    /**
     * @param array $data
     * @return InlineQueryResult
     */
    protected static function _mapFromArray($data)
    {
        if (!isset($data["id"]) || !isset($data["type"])) {
            throw new \InvalidArgumentException("Id and type are mandatory fields for InlineQueryResult: ".print_r($data,true), 400);
        }
        $item = new static($data["id"], $data["type"]);
        if (isset($data["reply_markup"])) {
            $item->reply_markup = $data["reply_markup"];
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
    public function jsonSerialize()
    {
        $data = array(
            "id" => $this->id,
            "type" => $this->type,
        );
        if (isset($this->reply_markup)) {
            $data["reply_markup"] = $this->reply_markup->toArray();
        }
        if (isset($this->input_message_content)) {
            $data["input_message_content"] = $this->input_message_content;
        }
        return $data;
    }
}