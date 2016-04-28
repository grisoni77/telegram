<?php

namespace Gr77\Telegram\Response;


use Gr77\Telegram\Update;

class Updates extends Response
{
    /** @var  Updates */
    protected $updates;

    /**
     * @return Updates
     */
    public function getUpdates()
    {
        return $this->updates;
    }

    /**
     * @param $result result field in telegram response
     */
    protected function parseResult($result)
    {
        $this->updates = new \Gr77\Telegram\Updates();
        foreach ($result as $update) {
            $this->updates->append(Update::mapFromArray($update));
        }
    }

}