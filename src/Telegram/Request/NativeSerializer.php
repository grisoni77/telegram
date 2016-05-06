<?php
/**
 * Project: citybike
 *
 * File: NativeSerializer.php
 * Created by Cristiano Cucco.
 * E-mail: cristiano.cucco@gmail.com
 * Date: 06/05/2016
 * Time: 09:44
 */

namespace Gr77\Telegram\Request;


class NativeSerializer implements Serializer
{
    /**
     * @param array|object $data
     * @return mixed
     */
    public function toJson($data)
    {
        return json_encode($data);
    }

}