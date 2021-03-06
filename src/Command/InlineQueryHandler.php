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

interface InlineQueryHandler
{
    /**
     * @param Update $update
     * @return bool Ritorna false per interrompere la catena di handlers
     */
    public function handleInlineQuery(Update $update);
}