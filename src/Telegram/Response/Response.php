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

abstract class Response
{
    /** @var  bool */
    protected $ok;
    /** @var  string */
    protected $description;
    /** @var  int */
    protected $error_code;

    /**
     * Response constructor.
     * @param $data array
     */
    public function __construct($data)
    {
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
    }

    /**
     * @param $result result field in telegram response
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

    public static function handleException(BadResponseException $e)
    {
        $code = $e->getResponse()->getStatusCode();
        if ($code == 403) {
            return new Forbidden(array(
                "ok" => false,
                "error_code" => 403,
                "result" => $e->getMessage(),
            ));
        }
        else {
            return new Error(array(
                "ok" => false,
                "error_code" => $e->getCode(),
                "description" => $e->getCode()." ".$e->getMessage(),
            ));
        }
    }
}