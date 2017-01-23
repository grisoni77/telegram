<?php
/**
 * Created by PhpStorm.
 * User: cris77
 * Date: 07/10/2016
 * Time: 13:38
 */

namespace tests\Gr77\Controller\Handler;


use Gr77\Controller\Handler\Intent;
use tests\Gr77\TestCase;

class IntentTest extends TestCase
{
    public function testGetIntentHandlers()
    {
        $handler = new Intent([
            'config_bot' => [
                'wit_ai_secret' => 'secret',
                ]
        ]);

        $handler->registerIntentHandler('intent1', '\\MyHandler');
        $handler->registerIntentHandler('intent1', '\\MyHandler2');
        $handler->registerIntentHandler('intent2', '\\MyHandler3');

        $handlers = $this->invokeMethod($handler, "getIntentHandlers", array("intent"));
        $this->assertFalse($handlers);

        $handlers = $this->invokeMethod($handler, "getIntentHandlers", array("intent1"));
        $this->assertCount(2, $handlers);
        $this->assertContains('\\MyHandler', $handlers);
        $this->assertContains('\\MyHandler2', $handlers);

        $handlers = $this->invokeMethod($handler, "getIntentHandlers", array("intent2"));
        $this->assertCount(1, $handlers);
        $this->assertContains('\\MyHandler3', $handlers);

    }
}