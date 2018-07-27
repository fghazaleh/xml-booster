<?php declare(strict_types=1);

namespace FGhazaleh\Commands;

use FGhazaleh\Configuration\ConfigurationInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;

class BaseCommand extends Command
{

    protected function getLogger():LoggerInterface
    {
        return $this->getApplication()->getLogger();
    }

    protected function getConfig():ConfigurationInterface
    {
        return $this->getApplication()->getConfig();
    }
}
