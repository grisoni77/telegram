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


use Gr77\Telegram\BaseObject;

class Location extends BaseObject implements \JsonSerializable
{
    private $longitude;
    private $latitude;

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

    /**
     * @return mixed
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @return mixed
     */
    public function getLatitude()
    {
        return $this->latitude;
    }


    /**
     * @return mixed
     */
    function jsonSerialize()
    {
        return array(
            "latitude" => $this->latitude,
            "longitude" => $this->longitude,
        );
    }
}