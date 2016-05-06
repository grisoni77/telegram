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
        return $visitor->visitArray($obj->jsonSerialize(), $type, $context);
        //return $obj->getProperties();
//        return $date->format($type['params'][0]);
    }
}