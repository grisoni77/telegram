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

class ControllerTest extends \PHPUnit_Framework_TestCase
{
    public function testGetTextHandlers()
    {
        $httpClient = $this->getMockBuilder(\Guzzle\Http\Client::class)->getMock();
        $client = $this->getMockBuilder(Client::class)
            ->setConstructorArgs(array(array(),$httpClient))
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


    /**
     * Call protected/private method of a class.
     *
     * @param object &$object    Instantiated object that we will run method on.
     * @param string $methodName Method name to call
     * @param array  $parameters Array of parameters to pass into method.
     *
     * @return mixed Method return.
     */
    public function invokeMethod(&$object, $methodName, array $parameters = array())
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }
}
