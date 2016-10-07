<?php
/**
 * Created by PhpStorm.
 * User: cris77
 * Date: 07/10/2016
 * Time: 17:27
 */

namespace Gr77\Telegram;


class WebhookInfo
{
    /**
     * Webhook URL, may be empty if webhook is not set up
     * @var string
     */
    private $url;
    /**
     * True, if a custom certificate was provided for webhook certificate checks
     * @var bool
     */
    private $has_custom_certificate;
    /**
     * Number of updates awaiting delivery
     * @var int
     */
    private $pending_update_count;
    /**
     * Optional. Unix time for the most recent error that happened when trying to deliver an update via webhook
     * @var int
     */
    private $last_error_date;
    /**
     * Optional. Error message in human-readable format for the most recent error that happened when trying to deliver an update via webhook
     * @var string
     */
    private $last_error_message;

    private function __construct()
    {
    }

    public static function mapFromArray($data)
    {
        if (!isset($data['url']) || !isset($data['has_custom_certificate']) || !isset($data['pending_update_count'])) {
            throw new \OutOfBoundsException('Some mandatory parameters are missing');
        }

        $webhookinfo = new self();
        $webhookinfo->url = $data['url'];
        $webhookinfo->has_custom_certificate = $data['has_custom_certificate'];
        $webhookinfo->pending_update_count = $data['pending_update_count'];
        if (isset($data['last_error_date'])) {
            $webhookinfo->last_error_date = $data['last_error_date'];
        }
        if (isset($data['last_error_message'])) {
            $webhookinfo->last_error_message = $data['last_error_message'];
        }
        return $webhookinfo;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return boolean
     */
    public function isHasCustomCertificate()
    {
        return $this->has_custom_certificate;
    }

    /**
     * @return int
     */
    public function getPendingUpdateCount()
    {
        return $this->pending_update_count;
    }

    /**
     * @return int
     */
    public function getLastErrorDate()
    {
        return $this->last_error_date;
    }

    /**
     * @return string
     */
    public function getLastErrorMessage()
    {
        return $this->last_error_message;
    }

}