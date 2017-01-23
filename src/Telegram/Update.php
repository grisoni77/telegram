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
    /**
     * Optional. New version of a message that is known to the bot and was edited
     * @var \Gr77\Telegram\Message\Message
     */
    private $edited_message;
    /**
     * Optional. New incoming channel post of any kind — text, photo, sticker, etc.
     * @var \Gr77\Telegram\Message\Message
     */
    private $channel_post;
    /**
     * Optional. New version of a channel post that is known to the bot and was edited
     * @var \Gr77\Telegram\Message\Message
     */
    private $edited_channel_post;


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
        if (isset($data['edited_message'])) {
            $update->setEditedMessage(Message::mapFromArray($data['edited_message']));
        }
        if (isset($data['channel_post'])) {
            $update->setChannelPost(Message::mapFromArray($data['channel_post']));
        }
        if (isset($data['edited_channel_post'])) {
            $update->setEditedChannelPost(Message::mapFromArray($data['edited_channel_post']));
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


    public function getChatId()
    {
        if ($this->hasMessage()) {
            return $this->getMessage()->getChat()->getId();
        } elseif ($this->hasCallbackQuery()) {
            return $this->getCallbackQuery()->getMessage()->getChat()->getId();
        } elseif ($this->hasInlineQuery()) {
            return  $this->getInlineQuery()->getFrom()->getId();
        } elseif ($this->hasChosenInlineResult()) {
            return  $this->getChosenInlineResult()->getFrom()->getId();
        } elseif ($this->hasChannelPost()) {
            return  $this->getChannelPost()->getChat()->getId();
        } elseif ($this->hasEditedMessage()) {
            return  $this->getEditedMessage()->getChat()->getId();
        } elseif ($this->hasEditedChannelPost()) {
            return  $this->getEditedChannelPost()->getChat()->getId();
        } else {
            return false;
        }
    }

    /**
     * @return Message
     */
    public function getEditedMessage()
    {
        return $this->edited_message;
    }

    /**
     * @param Message $edited_message
     * @return Update
     */
    public function setEditedMessage($edited_message)
    {
        $this->edited_message = $edited_message;
        return $this;
    }

    /**
     * @return Message
     */
    public function getChannelPost()
    {
        return $this->channel_post;
    }

    /**
     * @param Message $channel_post
     * @return Update
     */
    public function setChannelPost($channel_post)
    {
        $this->channel_post = $channel_post;
        return $this;
    }

    /**
     * @return Message
     */
    public function getEditedChannelPost()
    {
        return $this->edited_channel_post;
    }

    /**
     * @param Message $edited_channel_post
     * @return Update
     */
    public function setEditedChannelPost($edited_channel_post)
    {
        $this->edited_channel_post = $edited_channel_post;
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

    /**
     * @return bool
     */
    public function hasChannelPost()
    {
        return isset($this->channel_post);
    }

    /**
     * @return bool
     */
    public function hasEditedChannelPost()
    {
        return isset($this->edited_channel_post);
    }

    /**
     * @return bool
     */
    public function hasEditedMessage()
    {
        return isset($this->edited_message);
    }



}