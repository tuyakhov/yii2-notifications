<?php
/**
 * @copyright Anton Tuyakhov <atuyakhov@gmail.com>
 */
namespace tuyakhov\notifications\messages;


abstract class AbstractMessage implements MessageInterface
{
    protected $notification;

    public function __construct($notification)
    {
        
    }
}