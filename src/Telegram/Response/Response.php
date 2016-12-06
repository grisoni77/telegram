<?php
/**
 * Project: citybike
 *
 * File: Response.php
 * Created by Cristiano Cucco. * E-mail: cristiano.cucco@gmail.com.
 * Date: 19/04/2016
 * Time: 15:58
 */

namespace Gr77\Telegram\Response;


use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Psr7\Stream;

abstract class Response
{
    /** @var  bool */
    protected $ok;
    /** @var  string human-readable description of the result */
    protected $description;
    /** @var  int */
    protected $error_code;
    /** @var  mixed */
    protected $result;
    /** @var  mixed ResponseParameters object which can help to automatically handle the error */
    protected $parameters;

    /**
     * Response constructor.
     * @param $data Stream
     */
    public function __construct(Stream $stream)
    {
        $data = json_decode((string) $stream, true);

        $this->ok = $data['ok'];
        if (isset($data['description'])) {
            $this->description = $data['description'];
        }
        if (isset($data['result'])) {
            $this->parseResult($data['result']);
        }
        if (isset($data['error_code'])) {
            $this->error_code = $data['error_code'];
        }
        if (isset($data['parameters'])) {
            $this->parameters = $data['parameters'];
        }
    }

    /**
     * Template method to be implemented in concrete classes
     * @param mixed $result result field in telegram response
     */
    abstract protected function parseResult($result);

    /**
     * @return bool
     */
    public function hasError()
    {
        return !$this->isOk();
    }

    /**
     * @return bool
     */
    public function isOk()
    {
        return $this->ok;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return Updates
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @return mixed
     */
    public function getErrorCode()
    {
        return $this->error_code;
    }

    /**
     * @return mixed
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @param mixed $parameters
     * @return Response
     */
    public function setParameters($parameters)
    {
        $this->parameters = $parameters;
        return $this;
    }


    /**
     * @TODO Needs to change the way I manage this...
     *
     * @param BadResponseException $e
     * @return Error|Forbidden
     */
    public static function handleException(BadResponseException $e)
    {
        $e->getResponse()->getBody();
        $code = $e->getResponse()->getStatusCode();
        if ($code == 403) {
            return new Forbidden(\GuzzleHttp\Psr7\stream_for(json_encode([
                "ok" => false,
                "error_code" => 403,
                "result" => $e->getMessage(),
            ])));
        }
        else {
            return new Error(\GuzzleHttp\Psr7\stream_for(json_encode([
                "ok" => false,
                "error_code" => $e->getCode(),
                "description" => $e->getCode()." ".$e->getMessage(),
            ])));
        }
    }
}