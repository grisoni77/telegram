<?php
/**
 * Project: crp
 *
 * File: PhotoSize.php
 * Created by Cristiano Cucco.
 * E-mail: cristiano.cucco@gmail.com
 * Date: 13/05/2016
 * Time: 17:58
 */

namespace Gr77\Telegram\Message\Content;


use Gr77\Telegram\BaseObject;

/**
 * Class PhotoSize
 * This object represents one size of a photo or a file / sticker thumbnail.
 * @package Gr77\Telegram\Message\Content
 * @see https://core.telegram.org/bots/api#photosize
 */
class PhotoSize extends BaseObject implements \JsonSerializable
{
    /**
     * Unique identifier for this file

     * @var string
     */
    private $file_id;
    /**
     * Photo width
     * @var int
     */
    private $width;
    /**
     * Photo height
     * @var int
     */
    private $height;
    /**
     * Optional. File size
     * @var int
     */
    private $file_size;

    public function __construct($file_id, $width, $height, $file_size = null)
    {
        $this->file_id = $file_id;
        $this->width = $width;
        $this->height = $height;
        if (isset($file_size)) {
            $this->file_size = $file_size;
        }
    }

    public static function mapFromArray($data)
    {
        if (!isset($data["file_id"]) || !isset($data["width"]) || !isset($data["height"])) {
            throw new \InvalidArgumentException("Invalid arguments for constructor ".__CLASS__, 500);
        }
        if (isset($data["file_size"])) {
            return new self($data["file_id"],$data["width"],$data["height"],$data["file_size"]);
        } else {
            return new self($data["file_id"],$data["width"],$data["height"]);
        }
    }

    /**
     * @return string
     */
    public function getFileId()
    {
        return $this->file_id;
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @return int
     */
    public function getFileSize()
    {
        return $this->file_size;
    }


    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    function jsonSerialize()
    {
        $data = array(
            "file_id" => $this->file_id,
            "width" => $this->width,
            "height" => $this->height,
        );
        if (isset($this->file_size)) {
            $data["file_size"] = $this->file_size;
        }
        return $data;
    }
}