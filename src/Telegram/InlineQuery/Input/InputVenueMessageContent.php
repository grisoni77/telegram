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
 * Class InputVenueMessageContent
 * Represents the content of a venue message to be sent as the result of an inline query.
 * @package Gr77\Telegram\InlineQuery\Input
 * @see https://core.telegram.org/bots/api#inputvenuemessagecontent
 */
class InputVenueMessageContent extends InputLocationMessageContent
{
    /**
     * Location title
     * @var string
     */
    public $title;
    /**
     * Location address
     * @var string
     */
    public $address;

    /**
     * InputMessageContent constructor.
     * @param string $message_text
     * @param string $parse_mode
     * @param bool $disable_web_page_preview
     */
    public function __construct($latitude, $longitude, $title, $address)
    {
        parent::__construct($latitude, $longitude);
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    public static function mapFromArray($data)
    {
        $input = new InputLocationMessageContent($data["latitude"], $data["longitude"], $data["title"], $data["address"]);
        return $input;
    }

}