<?php declare(strict_types=1);

namespace FGhazaleh\Configuration;

use FGhazaleh\Exceptions\FileNotFoundException;

class ConfigurationLoader implements ConfigurationInterface
{

    private $configs = [];

    public function __construct(string $applicationPath = null)
    {
        $applicationPath = $applicationPath??__DIR__;

        $applicationPath = realpath($applicationPath) . '/config/';

        if (!file_exists($applicationPath)) {
            throw new FileNotFoundException(sprintf(
                'Directory not found in the following path [%s]',
                $applicationPath
            ));
        }

        $finder = new \Symfony\Component\Finder\Finder();
        $finder->files()->name('*.php')->in($applicationPath);

        foreach ($finder as $item) {
            if (!is_dir($item->getRealPath())) {
                $this->loadConfig(
                    str_replace('.php', '', $item->getFilename()),
                    $item->getRealPath()
                );
            }
        }
    }

    /**
     * Get the config value from config file.
     *
     * @param string $key
     * @param mixed|null $default
     * @return mixed|null
     */
    public function get(string $key, $default = null)
    {
        $keys = explode('.', $key);
        $config = $this->configs;
        foreach ($keys as $k) {
            if (!array_key_exists($k, $config)) {
                return $default;
            }
            $config = $config[$k];
        }
        return $config;
    }

    /**
     * @param string $key
     * @param string $file
     */
    private function loadConfig(string $key, string $file):void
    {
        $this->configs[$key] = include $file;
    }
}
