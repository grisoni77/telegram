<?php
/**
 * Created by PhpStorm.
 * User: cris77
 * Date: 04/10/2016
 * Time: 16:09
 */

namespace tests\Gr77\Controller;


use Gr77\Controller\Handler\Text;
use Gr77\Controller\Chain;
use Gr77\Telegram\Client;
use Gr77\Telegram\Update;
use tests\Gr77\TestCase;

class ChainTest extends TestCase
{
    public function testHandleUpdate()
    {
        $config = [
            "session_type" => "null"
        ];
        $httpClient = $this->getMockBuilder(\Guzzle\Http\Client::class)->getMock();
        $serializer = $this->getMockBuilder(\Gr77\Telegram\Request\NativeSerializer::class)->getMock();
        $client = $this->getMockBuilder(Client::class)
            ->setConstructorArgs(array($config,$httpClient, $serializer))
            ->getMock();
        $chain = new Chain("chainName", $client, $config);
//        $chain = $this->getMockBuilder(Chain::class)
//            ->setConstructorArgs(array("token", $client))
//            ->getMock();

        //$textHandler = new Text(array());
        $textHandler = $this->getMockBuilder(Text::class)
            ->setConstructorArgs(array())
            ->getMock();
        $textHandler->registerRegexpHandler("/Search (.*)/i", "MyHandler3");

        $chain->addHandler($textHandler);

        $res = '{"update_id":655079445, "message":{"message_id":190,"from":{"id":121262313,"first_name":"Cristiano","last_name":"Cucco","username":"Grisoni77"},"chat":{"id":121262313,"first_name":"Cristiano","last_name":"Cucco","username":"Grisoni77","type":"private"},"date":1462382522,"text":"Search Emanuele"}}';
        $update = Update::mapFromArray(json_decode($res, true));

        $textHandler->expects($this->once())
            ->method("handleUpdate")
            ->with($update)
            ;

        $chain->handle($update);
    }


}