<?php

namespace Gr77\Command;

use Gr77\Session\Session;
use Gr77\Telegram\Client;
use Gr77\Telegram\Update;
use Psr\Log\LoggerInterface;

interface Handler
{
    /**
     * Returns Handler concrete instance
     * @param Client $client
     * @param Session $session
     * @param array $config
     * @param LoggerInterface|null $logger
     * @return Handler
     */
    public static function provide(Client $client, Session $session, $config = array(), LoggerInterface $logger = null);

//    /**
//     * @param Update $update
//     * @return bool Ritorna false per interrompere la catena di handlers
//     */
//    public function __invoke(Update $update);

    /**
     * @return string
     */
    public static function getClassName();
}