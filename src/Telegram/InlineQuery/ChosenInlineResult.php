<?php
/**
 * Project: citybike
 *
 * File: ChosenInlineResult.php
 * Created by Cristiano Cucco.
 * E-mail: cristiano.cucco@gmail.com
 * Date: 03/05/2016
 * Time: 22:48
 */

namespace Gr77\Telegram\InlineQuery;

use Gr77\Telegram\Message\Content\Location;
use Gr77\Telegram\User;

/**
 * Class ChosenInlineResult
 * Represents a result of an inline query that was chosen by the user and sent to their chat partner.
 * @package Gr77\Telegram\InlineQuery
 * @see https://core.telegram.org/bots/api#choseninlineresult
 */
class ChosenInlineResult
{
    /**
     * The unique identifier for the result that was chosen
     * @var String
     */
    public $result_id;
    /**
     * The user that chose the result
     * @var \Gr77\Telegram\User
     */
    public $from;
    /**
     * Optional. Sender location, only for bots that require user location
     * @var \Gr77\Telegram\Message\Content\Location
     */
    public $location;
    /**
     * Optional. Identifier of the sent inline message.
     * Available only if there is an inline keyboard attached to the message.
     * Will be also received in callback queries and can be used to edit the message.
     * @var String
     */
    public $inline_message_id;
    /**
     * The query that was used to obtain the result
     * @var String
     */
    public $query;

    /**
     * ChosenInlineResult constructor.
     * @param string $result_id
     * @param User $from
     * @param string $query
     */
    public function __construct($result_id, User $from, $query)
    {
        $this->result_id = $result_id;
        $this->from = $from;
        $this->query = $query;
    }

    public static function mapFromArray($data)
    {
        if (!isset($data["result_id"]) || !isset($data["from"]) || !isset($data["query"])) {
            throw new \InvalidArgumentException("Invalid arguments for constructor ".__CLASS__, 500);
        }
        $item = new self($data["result_id"], User::mapFromArray($data["from"]), $data["query"]);
        if (isset($data["location"])) {
            $item->location = Location::mapFromArray($data["location"]);
        }
        if (isset($data["inline_message_id"])) {
            $item->inline_item_id;
        }
        return $item;
    }

    /**
     * @return String
     */
    public function getResultId()
    {
        return $this->result_id;
    }

    /**
     * @return User
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @return Location
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @return String
     */
    public function getInlineMessageId()
    {
        return $this->inline_message_id;
    }

    /**
     * @return String
     */
    public function getQuery()
    {
        return $this->query;
    }



}