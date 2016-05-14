<?php
/**
 * Project: citybike
 *
 * File: Update.php
 * Created by Cristiano Cucco. * E-mail: cristiano.cucco@gmail.com.
 * Date: 19/04/2016
 * Time: 16:07
 */

namespace Gr77\Telegram;


use Gr77\Telegram\CallbackQuery\CallbackQuery;
use Gr77\Telegram\InlineQuery\ChosenInlineResult;
use Gr77\Telegram\InlineQuery\InlineQuery;
use Gr77\Telegram\Message\Message;

class Update
{
    /** @var  int */
    public $update_id;
    /**
     * Optional. New incoming message of any kind — text, photo, sticker, etc.
     * @var \Gr77\Telegram\Message\Message
     */
    private $message;
    /**
     * Optional. New incoming inline query
     * @var \Gr77\Telegram\InlineQuery\InlineQuery
     */
    private $inline_query;
    /**
     * Optional. The result of an inline query that was chosen by a user and sent to their chat partner.
     * @var \Gr77\Telegram\InlineQuery\ChosenInlineResult
     */
    private $chosen_inline_result;
    /**
     * Optional. New incoming callback query
     * @var \Gr77\Telegram\CallbackQuery\CallbackQuery
     */
    private $callback_query;

    private function __construct()
    {
    }

    public static function mapFromArray($data)
    {
        $update = new self();
        $update->update_id = $data['update_id'];
        if (isset($data['message'])) {
            $update->setMessage(Message::mapFromArray($data['message']));
        }
        if (isset($data['callback_query'])) {
            $update->setCallbackQuery(CallbackQuery::mapFromArray($data['callback_query']));
        }
        if (isset($data['inline_query'])) {
            $update->setInlineQuery(InlineQuery::mapFromArray($data['inline_query']));
        }
        if (isset($data['chosen_inline_result'])) {
            $update->setChosenInlineResult(ChosenInlineResult::mapFromArray($data['chosen_inline_result']));
        }
        return $update;
    }


    /**
     * @return int
     */
    public function getUpdateId()
    {
        return $this->update_id;
    }

    /**
     * @param int $update_id
     * @return Update
     */
    public function setUpdateId($update_id)
    {
        $this->update_id = $update_id;
        return $this;
    }

    /**
     * @return CallbackQuery
     */
    public function getCallbackQuery()
    {
        return $this->callback_query;
    }

    /**
     * @param CallbackQuery $callback_query
     * @return Update
     */
    public function setCallbackQuery($callback_query)
    {
        $this->callback_query = $callback_query;
        return $this;
    }

    /**
     * @return \Gr77\Telegram\InlineQuery\InlineQuery
     */
    public function getInlineQuery()
    {
        return $this->inline_query;
    }

    /**
     * @param \Gr77\Telegram\InlineQuery\InlineQuery $inline_query
     * @return Update
     */
    public function setInlineQuery($inline_query)
    {
        $this->inline_query = $inline_query;
        return $this;
    }

    /**
     * @return \Gr77\Telegram\InlineQuery\ChosenInlineResult
     */
    public function getChosenInlineResult()
    {
        return $this->chosen_inline_result;
    }

    /**
     * @param \Gr77\Telegram\InlineQuery\ChosenInlineResult $chosen_inline_result
     * @return Update
     */
    public function setChosenInlineResult($chosen_inline_result)
    {
        $this->chosen_inline_result = $chosen_inline_result;
        return $this;
    }



    /**
     * @return Message
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param Message $message
     * @return Update
     */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }




    /**
     * @return bool
     */
    public function hasMessage()
    {
        return isset($this->message);
    }

    /**
     * @return bool
     */
    public function hasCallbackQuery()
    {
        return isset($this->callback_query);
    }

    /**
     * @return bool
     */
    public function hasInlineQuery()
    {
        return isset($this->inline_query);
    }

    /**
     * @return bool
     */
    public function hasChosenInlineResult()
    {
        return isset($this->chosen_inline_result);
    }

}