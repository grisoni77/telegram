<?php

namespace Gr77\Command;

use Gr77\Telegram\Client;
use Gr77\Telegram\Update;

interface Handler
{
    /**
     * Ritorna un'istanza di questo handler
     * @return Handler
     */
    public static function provide(Client $client, $logger = null, $config = array());

    /**
     * @param Update $update
     * @return bool Ritorna false per interrompere la catena di handlers
     */
    public function __invoke(Update $update);

    /**
     * @return string
     */
    public static function getClassName();
}