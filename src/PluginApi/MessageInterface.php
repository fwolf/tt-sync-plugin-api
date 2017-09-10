<?php

namespace Fwolf\Tools\TtSync\PluginApi;

use DateTime;

/**
 * @copyright   Copyright 2017 Fwolf
 * @license     https://opensource.org/licenses/MIT MIT
 */
interface MessageInterface
{
    const UPDATE_TIME_FORMAT = DateTime::W3C;


    /**
     * Append an attachment to list
     *
     * @param   AttachmentInterface $attachment
     * @return  $this
     */
    public function addAttachment(AttachmentInterface $attachment): self;


    /**
     * Dump content and attachments to disk
     *
     * @param   string $dir Parent dir to storage dump, MUST end with '/'.
     * @return  $this
     */
    public function dump(string $dir): self;


    /**
     * Import from json
     *
     * @param   string $jsonStr
     * @param   bool   $withAttachments Export attachments info too
     * @return  $this
     */
    public function fromJson(
        string $jsonStr,
        bool $withAttachments = true
    ): self;


    /**
     * Getter of attachments
     *
     * @return  AttachmentInterface[]
     */
    public function getAttachments(): array;


    /**
     * Getter of message content
     *
     * @return  string
     */
    public function getContent(): string;


    /**
     * Getter of unique id of message
     *
     * @return  string
     */
    public function getId(): string;


    /**
     * Getter of message update/publish time
     *
     * @return  string
     */
    public function getUpdateTime(): string;


    /**
     * Check if a message is earlier than another
     *
     * May used when sort messages
     *
     * As message have unique id, they will never equal.
     *
     * @param   MessageInterface $message
     * @return  bool
     */
    public function isEarlierThan(MessageInterface $message): bool;


    /**
     * Load content and attachments to disk
     *
     * @param   string $dumpFile Path to dump file
     * @return  $this
     */
    public function load(string $dumpFile): self;


    /**
     * Setter of attachments
     *
     * Will overwrite all exists data.
     *
     * @param   AttachmentInterface[] $attachments
     * @return  $this
     */
    public function setAttachments(array $attachments): self;


    /**
     * Setter of message content
     *
     * @param   string $content
     * @return  $this
     */
    public function setContent(string $content): self;


    /**
     * Setter of unique id of message
     *
     * @param   string $id
     * @return  $this
     */
    public function setId(string $id): self;


    /**
     * Setter of message update/publish time
     *
     * @param   string $updateTime
     * @return  $this
     */
    public function setUpdateTime(string $updateTime): self;


    /**
     * Export to json
     *
     * @param   bool $withAttachments Export attachments info too
     * @return  string
     */
    public function toJson(bool $withAttachments = true): string;
}
