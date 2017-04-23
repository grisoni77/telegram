<?php
/**
 * Project: citybike
 *
 * File: Entity.php
 * Created by Cristiano Cucco. * E-mail: cristiano.cucco@gmail.com.
 * Date: 21/04/2016
 * Time: 14:07
 */

namespace Gr77\Telegram\Message\Entity;

/**
 * Class Entity
 * This object represents one special entity in a text message. For example, hashtags, usernames, URLs, etc.
 * @package Gr77\Telegram\Message\Entity
 * @see https://core.telegram.org/bots/api#messageentity
 */
abstract class MessageEntity
{
    /**
     * Type of the entity. One of mention (@username), hashtag, bot_command, url, email, bold (bold text),
     * italic (italic text), code (monowidth string), pre (monowidth block), text_link (for clickable text URLs)
     * @var string
     */
    private $type;
    /**
     * Offset in UTF-16 code units to the start of the entity
     * @var int
     */
    private $offset;
    /**
     * Length of the entity in UTF-16 code units
     * @var int
     */
    private $length;

    /**
     * Value dell'entity estratta dal testo
     * @var string
     */
    private $value;

    protected function __construct()
    {
    }

    /**
     * @param array $data
     * @param string $text
     * @return MessageEntity
     */
    public static function mapFromArray($data, $text)
    {
        if (isset($data["type"]))
        {
            $separator = '_';
            $className = __NAMESPACE__."\\".str_replace($separator, '', implode('',array_map("ucfirst", explode('_', $data["type"]))));
//            $className = __NAMESPACE__."\\".str_replace($separator, '', ucwords($data["type"], $separator));
            if (!class_exists($className)) {
                throw new \InvalidArgumentException("Invalid MessageEntity type: ".$data["type"], 400);
            }
            return $className::_mapFromArray($data, $text);
        }
        else {
            throw new \InvalidArgumentException("Empty MessageEntity type", 400);
        }
    }

    /**
     * @param array $data
     * @param string $text
     * @return MessageEntity
     */
    protected static function _mapFromArray($data, $text)
    {
        $entity = new static();
        $entity
            ->setType($data["type"])
            ->setOffset($data["offset"])
            ->setLength($data["length"])
        ;
        $entity->setValue($entity->extractValue($text));
        return $entity;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return MessageEntity
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return int
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * @param int $offset
     * @return MessageEntity
     */
    public function setOffset($offset)
    {
        $this->offset = $offset;
        return $this;
    }

    /**
     * @return int
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * @param int $length
     * @return MessageEntity
     */
    public function setLength($length)
    {
        $this->length = $length;
        return $this;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     * @return MessageEntity
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @param $text
     * @return string
     */
    protected function extractValue($text)
    {
        return mb_substr($text, $this->offset, $this->length);
    }
}