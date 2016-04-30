<?php
/**
 * Project: crp_bot
 *
 * File: InputMessageContent.php
 * Created by Cristiano Cucco.
 * E-mail: cristiano.cucco@gmail.com
 * Date: 26/04/2016
 * Time: 18:41
 */

namespace Gr77\Telegram\InlineQuery\Input;

/**
 * Class InputMessageContent
 * Represents the content of a text message to be sent as the result of an inline query.
 * @package Gr77\Telegram\InlineQuery\Input
 * @see https://core.telegram.org/bots/api#inputmessagecontent
 */
class InputMessageContent implements \JsonSerializable
{
    /**
     * Text of the message to be sent, 1-4096 characters
     * @var string
     */
    public $message_text;
    /**
     * Optional. Send Markdown or HTML, if you want Telegram apps to show bold, italic, fixed-width text or inline URLs
     * in your bot's message.
     * @var string
     */
    public $parse_mode;
    /**
     * Optional. Disables link previews for links in the sent message
     * @var bool
     */
    public $disable_web_page_preview;

    /**
     * InputMessageContent constructor.
     * @param string $message_text
     * @param string $parse_mode
     * @param bool $disable_web_page_preview
     */
    public function __construct($message_text, $parse_mode = null, $disable_web_page_preview = null)
    {
        $this->message_text = $message_text;
        if (isset($parse_mode)) {
            $this->parse_mode = $parse_mode;
        }
        if (isset($disable_web_page_preview)) {
            $this->disable_web_page_preview = $disable_web_page_preview;
        }
    }

    public static function mapFromArray($data)
    {
        $input = new InputMessageContent($data["text"]);
        if (isset($data["parse_mode"])) {
            $input->parse_mode = $data["parse_mode"];
        }
        if (isset($data["disable_web_page_preview"])) {
            $input->disable_web_page_preview = $data["disable_web_page_preview"];
        }
        return $input;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        $data = array("message_text" => $this->message_text);
        if (isset($this->parse_mode)) {
            $data["parse_mode"] = $this->parse_mode;
        }
        if (isset($this->disable_web_page_preview)) {
            $data["disable_web_page_preview"] = $this->disable_web_page_preview;
        }
        return $data;
    }
}