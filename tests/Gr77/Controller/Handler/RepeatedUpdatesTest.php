<?php
/**
 * Created by PhpStorm.
 * User: cris77
 * Date: 23/01/2017
 * Time: 14:52
 */

namespace tests\Gr77\Controller\Handler;


use Gr77\Controller\Handler\RepeatedUpdates;
use Gr77\Controller\Handler\Text;
use Gr77\Session\InMemorySession;
use Gr77\Telegram\Client;
use Gr77\Telegram\Update;
use tests\Gr77\TestCase;

class RepeatedUpdatesTest extends TestCase
{
    private $update1, $update2, $client, $session, $successor;

    public function setUp()
    {
        $config = [
            "session_type" => "in-memory",
            "token" => "xcvxcvxcbxcvb",
        ];
        //$httpClient = $this->getMockBuilder(\Guzzle\Http\Client::class)->getMock();
        //$serializer = $this->getMockBuilder(\Gr77\Telegram\Request\NativeSerializer::class)->getMock();
        $this->client = $this->getMockBuilder(Client::class)
            ->setConstructorArgs(array($config))
            ->getMock();
        $this->session = new InMemorySession(mt_rand());

        $this->update1 = Update::mapFromArray([
            'update_id' => 1,
        ]);
        $this->update2 = Update::mapFromArray([
            'update_id' => 2,
        ]);
    }

    public function testPermitsNotRepeatedUpdate()
    {
        $handler = new RepeatedUpdates();
        $successor = $this->createMock(Text::class);
        $handler->setSuccessor($successor);

        $successor->expects($this->exactly(2))
            ->method("handleUpdate")
            ->withConsecutive([$this->equalTo($this->update1)], [$this->equalTo($this->update2)]);

        $handler->handleUpdate($this->update1, $this->client, $this->session);
        $handler->handleUpdate($this->update2, $this->client, $this->session);
    }

    public function testBlocksRepeatedUpdate()
    {
        $handler = new RepeatedUpdates();
        $successor = $this->createMock(Text::class);
        $handler->setSuccessor($successor);

        $successor->expects($this->once())
            ->method("handleUpdate")
            ->with($this->update1);

        $handler->handleUpdate($this->update1, $this->client, $this->session);
        $handler->handleUpdate($this->update1, $this->client, $this->session);
    }

}