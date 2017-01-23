<?php
/**
 * Project: crp_bot
 *
 * File: ControllerTest.php
 * Created by Cristiano Cucco.
 * E-mail: cristiano.cucco@gmail.com
 * Date: 29/04/2016
 * Time: 15:47
 */

namespace tests\Gr77\Controller;


use Gr77\Controller\Controller;
use Gr77\Telegram\Client;
use Gr77\Telegram\Update;
use tests\Gr77\TestCase;

class ControllerTest extends TestCase
{
    public function testGetTextHandlers()
    {
        $config = [
            "session_type" => "null",
            "token" => "xcvxcvxcbxcvb",
        ];
        //$httpClient = $this->getMockBuilder(\Guzzle\Http\Client::class)->getMock();
        //$serializer = $this->getMockBuilder(\Gr77\Telegram\Request\NativeSerializer::class)->getMock();
        $client = $this->getMockBuilder(Client::class)
            ->setConstructorArgs(array($config))
            ->getMock();
        $controller = new Controller("token", $client);

        $controller->registerRegexpHandler("/Agenda/i", "MyHandler");
        $controller->registerTextHandler("Agenda", "MyHandler2");
        $controller->registerRegexpHandler("/Search (.*)/i", "MyHandler3");

        /** @var \ArrayObject $handlers */
        $handlers = $this->invokeMethod($controller, "getTextHandlers", array("agenda"));
        $this->assertNotFalse($handlers);
        $this->assertEquals("MyHandler", $handlers->offsetGet(0));
        $this->assertEquals("MyHandler2", $handlers->offsetGet(1));

        /** @var \ArrayObject $handlers */
        $handlers = $this->invokeMethod($controller, "getTextHandlers", array("searcha qualcosa"));
        $this->assertFalse($handlers);

        /** @var \ArrayObject $handlers */
        $handlers = $this->invokeMethod($controller, "getTextHandlers", array("search qualcosa ddd"));
        $this->assertEquals("MyHandler3", $handlers->offsetGet(0));
    }

//    public function testCallHandleLocation()
//    {
//        //$httpClient = $this->getMockBuilder(\Guzzle\Http\Client::class)->getMock();
//        $serializer = $this->getMockBuilder(\Gr77\Telegram\Request\NativeSerializer::class)->getMock();
//        $client = $this->getMockBuilder(Client::class)
//            ->setConstructorArgs(array(array(), $serializer))
//            ->getMock();
//        //$controller = new Controller("token", $client);
//        $controller = $this->getMockBuilder(Controller::class)
//            ->setConstructorArgs(array("token", $client))
//            ->getMock();
//
//        $res = '{"update_id":655079745, "message":{"message_id":851,"from":{"id":121262313,"first_name":"Cristiano","last_name":"Cucco","username":"Grisoni77"},"chat":{"id":121262313,"first_name":"Cristiano","last_name":"Cucco","username":"Grisoni77","type":"private"},"date":1462896686,"location":{"latitude":44.912887,"longitude":8.035178}}}';
//        $update = Update::mapFromArray(json_decode($res, true));
//
//        $controller->expects($this->once())
//            ->method("handleLocation")
//            ->with($update)
//        ;
//        $controller->handleUpdate($update);
//    }


}
