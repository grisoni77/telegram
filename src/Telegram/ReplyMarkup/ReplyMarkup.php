<?php
/**
 * Project: citybike
 *
 * File: ReplyMarkup.php
 * Created by Cristiano Cucco. * E-mail: cristiano.cucco@gmail.com.
 * Date: 21/04/2016
 * Time: 10:02
 */

namespace Gr77\Telegram\ReplyMarkup;


abstract class ReplyMarkup extends BaseObject
{
    /**
     * @return array
     */
    abstract public function toArray();
}