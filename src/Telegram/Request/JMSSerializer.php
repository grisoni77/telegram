<?php
/**
 * Project: citybike
 *
 * File: JMSSerializer.php
 * Created by Cristiano Cucco.
 * E-mail: cristiano.cucco@gmail.com
 * Date: 06/05/2016
 * Time: 09:32
 */

namespace Gr77\Telegram\Request;


use JMS\Serializer\SerializationContext;

class JMSSerializer implements Serializer
{
    /** @var \JMS\Serializer\Serializer  */
    private $serializer;

    public function __construct(\JMS\Serializer\Serializer $serializer)
    {
        $this->serializer = $serializer;
    }

    public function toJson($data)
    {
        $context = new SerializationContext();
        $context->setSerializeNull(false);
        return $this->serializer->serialize($data, "json", $context);
    }

}