<?php
/**
 * Project: citybike
 *
 * File: User.php
 * Created by Cristiano Cucco.
 * E-mail: cristiano.cucco@gmail.com
 * Date: 22/04/2016
 * Time: 17:41
 */

namespace Gr77\Telegram;

/**
 * Class User
 * This object represents a Telegram user or bot.
 * @package Gr77\Telegram
 * @see https://core.telegram.org/bots/api#user
 */
class User
{
    /**
     * Unique identifier for this user or bot
     * @var int
     */
    public $id;
    /**
     * User‘s or bot’s first name
     * @var string
     */
    public $first_name;
    /**
     * Optional. User‘s or bot’s last name
     * @var string
     */
    public $last_name;
    /**
     * Optional. User‘s or bot’s username
     * @var string
     */
    public $username;

    /**
     * User constructor.
     * @param int $id
     * @param string $first_name
     * @param string $last_name
     * @param string $username
     */
    public function __construct($id, $first_name, $last_name = null, $username = null)
    {
        $this->id = $id;
        $this->first_name = $first_name;
        if (isset($last_name)) {
            $this->last_name = $last_name;
        }
        if (isset($username)) {
            $this->username = $username;
        }
    }

    /**
     * @param array $data
     * @return User
     */
    public static function mapFromArray($data)
    {
        if (!isset($data["id"]) || !isset($data["first_name"])) {
            throw new \InvalidArgumentException("Id and first_name are mandatory fields for User", 400);
        }
        $user = new self($data["id"], $data["first_name"]);
        if (isset($data["last_name"])) {
            $user->last_name = $data["last_name"];
        }
        if (isset($data["username"])) {
            $user->username = $data["username"];
        }
        return $user;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return User
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * @param string $first_name
     * @return User
     */
    public function setFirstName($first_name)
    {
        $this->first_name = $first_name;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * @param string $last_name
     * @return User
     */
    public function setLastName($last_name)
    {
        $this->last_name = $last_name;
        return $this;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

}