<?php

namespace Fwolf\Tools\TtSync\PluginApi;

/**
 * @copyright   Copyright 2017 Fwolf
 * @license     https://opensource.org/licenses/MIT MIT
 */
interface PluginFactoryInterface
{
    /**
     * Create an plugin instance and config it
     *
     * @return  PluginInterface
     */
    public function createPlugin(): PluginInterface;


    /**
     * Get an factory instance
     *
     * @return  static
     */
    public static function getInstance(): self;


    /**
     * Getter of plugin class name
     *
     * @return  string
     */
    public function getPluginClass(): string;
}
