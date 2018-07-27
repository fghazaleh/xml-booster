<?php declare(strict_types=1);

namespace FGhazaleh\Exceptions;

class InvalidArgsException extends \InvalidArgumentException
{
    protected $message = 'Invalid or missing argument.';
}
