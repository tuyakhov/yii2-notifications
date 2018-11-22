<?php
/**
 * @copyright Anton Tuyakhov <atuyakhov@gmail.com>
 */

namespace tuyakhov\notifications\messages;


class DatabaseMessage extends AbstractMessage
{
    /**
     * @var array additional data
     * Example:
     * [
     *     'data' => [
     *          'actionUrl' => ['href' => '/invoice/123/pay', 'label' => 'Pay Invoice']
     *     ]
     * ]
     */
    public $data = [];
}