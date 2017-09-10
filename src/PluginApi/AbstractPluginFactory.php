<?php

namespace Fwolf\Tools\TtSync\PluginApi;

/**
 * One factory for one plugin class
 *
 * @copyright   Copyright 2017 Fwolf
 * @license     https://opensource.org/licenses/MIT MIT
 */
abstract class AbstractPluginFactory implements PluginFactoryInterface
{
    /**
     * Class name of plugin this factory will create
     */
    const PLUGIN_CLASS_NAME = '';


    /**
     * Configure plugin instance
     *
     * @param   PluginInterface $plugin
     * @return  PluginInterface
     */
    protected function configPlugin(PluginInterface $plugin): PluginInterface
    {
        // Do config

        return $plugin;
    }


    /**
     * @inheritDoc
     */
    public function createPlugin(): PluginInterface
    {
        $className = $this->getPluginClass();

        $plugin = new $className;

        $plugin = $this->configPlugin($plugin);

        return $plugin;
    }


    /**
     * Get or reuse instance of self
     *
     * @return PluginFactoryInterface
     */
    public static function getInstance(): PluginFactoryInterface
    {
        static $instances = [];

        $className = get_called_class();

        if (!isset($instances[$className])) {
            $instances[$className] = new $className();
        }

        return $instances[$className];
    }


    /**
     * @inheritDoc
     */
    public function getPluginClass(): string
    {
        return static::PLUGIN_CLASS_NAME;
    }
}
