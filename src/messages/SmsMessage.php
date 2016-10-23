<?php
/**
 * @link http://www.stombox.com/
 * @copyright Copyright (c) 2015 Stombox LLC
 * @license http://www.stombox.com/license/
 */

namespace tuyakhov\notifications\messages;


class SmsMessage extends AbstractMessage
{
    /**
     * A phone number in E.164 format.
     * @var string
     */
    public $from;

    /**
     * The text of the message you want to send, limited to 1600 characters.
     * @var string
     */
    public $body;

    /**
     * The URL of the media you wish to send out with the message.
     * @var string
     */
    public $mediaUrl;
}