<?php
/**
 * @copyright Anton Tuyakhov <atuyakhov@gmail.com>
 */

namespace tuyakhov\notifications;


interface HandlerInterface
{
    public function __construct($event, $settings = null);

    public function handle();
}