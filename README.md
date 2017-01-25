# PHP Telegram client
[![Build Status](https://travis-ci.org/grisoni77/telegram.svg?branch=master)](https://travis-ci.org/grisoni77/telegram)

Usage example:

    $config = [
        'botBaseUrl' => '<your_bot_endpoint>',
        'token'      => 'your_bot_token',
        'config_bot' => [
            // whatever you need to config for your bot handlers
        ]
    ];

    // set client and logger
    $client = new \Gr77\Telegram\Client($config);
    $monolog = new Monolog\Logger('prod');

    // set chain of update handlers
    $builder = new \Gr77\Controller\Builder();
    $chain = $builder
        ->setName('chain')
        ->setClient($client)
        ->setConfig($client)
        ->setLogger($monolog)
        ->addAllFeatures()
        ->build()
    ;

    // handle update from Telegram Servers
    // $body is raw json sent by telegram servers through update
    $body = file_get_contents('php://input');
    $decodedBody = json_decode($body, true);
    $update = \Gr77\Telegram\Update::mapFromArray($decodedBody);

    // finally handle telegram update
    $chain->handle($update);


Command Handler example:

     namespace MyHandlers;
     
     use Gr77\Command\Base;
     use Gr77\Command\CommandHandler;
     use Gr77\Telegram\Message\Content\PlainText;
     use Gr77\Telegram\Update;
     
     class Start extends Base implements CommandHandler
     {
         /**
          * @param Update $update
          * @return bool Returns false to stop handlers chain
          */
         public function handleCommand(Update $update)
         {
             $chat_id = $update->getMessage()->getChat()->getId();
             $text = new PlainText("Thanks for using this bot!");
             $res = $this->client->sendMessage($chat_id, $text);
             return $res->isOk();
         }
     }
