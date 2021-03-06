<?php
/**
 * Created by PhpStorm.
 * User: cris77
 * Date: 06/12/2016
 * Time: 14:33
 */

namespace Gr77\Telegram\Exception;

use Exception;
use Gr77\Telegram\Response\Response;

/**
 * Class TelegramException
 * @package Telegram\Exception
 */
class TelegramException extends \Exception
{
    /** @var  Response */
    private $response;

    /**
     * @param $res Response
     * @return TelegramException
     */
    public static function throwUnsuccessfullRequest($res)
    {
        $error_code = (int) $res->getErrorCode();
        $error_code = isset($error_code) ? $error_code : 400;
        $exception = new self($res->getDescription(), $error_code);
        $exception->setResponse($res);
        return $exception;
    }

    /**
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param Response $response
     * @return TelegramException
     */
    public function setResponse($response)
    {
        $this->response = $response;
        return $this;
    }


}