<?php
/**
 * Project: citybike
 *
 * File: Location.php
 * Created by Cristiano Cucco.
 * E-mail: cristiano.cucco@gmail.com
 * Date: 24/04/2016
 * Time: 21:53
 */

namespace Gr77\Telegram\Message\Content;


class Location
{
    public $longitude;
    public $latitude;

    /**
     * Location constructor.
     * @param $longitude
     * @param $latitude
     */
    public function __construct($longitude, $latitude)
    {
        $this->longitude = $longitude;
        $this->latitude = $latitude;
    }

    public static function mapFromArray($data)
    {
        if (!isset($data["latitude"]) || !isset($data["longitude"])) {
            throw new \InvalidArgumentException("Invalid arguments for constructor ".__CLASS__, 500);
        }
        return new self($data["latitude"],$data["longitude"]);
    }
}