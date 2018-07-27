<?php declare(strict_types=1);

namespace FGhazaleh\Exceptions;

class FileNotFoundException extends \InvalidArgumentException
{
    protected $message = 'File not found in provided path.';
}
