<?php

namespace Fwolf\Tools\TtSync\PluginApi;

/**
 * @copyright   Copyright 2017 Fwolf
 * @license     https://opensource.org/licenses/MIT MIT
 */
interface AttachmentInterface
{
    /**
     * Getter of attachment mime type
     *
     * @return  string
     */
    public function getMimeType(): string;


    /**
     * Getter of attachment file path
     *
     * @return  string
     */
    public function getPath(): string;


    /**
     * Getter of attachment file size
     *
     * @return  int
     */
    public function getSize(): int;


    /**
     * Getter of attachment url
     *
     * Maybe empty if attachment is load from disk.
     *
     * @return  string
     */
    public function getUrl();


    /**
     * Is attachment saved to disk ?
     *
     * @return  bool
     */
    public function isDownloaded(): bool;


    /**
     * Download from url or save to another disk location
     *
     * @param   string $path Will generate tmp path if empty
     * @return  $this
     */
    public function save(string $path = ''): self;


    /**
     * Setter of disk path
     *
     * @param   string $path
     * @return  $this
     */
    public function setPath(string $path): self;


    /**
     * Setter of attachment url
     *
     * @param   string $url
     * @return  $this
     */
    public function setUrl(string $url): self;
}
