<?php
/**
 * Created by PhpStorm.
 * User: cris77
 * Date: 13/12/2016
 * Time: 12:17
 */

namespace Gr77\Telegram\Message\Content;


interface Text extends \JsonSerializable
{
    /**
     * @return string
     */
    public function getText();

    /**
     * @return null|string
     */
    public function getParseMode();
}