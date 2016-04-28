<?php
/**
 * Project: citybike
 *
 * File: InlineQuery.php
 * Created by Cristiano Cucco.
 * E-mail: cristiano.cucco@gmail.com
 * Date: 24/04/2016
 * Time: 21:51
 */

namespace Gr77\Telegram\InlineQuery;


use Gr77\Telegram\User;

class InlineQuery
{
    /**
     * Unique identifier for this query
     * @var string
     */
    private $id;
    /**
     * Sender
     * @var \Gr77\Telegram\User
     */
    private $from;
    /**
     * @var \Gr77\Telegram\Message\Content\Location
     */
    private $location;
    /**
     * Text of the query
     * @var string
     */
    private $query;
    /**
     * Offset of the results to be returned, can be controlled by the bot
     * @var string
     */
    private $offset;

    /**
     * InlineQuery constructor.
     * @param string $id
     * @param \Gr77\Telegram\User $from
     * @param string $query
     * @param string $offset
     */
    public function __construct($id, \Gr77\Telegram\User $from, $query, $offset)
    {
        $this->id = $id;
        $this->from = $from;
        $this->query = $query;
        $this->offset = $offset;
    }

    /**
     * @param $data
     * @return InlineQuery
     */
    public static function mapFromArray($data)
    {
        if (!isset($data["id"]) || !isset($data["from"]) || !isset($data["query"]) || !isset($data["offset"])) {
            throw new \InvalidArgumentException("Id, from, query and offset are mandatory fields for InlineQuery", 400);
        }
        $inlineQuery = new self($data["id"], User::mapFromArray($data["from"]), $data["query"], $data["offset"]);
        if (isset($data["location"])) {
//            $inlineQuery->setLocation();
        }
        return $inlineQuery;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return InlineQuery
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return \Gr77\Telegram\User
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param \Gr77\Telegram\User $from
     * @return InlineQuery
     */
    public function setFrom($from)
    {
        $this->from = $from;
        return $this;
    }

    /**
     * @return \Gr77\Telegram\Message\Content\Location
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param \Gr77\Telegram\Message\Content\Location $location
     * @return InlineQuery
     */
    public function setLocation($location)
    {
        $this->location = $location;
        return $this;
    }

    /**
     * @return string
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @param string $query
     * @return InlineQuery
     */
    public function setQuery($query)
    {
        $this->query = $query;
        return $this;
    }

    /**
     * @return string
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * @param string $offset
     * @return InlineQuery
     */
    public function setOffset($offset)
    {
        $this->offset = $offset;
        return $this;
    }


}