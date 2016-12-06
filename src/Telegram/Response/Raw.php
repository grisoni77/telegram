<?php

namespace Telegram\Response;


use Gr77\Telegram\Response\Response;

class Raw extends Response
{

    /**
     * Template method to be implemented in concrete classes
     * @param mixed $result result field in telegram response
     */
    protected function parseResult($result)
    {
        $this->result = $result;
    }
}