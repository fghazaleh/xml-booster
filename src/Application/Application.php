<?php declare(strict_types=1);

namespace FGhazaleh\Application;

use FGhazaleh\Configuration\ConfigurationInterface;
use Psr\Log\LoggerInterface;

class Application extends \Symfony\Component\Console\Application
{
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var ConfigurationInterface
     */
    private $config;

    /**
     * @param LoggerInterface $logger
     * @param ConfigurationInterface $config
     */
    public function __construct(LoggerInterface $logger, ConfigurationInterface $config)
    {
        parent::__construct('XML Booster', 'v1.0');
        $this->logger = $logger;
        $this->config = $config;
    }

    /**
     * @return ConfigurationInterface;
     * */
    public function getConfig():ConfigurationInterface
    {
        return $this->config;
    }

    /**
     * @return LoggerInterface
     * */
    public function getLogger():LoggerInterface
    {
        return $this->logger;
    }
}
