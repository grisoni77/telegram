<?php
/**
 * Project: citybike
 *
 * File: Serializer.php
 * Created by Cristiano Cucco.
 * E-mail: cristiano.cucco@gmail.com
 * Date: 06/05/2016
 * Time: 09:30
 */

namespace Gr77\Telegram\Request;


interface Serializer
{
    /**
     * @param object|array $data
     * @return string
     */
    public function toJson($data);
}