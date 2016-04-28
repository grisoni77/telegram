<?php
/**
 * Project: citybike
 *
 * File: TextLink.php
 * Created by Cristiano Cucco. * E-mail: cristiano.cucco@gmail.com.
 * Date: 21/04/2016
 * Time: 14:15
 */

namespace Gr77\Telegram\Message\Entity;


class TextLink extends MessageEntity
{
    /**
     * Optional. For “text_link” only, url that will be opened after user taps on the text
     * @var string
     */
    private $url;

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return TextLink
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    protected static function _mapFromArray($data, $text)
    {
        $entity = new static();
        $entity
            ->setType($data["type"])
            ->setOffset($data["offset"])
            ->setLength($data["length"])
            ->setUrl($data['url'])
        ;
        $entity->setValue($entity->extractValue($text));
        return $entity;
    }
}