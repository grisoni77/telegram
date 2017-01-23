# PHP Telegram client
[![Build Status](https://travis-ci.org/grisoni77/telegram.svg?branch=master)](https://travis-ci.org/grisoni77/telegram)

Usage example:

    $token = <bot_token>;
    $config_telegram = array(
      'apiurl' => 'https://api.telegram.org'
    );
    $httpClient = new \Guzzle\Http\Client();
    $client = new \Gr77\Telegram\Client($config_telegram, $httpClient);
    
    // create controller and register handlers
    $controller = new \Gr77\Controller\Controller($token, $client);
    $controller->registerCommandHandler("\start", "\\MyHandlers\\Start");
    
    // handle update from Telegram Servers
    // $body is raw json sent by telegram servers through update
    $decodedBody = json_decode($body, true);
    $update = \Gr77\Telegram\Update::mapFromArray($decodedBody);
    $res = $controller->handleUpdate($update);


Command Handler example:

     namespace MyHandlers;
     
     use Gr77\Command\Base;
     use Gr77\Telegram\Message\Content\PlainText;
     use Gr77\Telegram\Update;
     
     class Start extends Base
     {
         /**
          * @param Update $update
          * @return bool returns false to break handlers chain, true to run next handlers
          */
         public function __invoke(Update $update)
         {
             $chat_id = $update->getMessage()->chat['id'];
             $text = new PlainText("Some welcome text");
             $res = $this->client->sendMessage($chat_id, $text);
             
             return $res->isOk();
         }
     }
