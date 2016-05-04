<?php
/**
 * Project: citybike
 *
 * File: InlineQueryResultLocation.php
 * Created by Cristiano Cucco.
 * E-mail: cristiano.cucco@gmail.com
 * Date: 30/04/2016
 * Time: 20:49
 */

namespace Gr77\Telegram\InlineQuery\InlineQueryResult;

/**
 * Class InlineQueryResultLocation
 * Represents a location on a map. By default, the location will be sent by the user. Alternatively,
 * you can use input_message_content to send a message with the specified content instead of the location.
 * @package Gr77\Telegram\InlineQuery\InlineQueryResult
 * @see https://core.telegram.org/bots/api#inlinequeryresultlocation
 */
class InlineQueryResultLocation extends InlineQueryResult
{
    /**
     * Location latitude in degrees
     * @var float
     */
    public $latitude;
    /**
     * Location longitude in degrees
     * @var float
     */
    public $longitude;
    /**
     * Location title
     * @var string
     */
    public $title;


    /**
     * @param array $data
     * @return InlineQueryResultLocation
     */
    protected static function _mapFromArray($data)
    {
        if (!isset($data["title"]) || !isset($data["latitude"]) || !isset($data["longitude"])) {
            throw new \InvalidArgumentException("Invalid data for InlineQueryResultLocation", 400);
        }
        $item = parent::_mapFromArray($data);
        $item->title = $data["title"];
        $item->latitude = $data["latitude"];
        $item->longitude = $data["longitude"];

        return $item;
    }

    public function jsonSerialize()
    {
        $data = parent::jsonSerialize();
        $data["title"] = $this->title;
        $data["latitude"] = $this->latitude;
        $data["longitude"] = $this->longitude;

        return $data;
    }


}