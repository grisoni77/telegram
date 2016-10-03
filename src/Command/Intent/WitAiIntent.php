<?php
/**
 * Project: citybike
 *
 * File: WitAiIntent.php
 * Created by Cristiano Cucco.
 * E-mail: cristiano.cucco@gmail.com
 * Date: 13/06/2016
 * Time: 00:11
 */

namespace Gr77\Command\Intent;


class WitAiIntent implements Intent
{
    /**
     * @var string
     */
    private $type;
    /**
     * @var array
     */
    private $message;

    /**
     * WitAiIntent constructor.
     * @param string $type
     * @param array $message
     */
    public function __construct($type, $message)
    {
        $this->type = $type;
        $this->message = $message;
    }

    /**
     * @return array
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

}