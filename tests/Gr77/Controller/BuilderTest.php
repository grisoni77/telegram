<?php
/**
 * Created by PhpStorm.
 * User: cris77
 * Date: 24/01/2017
 * Time: 10:32
 */

namespace tests\Gr77\Controller;


use Gr77\Controller\Builder;
use Gr77\Controller\Chain;
use Gr77\Telegram\Client;
use tests\Gr77\TestCase;

class BuilderTest extends TestCase
{
    private $features = [
        'Command', 'CallbackQuery', 'ChosenInlineResult', 'InlineQuery', 'Intent', 'Location',
        'RepeatedUpdates', 'Text', 'WaitingAnswer',
    ];

    public function testAddFeatures()
    {
        $builder = new Builder();
        $builder
            ->addCommand()->addCallbackQuery()->addChosenInlineResult()
            ->addInlineQuery()->addIntent()->addLocation()
            ->addRepeatedUpdates()->addText()->addWaitingAnswer();

        foreach ($this->features as $feature) {
            $this->assertTrue($builder->hasFeature($feature));
        }
    }

    public function testAddOnlyOneFeature()
    {
        $builder = new Builder();
        $builder->addCommand();

        foreach ($this->features as $feature) {
            if ($feature==='Command') {
                $this->assertTrue($builder->hasFeature($feature));
            } else {
                $this->assertFalse($builder->hasFeature($feature));
            }
        }
    }

    public function testAddAllFeatures()
    {
        $builder = new Builder();
        $builder->addAllFeatures();

        foreach ($this->features as $feature) {
            $this->assertTrue($builder->hasFeature($feature));
        }
    }

    public function testAddWrongFeature()
    {
        $this->expectException(\BadMethodCallException::class);

        $builder = new Builder();
        $builder->addNotAFeature();
    }

    public function testCannotBuildInvalidController()
    {
        $this->expectException(\RuntimeException::class);
        $builder = new Builder();
        $builder->build();
    }

    public function testCannotBuildEmptyFeaturesController()
    {
        $this->expectException(\RuntimeException::class);
        $builder = new Builder();
        $builder->setName('test')->setClient($this->createMock(Client::class))->setConfig([]);
        $builder->build();
    }


    public function testCanBuildValidController()
    {
        $builder = new Builder();
        $builder->setName('test')->setClient($this->createMock(Client::class))->setConfig([]);
        $builder->addCommand();
        $controller = $builder->build();

        $this->assertInstanceOf(Chain::class, $controller);
    }

}