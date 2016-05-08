<?php
/**
 * Project: citybike
 *
 * File: KeyboardButtonRow.php
 * Created by Cristiano Cucco. * E-mail: cristiano.cucco@gmail.com.
 * Date: 20/04/2016
 * Time: 18:20
 */

namespace Gr77\Telegram\ReplyMarkup;


class KeyboardButtonRow extends \ArrayObject
{

    /**
     * @param KeyboardButton $button
     * @return KeyboardButtonRow
     */
    public function appendButton(KeyboardButton $button)
    {
        $this->append($button);
        return $this;
    }

    /**
     * @param KeyboardButton $button
     * @return KeyboardButtonRow
     */
    public function prependButton(KeyboardButton $button)
    {
        $this->prepend($button);
        return $this;
    }

    /**
     * @param string $text
     * @return KeyboardButtonRow
     */
    public function appendTextButton($text)
    {
        $this->appendButton(new KeyboardButton($text));
        return $this;
    }

    /**
     * @param string $text
     * @return KeyboardButtonRow
     */
    public function prependTextButton($text)
    {
        $this->prependButton(new KeyboardButton($text));
        return $this;
    }

    /**
     * @param string $text
     * @return KeyboardButtonRow
     */
    public function appendContactButton($text)
    {
        $this->appendButton(new KeyboardButton($text, true, false));
        return $this;
    }

    /**
     * @param string $text
     * @return KeyboardButtonRow
     */
    public function prependContactButton($text)
    {
        $this->prependButton(new KeyboardButton($text, true, false));
        return $this;
    }

    /**
     * @param string $text
     * @return KeyboardButtonRow
     */
    public function appendLocationButton($text)
    {
        $this->appendButton(new KeyboardButton($text, false, true));
        return $this;
    }

    /**
     * @param string $text
     * @return KeyboardButtonRow
     */
    public function prependLocationButton($text)
    {
        $this->prependButton(new KeyboardButton($text, false, true));
        return $this;
    }


}