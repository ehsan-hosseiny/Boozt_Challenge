<?php


namespace App\Core\exception;


class NotFoundException extends \Exception
{
    protected $message = 'Page not found';
    protected $code = 404;

}