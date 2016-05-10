<?php
/**
 * Project: crp_bot
 *
 * File: BaseObjectHandler.php
 * Created by Cristiano Cucco.
 * E-mail: cristiano.cucco@gmail.com
 * Date: 06/05/2016
 * Time: 11:13
 */

namespace Gr77\Telegram\Request;


use Gr77\Telegram\BaseObject;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\GraphNavigator;
use JMS\Serializer\JsonSerializationVisitor;
use JMS\Serializer\Context;

class BaseObjectHandler implements  SubscribingHandlerInterface
{

    /**
     * Return format:
     *
     *      array(
     *          array(
     *              'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
     *              'format' => 'json',
     *              'type' => 'DateTime',
     *              'method' => 'serializeDateTimeToJson',
     *          ),
     *      )
     *
     * The direction and method keys can be omitted.
     *
     * @return array
     */
    public static function getSubscribingMethods()
    {
        return array(
            array(
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format' => 'json',
//                'type' => "Gr77\\Telegram\\InlineQuery\\InlineQueryResult\\InlineQueryResultArticle",
                'type' => "JsonSerializable",
                'method' => 'serializeJsonSerializableToJson',
            ),
        );
    }

    public function serializeJsonSerializableToJson(
        JsonSerializationVisitor $visitor,
        \JsonSerializable $obj,
        array $type,
        Context $context
    )
    {
        //return $visitor->startVisitingObject()
        $data = $obj->jsonSerialize();
        if (is_array($data)) {
            return $visitor->visitArray($data, $type, $context);
        }
        elseif(is_int($data)) {
            return $visitor->visitInteger($data, $type, $context);
        }
        elseif(is_string($data)) {
            return $visitor->visitString($data, $type, $context);
        }
        elseif(is_bool($data)) {
            return $visitor->visitBoolean($data, $type, $context);
        }
        elseif(is_null($data)) {
            return $visitor->visitNull($data, $type, $context);
        }
        //return $obj->getProperties();
//        return $date->format($type['params'][0]);
    }
}