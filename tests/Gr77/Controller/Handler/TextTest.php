<?php
/**
 * Created by PhpStorm.
 * User: cris77
 * Date: 04/10/2016
 * Time: 16:09
 */

namespace tests\Gr77\Controller;


use Gr77\Telegram\Message\Content\PlainText;
use PHPUnit_Framework_TestCase;
use Gr77\Controller\Chain;
use Gr77\Telegram\Client;
use Gr77\Telegram\Update;
use tests\Gr77\TestCase;

class TextTest extends TestCase
{
    public function testGetTextHandlers()
    {
        $textHandler = new PlainText(array());
        $textHandler->registerRegexpHandler("/Test/i", "MyHandler");
        $textHandler->registerTextHandler("test", "MyHandler2");
        $textHandler->registerRegexpHandler("/Search (.*)/i", "MyHandler3");

        /** @var \ArrayObject $handlers */
        $handlers = $this->invokeMethod($textHandler, "getTextHandlers", array("test"));
        $this->assertNotFalse($handlers);
        $this->assertEquals("MyHandler", $handlers->offsetGet(0));
        $this->assertEquals("MyHandler2", $handlers->offsetGet(1));

        /** @var \ArrayObject $handlers */
        $handlers = $this->invokeMethod($textHandler, "getTextHandlers", array("searcha qualcosa"));
        $this->assertFalse($handlers);

        /** @var \ArrayObject $handlers */
        $handlers = $this->invokeMethod($textHandler, "getTextHandlers", array("search qualcosa ddd"));
        $this->assertEquals("MyHandler3", $handlers->offsetGet(0));
    }


}