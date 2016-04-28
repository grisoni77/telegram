<?php
/**
 * Project: citybike
 *
 * File: Base.php
 * Created by Cristiano Cucco. * E-mail: cristiano.cucco@gmail.com.
 * Date: 20/04/2016
 * Time: 16:36
 */

namespace Gr77\Command;

use Gr77\Telegram\Client;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

abstract class Base implements Handler
{
    /** @var \Gr77\Telegram\Client  */
    protected $client;
    /** @var  \Psr\Log\LoggerInterface */
    protected $logger;
    /** @var  array config bot */
    protected $config;

    /**
     * Base constructor.
     * @param Client $client
     * @param LoggerInterface|null $logger
     * @param array $config
     */
    public function __construct(Client $client, $config = array(), LoggerInterface $logger = null)
    {
        $this->client = $client;
        if (null == $logger) {
            $this->logger = new NullLogger();
        } else {
            $this->logger = $logger;
        }
        $this->config = $config;
    }

    /**
     * @param Client $client
     * @param LoggerInterface|null $logger
     * @param array $config
     * @return static
     */
    public static function provide(Client $client, $config = array(), LoggerInterface $logger = null)
    {
        $handler = new static($client, $config, $logger);
        return $handler;
    }

    /**
     * @return string
     */
    public static function getClassName() {
        return get_called_class();
    }
}