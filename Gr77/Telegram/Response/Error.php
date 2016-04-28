<?php
/**
 * Project: citybike
 *
 * File: Error.php
 * Created by Cristiano Cucco. * E-mail: cristiano.cucco@gmail.com.
 * Date: 20/04/2016
 * Time: 16:58
 */

namespace Gr77\Telegram\Response;


class Error extends Response
{

    /**
     * @param $result result field in telegram response
     */
    protected function parseResult($result)
    {
        // niente da parsare
    }
}