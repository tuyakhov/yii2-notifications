<?php
/**
 * @copyright Anton Tuyakhov <atuyakhov@gmail.com>
 */

namespace tuyakhov\notifications\messages;

/**
 * Represents a text message to be sent by a Telegram bot.
 */
class TelegramMessage extends AbstractMessage
{
    /**
     * If the message is a reply, ID of the original message
     * @var integer|string
     */
    public $replyToMessageId;

    /**
     * Additional interface options.
     * An object for an inline keyboard, custom reply keyboard,
     * instructions to remove reply keyboard or to force a reply from the user.
     * Example:
     * [
     *    "inline_keyboard" => [
     *       [
     *          ["text" => "View invoice", "url" => "http://site.com/invoice/123"],
     *          ["text" => "Pay invoice", "url" => "http://site.com/invoice/123/pay"],
     *       ]
     *    ]
     * ]
     * @var array
     */
    public $replyMarkup;

    /**
     * Sends the message silently. Users will receive a notification with no sound.
     * @var bool
     */
    public $silentMode = false;

    /**
     * Disables link previews for links in this message
     * @var bool
     */
    public $withoutPagePreview = false;
}