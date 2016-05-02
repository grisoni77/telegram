<?php
/**
 * Project: crp_bot
 *
 * File: InputMessageContent.php
 * Created by Cristiano Cucco.
 * E-mail: cristiano.cucco@gmail.com
 * Date: 26/04/2016
 * Time: 18:41
 */

namespace Gr77\Telegram\InlineQuery\Input;

/**
 * Class InputLocationMessageContent
 * Represents the content of a location message to be sent as the result of an inline query.
 * @package Gr77\Telegram\InlineQuery\Input
 * @see https://core.telegram.org/bots/api#inputlocationmessagecontent
 */
class InputLocationMessageContent
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
     * InputMessageContent constructor.
     * @param string $message_text
     * @param string $parse_mode
     * @param bool $disable_web_page_preview
     */
    public function __construct($latitude, $longitude)
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    public static function mapFromArray($data)
    {
        $input = new InputLocationMessageContent($data["latitude"], $data["longitude"]);
        return $input;
    }

}