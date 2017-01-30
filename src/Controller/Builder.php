<?php
/**
 * Created by PhpStorm.
 * User: cris77
 * Date: 24/01/2017
 * Time: 09:50
 */

namespace Gr77\Controller;


use Gr77\Telegram\Client;
use Psr\Log\LoggerInterface;

/**
 * Class ChainBuilder
 * @package Gr77\Controller
 * @method Builder addRepeatedUpdates() add RepeatedUpdates handler
 * @method Builder addWaitingAnswer() add WaitingAnswer handler
 * @method Builder addLocation() add Location handler
 * @method Builder addCommand() add Command handler
 * @method Builder addText() add Text handler
 * @method Builder addIntent() add Intent handler
 * @method Builder addCallbackQuery() add CallbackQuery handler
 * @method Builder addInlineQuery() add InlineQuery handler
 * @method Builder addChosenInlineResult() add ChosenInlineResult handler
 */
class Builder
{
    private $allowedFeatures = [
        'Command', 'CallbackQuery', 'ChosenInlineResult', 'InlineQuery', 'Intent', 'Location',
        'RepeatedUpdates', 'Text', 'WaitingAnswer',
    ];

    /** @var array */
    private $features = [
        'all' => false
    ];
    /** @var  string */
    private $name;
    /** @var  LoggerInterface */
    private $logger;
    /** @var  Client */
    private $client;
    /** @var  array */
    private $config;

    /**
     * @param string $name
     * @return Builder
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param LoggerInterface $logger
     * @return Builder
     */
    public function setLogger($logger)
    {
        $this->logger = $logger;
        return $this;
    }

    /**
     * @param Client $client
     * @return Builder
     */
    public function setClient(Client $client)
    {
        $this->client = $client;
        return $this;
    }

    /**
     * @param array $config
     * @return Builder
     */
    public function setConfig($config)
    {
        $this->config = $config;
        return $this;
    }

    public function addFeature($feature, $params = null)
    {
        $this->features[$feature] = [];
    }
    /**
     * Add all possible features
     * @return $this
     */
    public function addAllFeatures()
    {
        $this->features['all'] = true;
        return $this;
    }

    /**
     * @param $feature
     * @return bool
     */
    public function hasFeature($feature) {
        return array_key_exists($feature, $this->features) || $this->hasAllFeatures();
    }

    /**
     * @return bool
     */
    public function hasAllFeatures() {
        return array_key_exists('all', $this->features) && $this->features['all'];
    }

    /**
     * @param $name
     * @param $arguments
     * @return $this
     */
    public function __call($name, $arguments)
    {
        if (preg_match('/^add(.*)$/', $name, $matches)) {
            $feature = $matches[1];
            if (in_array($feature, $this->allowedFeatures)) {
                $this->addFeature($feature);
            } elseif ($feature === 'all') {
                $this->addAllFeatures();
            } else {
                throw new \BadMethodCallException("Feature does not exist: ".$feature);
            }
            return $this;
        }
    }

    private function log($msg, $level = 'debug')
    {
        if (isset($this->logger)) {
            $this->logger->log($level, $msg);
        }
    }

    private function validate()
    {
        if (!isset($this->client) || !$this->client instanceof Client) {
            $this->log('BUILDER: invalid client');
            return false;
        }
        if (!isset($this->config)) {
            $this->log('BUILDER: invalid config');
            return false;
        }
        if (!$this->hasAllFeatures() && count($this->features) === 1) {
            $this->log('BUILDER: empty features');
            return false;
        }

        return true;
    }


    /**
     * Build chain object
     * @return Chain
     */
    public function build()
    {
        if (!$this->validate()) {
            throw new \RuntimeException('Invalid configuration');
        }

        $chain = new Chain($this->name, $this->client, $this->config, $this->logger);

        $all_features = $this->hasAllFeatures();

        if ($all_features || $this->hasFeature('RepeatedUpdates')) {
            $chain->addHandler(new \Gr77\Controller\Handler\RepeatedUpdates());
        }
        if ($all_features || $this->hasFeature('WaitingAnswer')) {
            $chain->addHandler(new \Gr77\Controller\Handler\WaitingAnswer());
        }
        if ($all_features || $this->hasFeature('Location')) {
            $chain->addHandler(new \Gr77\Controller\Handler\Location($this->config));
        }
        if ($all_features || $this->hasFeature('Command')) {
            $chain->addHandler(new \Gr77\Controller\Handler\Command($this->config));
        }
        if ($all_features || $this->hasFeature('Text')) {
            $chain->addHandler(new \Gr77\Controller\Handler\Text($this->config));
        }
        if ($all_features || $this->hasFeature('Intent')) {
            $chain->addHandler(new \Gr77\Controller\Handler\Intent($this->config));
        }
        if ($all_features || $this->hasFeature('CallbackQuery')) {
            $chain->addHandler(new \Gr77\Controller\Handler\CallbackQuery($this->config));
        }
        if ($all_features || $this->hasFeature('InlineQuery')) {
            $chain->addHandler(new \Gr77\Controller\Handler\InlineQuery($this->config));
        }
        if ($all_features || $this->hasFeature('ChosenInlineResult')) {
            $chain->addHandler(new \Gr77\Controller\Handler\ChosenInlineResult($this->config));
        }

        return $chain;
    }

}