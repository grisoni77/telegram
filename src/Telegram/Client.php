<?php
namespace Gr77\Telegram;

use Gr77\Telegram\Message\Content\InputFile;
use Gr77\Telegram\Message\Content\Location;
use Gr77\Telegram\Message\Content\Text;
use Gr77\Telegram\ReplyMarkup\ReplyMarkup;
use Gr77\Telegram\Request\Serializer;
use Gr77\Telegram\Response\Boolean;
use Gr77\Telegram\Response\Error;
use Gr77\Telegram\Response\Forbidden;
use Gr77\Telegram\Response\Message;
use Gr77\Telegram\Response\Response;
use Gr77\Telegram\Response\Updates;
use Guzzle\Http\Exception\BadResponseException;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class Client
{
    /** @var \Guzzle\Http\Client  */
    protected $httpClient;
    /** @var  array */
    protected $config;
    /** @var  string */
    protected $token;
    /** @var  string */
    protected $apiUrl;
    /** @var Serializer  */
    protected $serializer;
    /** @var \Psr\Log\LoggerInterface  */
    protected $logger;

    /**
     * @param string $token
     */
    public function setToken($token)
    {
        $this->token = $token;
        $this->apiUrl = $this->config['apiurl'].'/bot'.$token.'/';
        $this->httpClient->setBaseUrl($this->apiUrl);

    }

    public function __construct($config, \Guzzle\Http\Client $httpClient, Serializer $serializer, LoggerInterface $logger = null)
    {
        $this->config = $config;
        $this->httpClient = $httpClient;
        $this->serializer = $serializer;
        if (isset($logger)) {
            $this->logger = $logger;
        } else {
            $this->logger = new NullLogger();
        }
    }

    /**
     * @return \Guzzle\Http\Client
     */
    public function getHttpClient()
    {
        return $this->httpClient;
    }

    /**
     * @return NullLogger
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }


    /**
     * @param $body
     * @return string
     */
    private function toJson($body)
    {
        $encodedBody = $this->serializer->toJson($body);
        $this->logger->debug($encodedBody);
        echo $encodedBody;
        return $encodedBody;
    }

    /**
     * @param $token
     * @return array|bool|float|int|string
     */
    public function setWebhook($token)
    {
        try {
            $this->setToken($token);
            $res = $this->httpClient
                ->post('setWebhook', null, array(
                    'url' => $this->config['botBaseUrl'] . '/webhook/' . $token
                ))
                ->send()
                ->json()
            ;
            return $res['description'];
        } catch (BadResponseException $e) {
            echo $e->getMessage();
//            print_r($e->getResponse());
        }
    }

    /**
     * @param $token
     * @return array|bool|float|int|string
     */
    public function removeWebhook($token)
    {
        try {
            $this->setToken($token);
            $res = $this->httpClient
                ->post('setWebhook', null, array(
                    'url' => ''
                ))
                ->send()
                ->json()
            ;
            return $res['description'];
        } catch (BadResponseException $e) {
            echo $e->getMessage();
//            print_r($e->getResponse());
        }
    }

    /**
     * @param array $params
     * @return Error|Updates
     */
    public function getUpdates($params = array())
    {
        try {
            $res = $this->httpClient
                ->post('getUpdates', null, $params)
                ->send()
                ->json()
            ;
            if ($res['ok']) {
                return new Updates($res);
            } else {
                return new Error($res);
            }
            //return print_r($response->getResult(), true);
        } catch (BadResponseException $e) {
            echo $e->getMessage();
//            print_r($e->getResponse());
        }
    }


    /**
     * Use this method when you need to tell the user that something is happening on the bot's side.
     * The status is set for 5 seconds or less (when a message arrives from your bot, Telegram clients clear its typing status).
     *
     * @param int|string $chat_id Unique identifier for the target chat or username of the target channel (in the format @channelusername)
     * @param string $action Type of action to broadcast. See constants defined in Gr77\Telegram\Chat\Action class
     * @return Message
     * @see https://core.telegram.org/bots/api#sendchataction
     */
    public function sendChatAction($chat_id, $action)
    {
        try {
            $body = array(
                "chat_id" => $chat_id,
                "action" => $action,
            );
            $request = $this->httpClient->post('sendChatAction');
            $request->setHeader('Content-Type', 'application/json');
            $request->setBody($this->toJson($body));
            $res = $request->send()->json();
            return new Boolean($res);
        } catch (BadResponseException $e) {
            //echo $e->getMessage();
//            print_r($e->getResponse());
            $this->logger->error($e->getRequest()->getBody());
            $this->logger->error($e->getResponse()->getBody());
        }
    }

    /**
     * @param int $chat_id
     * @param Text|null $text
     * @param string|null $parse_mode Send Markdown or HTML, if you want Telegram apps to show bold, italic, fixed-width text or inline URLs in your bot's message.
     * @param bool|false $disable_web_page_preview Disables link previews for links in this message
     * @param bool|false $disable_notification Sends the message silently.
     * @param null $reply_to_message_id If the message is a reply, ID of the original message
     * @param ReplyMarkup|null $reply_markup
     * @return Message
     * @see https://core.telegram.org/bots/api#sendmessage
     */
    public function sendMessage(
        $chat_id,
        Text $text = null,
        $parse_mode = null,
        $disable_web_page_preview = null,
        $disable_notification = null,
        $reply_to_message_id = null,
        ReplyMarkup $reply_markup = null
    )
    {
        try {
            $body = array(
                "chat_id" => $chat_id,
                //"myField" => "prova",
            );
            if (isset($text)) {
                $body["text"] = $text;
            }
            if (isset($parse_mode)) {
                $body["parse_mode"] = $parse_mode;
            } elseif (isset($text)) {
                $body["parse_mode"] = $text->getParseMode();
            }
            if (isset($disable_web_page_preview)) {
                $body["disable_web_page_preview"] = $disable_web_page_preview;
            }
            if (isset($disable_notification)) {
                $body["disable_notification"] = $disable_notification;
            }
            if (isset($reply_to_message_id)) {
                $body["reply_to_message_id"] = $reply_to_message_id;
            }
            if (isset($reply_markup)) {
                $body["reply_markup"] = $reply_markup->toArray();
            }
            $request = $this->httpClient->post('sendMessage');
            $request->setHeader('Content-Type', 'application/json');
            $request->setBody($this->toJson($body));
            $res = $request->send()->json();
            return new Message($res);
        } catch (BadResponseException $e) {
            echo $e->getMessage();
//            print_r($e->getResponse());
            $this->logger->error($e->getRequest()->getBody());
            $this->logger->error($e->getResponse()->getBody());
            return Response::handleException($e);
        }
    }


    /**
     * @param $chat_id
     * @param Text $text
     * @param ReplyMarkup $keyboard
     * @return Message
     */
    public function sendKeyboard($chat_id, Text $text, ReplyMarkup $keyboard)
    {
        return $this->sendMessage($chat_id, $text, null, null, null, null, $keyboard);
    }


    /**
     * Use this method to send information about a venue. On success, the sent Message is returned.
     * @param $chat_id
     * @param Location $location
     * @param $title
     * @param $address
     * @param $foursquare_id
     * @param $disable_notification
     * @param $reply_to_message_id
     * @param $reply_markup
     * @return Message
     * @see https://core.telegram.org/bots/api#sendvenue
     */
    public function sendVenue(
        $chat_id,
        Location $location,
        $title,
        $address,
        $foursquare_id = null,
        $disable_notification = null,
        $reply_to_message_id = null,
        $reply_markup = null
    )
    {
        try {
            $body = array(
                "chat_id" => $chat_id,
                "latitude" => $location->getLatitude(),
                "longitude" => $location->getLongitude(),
                "title" => $title,
                "address" => $address,
            );
            if (isset($foursquare_id)) {
                $body["foursquare_id"] = $foursquare_id;
            }
            if (isset($disable_notification)) {
                $body["disable_notification"] = $disable_notification;
            }
            if (isset($reply_to_message_id)) {
                $body["reply_to_message_id"] = $reply_to_message_id;
            }
            if (isset($reply_markup)) {
                $body["reply_markup"] = $reply_markup->toArray();
            }
            $request = $this->httpClient->post('sendVenue');
            $request->setHeader('Content-Type', 'application/json');
            $request->setBody($this->toJson($body));
            $res = $request->send()->json();
            return new Message($res);
        } catch (BadResponseException $e) {
            echo $e->getMessage();
//            print_r($e->getResponse());
            $this->logger->error($e->getRequest()->getBody());
            $this->logger->error($e->getResponse()->getBody());
            return Response::handleException($e);
        }
    }

    /**
     * Use this method to send photos. On success, the sent Message is returned.
     * @param int|string $chat_id Unique identifier for the target chat or username of the target channel (in the format @channelusername)
     * @param InputFile|string $photo Photo to send. You can either pass a file_id as String to resend a photo that is already on the Telegram servers, or upload a new photo using multipart/form-data.
     * @param string $caption Optional. Photo caption (may also be used when resending photos by file_id), 0-200 characters
     * @param $disable_notification Optional. Sends the message silently. iOS users will not receive a notification, Android users will receive a notification with no sound.
     * @param $reply_to_message_id Optional. If the message is a reply, ID of the original message
     * @param $reply_markup Optional. Additional interface options. A JSON-serialized object for an inline keyboard, custom reply keyboard, instructions to hide reply keyboard or to force a reply from the user.
     * @return Message
     * @see https://core.telegram.org/bots/api#sendphoto
     */
    public function sendPhoto(
        $chat_id,
        $photo,
        $caption = null,
        $disable_notification = null,
        $reply_to_message_id = null,
        $reply_markup = null
    )
    {
        try {
            $body = array(
                "chat_id" => $chat_id,
            );
            if (isset($caption)) {
                $body["caption"] = $caption;
            }
            if (isset($disable_notification)) {
                $body["disable_notification"] = $disable_notification;
            }
            if (isset($reply_to_message_id)) {
                $body["reply_to_message_id"] = $reply_to_message_id;
            }
            if (isset($reply_markup)) {
                $body["reply_markup"] = $reply_markup->toArray();
            }
            if ($photo instanceof InputFile) {
//                $request = $this->httpClient->post('sendPhoto');
//                $request
//                    ->addPostFiles(array("photo" => $photo->getData()))
//                    ->addPostFields(json_decode(json_encode($body), true));
                $bot_url    = $this->httpClient->getBaseUrl();
                $ch = curl_init($bot_url.'sendPhoto');
//                $cfile = new \CURLFile($photo->getFileName());
                $data = array(
                    'chat_id' => $chat_id,
//                    'photo' => $cfile ,
                    'photo' => '@' . realpath($photo->getData()) . ';filename='.$photo->getData(),
                    'caption' => 'testing'
                );
                curl_setopt($ch, CURLOPT_POST,1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $res = curl_exec($ch);
                $res = json_decode($res, true);
            } else {
                $request = $this->httpClient->post('sendPhoto');
                $body["photo"] = $photo;
                $request->setHeader('Content-Type', 'application/json');
                $request->setBody($this->toJson($body));
                $res = $request->send()->json();
            }
            return new Message($res);
        } catch (BadResponseException $e) {
            echo $e->getMessage();
//            print_r($e->getResponse());
            $this->logger->error($e->getRequest()->getBody());
            $this->logger->error($e->getResponse()->getBody());
            return Response::handleException($e);
        }
    }

    /**
     * Use this method to send answers to callback queries sent from inline keyboards. The answer will be displayed to
     * the user as a notification at the top of the chat screen or as an alert. On success, True is returned.
     *
     * @param $callback_query_id Unique identifier for the query to be answered
     * @param string|null $text Text of the notification. If not specified, nothing will be shown to the user
     * @param null $show_alert If true, an alert will be shown by the client instead of a notification at the top of the chat screen. Defaults to false.
     * @return Message
     * @see https://core.telegram.org/bots/api#answercallbackquery
     */
    public function answerCallbackQuery(
        $callback_query_id,
        $text = null,
        $show_alert = null
    )
    {
        try {
            $body = array(
                "callback_query_id" => $callback_query_id,
            );
            if (isset($text)) {
                $body["text"] = $text;
            }
            if (isset($show_alert)) {
                $body["show_alert"] = $show_alert;
            }
            $request = $this->httpClient->post('answerCallbackQuery');
            $request->setHeader('Content-Type', 'application/json');
            $request->setBody($this->toJson($body));
            $res = $request->send()->json();
            if ($res['ok']) {
                return new Boolean($res);
            } else {
                return new Error($res);
            }
        } catch (BadResponseException $e) {
            echo $e->getMessage();
//            print_r($e->getResponse());
            $this->logger->error($e->getRequest()->getBody());
            $this->logger->error($e->getResponse()->getBody());
            return Response::handleException($e);
        }
    }

    /**
     * Use this method to edit text messages sent by the bot or via the bot (for inline bots). On success, if edited message
     * is sent by the bot, the edited Message is returned, otherwise True is returned.
     *
     * @param Text|null $text                   New text of the message
     * @param $chat_id                          Required if inline_message_id is not specified. Unique identifier for the target chat or username of the target channel (in the format @channelusername)
     * @param $message_id                       Required if inline_message_id is not specified. Unique identifier of the sent message
     * @param $inline_message_id                Required if chat_id and message_id are not specified. Identifier of the inline message
     * @param null $parse_mode                  Send Markdown or HTML, if you want Telegram apps to show bold, italic, fixed-width text or inline URLs in your bot's message.
     * @param null $disable_web_page_preview    Disables link previews for links in this message
     * @param ReplyMarkup|null $reply_markup    A JSON-serialized object for an inline keyboard.
     */
    public function editMessageText(
        Text $text = null,
        $chat_id = null,
        $message_id = null,
        $inline_message_id = null,
        $parse_mode = null,
        $disable_web_page_preview = null,
        ReplyMarkup $reply_markup = null
    )
    {
        try {
            $body = array(
                "text" => $text,
                //"myField" => "prova",
            );
            if (isset($chat_id)) {
                $body["chat_id"] = $chat_id;
            }
            if (isset($message_id)) {
                $body["message_id"] = $message_id;
            }
            if (isset($inline_message_id)) {
                $body["inline_message_id"] = $inline_message_id;
            }
            if (isset($parse_mode)) {
                $body["parse_mode"] = $parse_mode;
            } else {
                $body["parse_mode"] = $text->getParseMode();
            }
            if (isset($disable_web_page_preview)) {
                $body["disable_web_page_preview"] = $disable_web_page_preview;
            }
            if (isset($reply_markup)) {
                $body["reply_markup"] = $reply_markup->toArray();
            }
            $request = $this->httpClient->post('editMessageText');
            $request->setHeader('Content-Type', 'application/json');
            $request->setBody($this->toJson($body));
            $res = $request->send()->json();
            return new Message($res);
        } catch (BadResponseException $e) {
            echo $e->getMessage();
//            print_r($e->getResponse());
            $this->logger->error($e->getRequest()->getBody());
            $this->logger->error($e->getResponse()->getBody());
            return Response::handleException($e);
        }
    }


    /**
     * @param string $inline_query_id Unique identifier for the answered query
     * @param \Gr77\Telegram\InlineQuery\InlineQueryResult[] $results
     * @param int $cache_time
     * @param bool $is_personal
     * @param string $next_offset
     * @param string $switch_pm_text
     * @param string $switch_pm_parameter
     */
    public function answerInlineQuery(
        $inline_query_id,
        $results,
        $cache_time = null,
        $is_personal = null,
        $next_offset = null,
        $switch_pm_text = null,
        $switch_pm_parameter = null
    )
    {
        try {
            $body = array(
                "inline_query_id" => $inline_query_id,
                "results" => $results,
                //"myField" => "prova",
            );
            if (isset($cache_time)) {
                $body["cache_time"] = $cache_time;
            }
            if (isset($is_personal)) {
                $body["is_personal"] = $is_personal;
            }
            if (isset($next_offset)) {
                $body["next_offset"] = $next_offset;
            }
            if (isset($switch_pm_text)) {
                $body["switch_pm_text"] = $switch_pm_text;
            }
            if (isset($switch_pm_parameter)) {
                $body["switch_pm_parameter"] = $switch_pm_parameter;
            }
            $request = $this->httpClient->post('answerInlineQuery');
            $request->setHeader('Content-Type', 'application/json');
            $request->setBody($this->toJson($body));
            $res = $request->send()->json();
            if ($res['ok']) {
                return new Boolean($res);
            } else {
                return new Error($res);
            }
        } catch (BadResponseException $e) {
            echo $e->getMessage();
//            print_r($e->getResponse());
            $this->logger->error($e->getRequest()->getBody());
            $this->logger->error($e->getResponse()->getBody());
            return Response::handleException($e);
        }
    }

}