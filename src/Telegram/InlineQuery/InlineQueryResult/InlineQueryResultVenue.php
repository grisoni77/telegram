<?php
/**
 * Project: citybike
 *
 * File: InlineQueryResultVenue.php
 * Created by Cristiano Cucco.
 * E-mail: cristiano.cucco@gmail.com
 * Date: 30/04/2016
 * Time: 20:49
 */

namespace Gr77\Telegram\InlineQuery\InlineQueryResult;

/**
 * Class InlineQueryResultVenue
 * Represents a venue. By default, the venue will be sent by the user. Alternatively, you can use input_message_content
 * to send a message with the specified content instead of the venue.
 * @package Gr77\Telegram\InlineQuery\InlineQueryResult
 * @see https://core.telegram.org/bots/api#inlinequeryresultvenue
 */
class InlineQueryResultVenue extends InlineQueryResultLocation
{

    /**
     * Location address
     * @var string
     */
    public $address;


    /**
     * @param array $data
     * @return InlineQueryResultVenue
     */
    protected static function _mapFromArray($data)
    {
        if (!isset($data["address"])) {
            throw new \InvalidArgumentException("Invalid data for InlineQueryResultVenue", 400);
        }
        $item = parent::_mapFromArray($data);
        $item->address = $data["address"];

        return $item;
    }

    public function jsonSerialize()
    {
        $data = parent::jsonSerialize();
        $data["address"] = $this->address;

        return $data;
    }
}