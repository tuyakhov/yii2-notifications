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

    /**
     * Attach files from local file system, array contain file path
     * Example:
     * [
     *     'full/path/to/file1.jpg',
     *     'full/path/to/file2.pdf',
     * ]
     * @var array
     */
    public $attachFiles;
}