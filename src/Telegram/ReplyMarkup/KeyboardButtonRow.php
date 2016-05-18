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


class KeyboardButtonRow extends \ArrayObject implements \JsonSerializable
{

    /**
     * @param KeyboardButton $button
     */
    public function appendButton(KeyboardButton $button)
    {
        $this->append($button);
    }

    /**
     * @param KeyboardButton $button
     */
    public function prependButton(KeyboardButton $button)
    {
        $this->prepend($button);
    }

    /**
     * @return mixed
     */
    function jsonSerialize()
    {
        $data = array();
        foreach ($this->getArrayCopy() as $item) {
            $data[] = $item;
        }
        return $data;
    }


}