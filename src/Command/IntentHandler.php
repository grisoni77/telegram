<?php
/**
 * Project: citybike
 *
 * File: IntentHandler.php
 * Created by Cristiano Cucco.
 * E-mail: cristiano.cucco@gmail.com
 * Date: 13/06/2016
 * Time: 00:10
 */

namespace Gr77\Command;


use Gr77\Command\Intent\Intent;
use Gr77\Telegram\Update;

interface IntentHandler
{
    /**
     * @param Update $update
     * @return bool Ritorna false per interrompere la catena di handlers
     */
    public function handleIntent(Update $update, Intent $intent);
}