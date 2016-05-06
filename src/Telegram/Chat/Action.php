<?php
/**
 * Project: crp_bot
 *
 * File: Action.php
 * Created by Cristiano Cucco.
 * E-mail: cristiano.cucco@gmail.com
 * Date: 06/05/2016
 * Time: 17:53
 */

namespace Gr77\Telegram\Chat;

/**
 * Class Action
 * Type of action to broadcast.
 * Choose one, depending on what the user is about to receive: typing for text messages, upload_photo for photos,
 * record_video or upload_video for videos, record_audio or upload_audio for audio files, upload_document for general
 * files, find_location for location data.
 * @package Gr77\Telegram\Chat
 * @see https://core.telegram.org/bots/api#sendchataction
 */
abstract class Action
{
    const TYPING            = "typing";
    const UPLOAD_PHOTO      = "upload_photo";
    const RECORD_VIDEO      = "record_video";
    const UPLOAD_VIDEO      = "upload_video";
    const RECORD_AUDIO      = "record_audio";
    const UPLOAD_AUDIO      = "upload_audio";
    const UPLOAD_DOCUMENT   = "upload_document";
    const FIND_LOCATION     = "find_location";
}