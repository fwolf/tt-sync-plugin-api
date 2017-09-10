<?php

namespace Fwolf\Tools\TtSync\PluginApi;

/**
 * @copyright   Copyright 2017 Fwolf
 * @license     https://opensource.org/licenses/MIT MIT
 */
abstract class AbstractPlugin implements PluginInterface
{
    /**
     * Plugin version
     */
    const VERSION = '';


    /**
     * Sort message by update/publish time ascending
     *
     * @param   MessageInterface[] $messages
     * @return  MessageInterface[]
     */
    protected function sortMessages(array $messages): array
    {
        $compareFunc = function (
            MessageInterface $message1,
            MessageInterface $message2
        ) {
            return $message1->isEarlierThan($message2) ? -1 : 1;
        };

        usort($messages, $compareFunc);

        return $messages;
    }
}
