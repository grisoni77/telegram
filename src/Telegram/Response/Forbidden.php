<?php
/**
 * Project: telegram
 *
 * File: Forbidden.php
 * Created by Cristiano Cucco.
 * E-mail: cristiano.cucco@gmail.com
 * Date: 11/05/2016
 * Time: 17:07
 */

namespace Gr77\Telegram\Response;


use GuzzleHttp\Psr7\Stream;

class Forbidden extends Response
{
    /**
     * @var string
     */
    private $response;

    public function __construct(Stream $stream)
    {
        parent::__construct($stream);

        if (!isset($this->description) && empty($this->description)) {
            $this->description = "Forbidden..user probably stopped this bot";
        }
    }

    /**
     * @param $result result field in telegram response
     */
    protected function parseResult($result)
    {
        $this->response = $result;
    }

    /**
     * @return string
     */
    public function getResponse()
    {
        return $this->response;
    }
}