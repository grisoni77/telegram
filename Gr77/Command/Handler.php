<?php

namespace Gr77\Command;

use Gr77\Telegram\Client;
use Gr77\Telegram\Update;
use Psr\Log\LoggerInterface;

interface Handler
{
    /**
     * Ritorna un'istanza di questo handler
     * @return Handler
     */
    public static function provide(Client $client, $config = array(), LoggerInterface $logger = null);

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