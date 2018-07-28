<?php
/**
 * @copyright Anton Tuyakhov <atuyakhov@gmail.com>
 */

namespace tuyakhov\notifications\messages;


class SlackMessage extends AbstractMessage
{
    /**
     * @var array optional arguments
     * @see https://api.slack.com/methods/chat.postMessage
     * Example:
     * [
     *     'attachments' => [
     *          ['pretext' => 'pre-hello', 'text" => 'text-world']
     *     ]
     * ]
     */
    public $arguments = [];
}