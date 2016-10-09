<?php
/**
 * @copyright Anton Tuyakhov <atuyakhov@gmail.com>
 */

namespace tuyakhov\notifications\messages;


class MailMessage extends AbstractMessage
{
    /**
     * The view to be used for rendering the message body.
     * @var string|array|null $view 
     */
    public $view;

    /**
     * The parameters (name-value pairs) that will be extracted and made available in the view file.
     * @var array
     */
    public $viewData;

    /**
     * The message sender.
     * @var string
     */
    public $from;
}