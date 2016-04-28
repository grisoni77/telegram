<?php
/**
 * Project: citybike
 *
 * File: KeyboardButton.php
 * Created by Cristiano Cucco. * E-mail: cristiano.cucco@gmail.com.
 * Date: 20/04/2016
 * Time: 18:17
 */

namespace Gr77\Telegram\ReplyMarkup;

/**
 * Class KeyboardButton
 * This object represents one button of the reply keyboard. For simple text buttons String can be used instead of this
 * object to specify text of the button. Optional fields are mutually exclusive.
 *
 * @package Gr77\Telegram\ReplyMarkup
 */
class KeyboardButton
{
    /**
     * Text of the button. If none of the optional fields are used, it will be sent to the bot as a message when the button is pressed
     * @var string
     */
    public $text;
    /**
     * Optional. If True, the user's phone number will be sent as a contact when the button is pressed. Available in private chats only
     * @var bool
     */
    public $request_contact;
    /**
     * Optional. If True, the user's current location will be sent when the button is pressed. Available in private chats only
     * @var bool
     */
    public $request_location;

    /**
     * KeyboardButton constructor.
     * @param string $text
     * @param bool $request_contact
     * @param bool $request_location
     */
    public function __construct($text, $request_contact = false, $request_location = false)
    {
        $this->text = $text;
        $this->request_contact = $request_contact;
        $this->request_location = $request_location;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $text
     * @return KeyboardButton
     */
    public function setText($text)
    {
        $this->text = $text;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isRequestContact()
    {
        return $this->request_contact;
    }

    /**
     * @param boolean $request_contact
     * @return KeyboardButton
     */
    public function setRequestContact($request_contact)
    {
        $this->request_contact = $request_contact;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isRequestLocation()
    {
        return $this->request_location;
    }

    /**
     * @param boolean $request_location
     * @return KeyboardButton
     */
    public function setRequestLocation($request_location)
    {
        $this->request_location = $request_location;
        return $this;
    }

}