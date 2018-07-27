<?php declare(strict_types=1);

namespace FGhazaleh\Exceptions;

class XmlParserException extends \RuntimeException
{
    protected $message = 'Unable to parser the current xml.';
}
