<?php

namespace Fwolf\Tools\TtSync\PluginApi;

/**
 * @copyright   Copyright 2017 Fwolf
 * @license     https://opensource.org/licenses/MIT MIT
 */
interface PluginInterface
{
    /**
     * Plugin API version
     */
    const API_VERSION = '0.1';

    /**
     * Default to get how many recent messages
     */
    const RECENT_COUNT = 30;


    /**
     * Get all/most messages
     *
     * Some service has limit on max number of messages can retrieved, so we
     * cannot get 'all' messages, only 'most'.
     *
     * Messages should sort by update/publish time ascending.
     *
     * @return  MessageInterface[]
     */
    public function getAllMessages(): array;


    /**
     * Get recent messages
     *
     * Messages should sort by update/publish time ascending.
     *
     * @param   int $count Count of recent messages to get
     * @return  MessageInterface[]
     */
    public function getRecentMessages(int $count = self::RECENT_COUNT): array;


    /**
     * Post single message
     *
     * @param   MessageInterface $message
     * @return  $this
     */
    public function postMessage(MessageInterface $message): self;


    /**
     * Post multiple messages, by raw order
     *
     * @param   MessageInterface[] $messages
     * @return  $this
     */
    public function postMessages(array $messages): self;


    /**
     * Validate a profile can connect successful
     *
     * @param   string $profile
     * @return  bool
     */
    public function validate(string $profile): bool;
}
