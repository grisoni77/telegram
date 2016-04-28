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


abstract class InlineQueryResult
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
            throw new \InvalidArgumentException("Id and type are mandatory fields for InlineQueryResult", 400);
        }
        $item = new static($data["id"], $data["type"]);
        return $item;
    }
}