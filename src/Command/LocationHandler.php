<?php
/**
 * Project: crp_bot
 *
 * File: CommandHandler.php
 * Created by Cristiano Cucco.
 * E-mail: cristiano.cucco@gmail.com
 * Date: 29/04/2016
 * Time: 09:21
 */

namespace Gr77\Command;


use Gr77\Telegram\Update;

interface LocationHandler
{
    /**
     * @param Update $update
     * @return bool returns false to break handlers' chain
     */
    public function handleLocation(Update $update);
}