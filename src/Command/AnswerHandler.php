<?php
/**
 * Project: crp_bot
 *
 * File: AnswerHandler.php
 * Created by Cristiano Cucco.
 * E-mail: cristiano.cucco@gmail.com
 * Date: 29/04/2016
 * Time: 09:21
 */

namespace Gr77\Command;


use Gr77\Telegram\Update;

interface AnswerHandler
{
    /**
     * @param Update $update
     * @return bool Returns false to break handlers' chain
     */
    public function handleAnswer(Update $update);
}