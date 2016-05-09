<?php
/**
 * Project: crp_bot
 *
 * File: Boolean.php
 * Created by Cristiano Cucco.
 * E-mail: cristiano.cucco@gmail.com
 * Date: 09/05/2016
 * Time: 14:08
 */

namespace Gr77\Telegram\Response;


class Boolean extends Response
{
    protected $result;

    /**
     * @param $result result field in telegram response
     */
    protected function parseResult($result)
    {
        $this->result = (bool) $result;
    }
}