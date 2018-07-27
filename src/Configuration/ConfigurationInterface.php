<?php declare(strict_types=1);

namespace FGhazaleh\Configuration;

interface ConfigurationInterface
{
    /**
     * Get the config value from config file.
     *
     * @param string $key
     * @param mixed|null $default
     * @return mixed|null
     */
    public function get(string $key, $default = null);
}
