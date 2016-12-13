<?php
/**
 * Created by PhpStorm.
 * User: cris77
 * Date: 13/12/2016
 * Time: 11:55
 */

namespace Gr77\Telegram\Message\Content;


use Gr77\Telegram\BaseObject;

class LocaleText extends BaseObject implements \JsonSerializable
{
    protected $text;
    protected $language;

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    function jsonSerialize()
    {
        // TODO: Implement jsonSerialize() method.
    }
}