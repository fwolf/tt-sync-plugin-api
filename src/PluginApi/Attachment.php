<?php

namespace Fwolf\Tools\TtSync\PluginApi;

/**
 * @copyright   Copyright 2017 Fwolf
 * @license     https://opensource.org/licenses/MIT MIT
 */
class Attachment implements AttachmentInterface
{
    /**
     * Temp file name prefix when download
     */
    const TMP_FILE_PREFIX = 'tt';


    /**
     * Is this attachment downloaded to disk or not ?
     *
     * @var bool
     */
    protected $isDownloaded = false;

    /**
     * @var string
     */
    protected $mimeType = '';

    /**
     * Path on disk
     *
     * Maybe empty if not download.
     *
     * @var string
     */
    protected $path = '';

    /**
     * @var int
     */
    protected $size = 0;

    /**
     * Url if its create from internet
     *
     * Maybe empty if load from disk.
     *
     * @var string
     */
    protected $url = '';


    /**
     * @param   string $urlOrPath
     */
    public function __construct(string $urlOrPath)
    {
        $this->setUrlOrPath($urlOrPath);
    }


    /**
     * @inheritdoc
     *
     * Will download attachment if haven't.
     */
    public function getMimeType(): string
    {
        if (!$this->isDownloaded()) {
            $this->save();
        }

        return $this->mimeType;
    }


    /**
     * @inheritdoc
     *
     * Will download attachment if haven't.
     */
    public function getPath(): string
    {
        if (!$this->isDownloaded()) {
            $this->save();
        }

        return $this->path;
    }


    /**
     * @inheritdoc
     *
     * Will download attachment if haven't.
     */
    public function getSize(): int
    {
        if (!$this->isDownloaded()) {
            $this->save();
        }

        return $this->size;
    }


    /**
     * Generate tmp file path for download
     *
     * @return  string
     */
    protected function getTmpPath(): string
    {
        $path = tempnam(sys_get_temp_dir(), static::TMP_FILE_PREFIX);

        // Add file ext of url to path
        $url = $this->getUrl();
        $pos = strrpos($url, '.');
        if (false === $pos) {
            // Url have no ext, return original
            return $path;
        } else {
            return $path . '.' . substr($url, $pos + 1);
        }
    }


    /**
     * @inheritdoc
     */
    public function getUrl()
    {
        return $this->url;
    }


    /**
     * @inheritdoc
     */
    public function isDownloaded(): bool
    {
        return $this->isDownloaded;
    }


    /**
     * @inheritdoc
     */
    public function save(string $path = ''): AttachmentInterface
    {
        if ($this->isDownloaded()) {
            if (empty($path)) {
                // Already downloaded to tmp, do nothing
                return $this;
            } else {
                rename($this->getPath(), $path);
            }
        } else {
            if (empty($path)) {
                $path = $this->getTmpPath();
            }
            file_put_contents($path, fopen($this->getUrl(), 'rb'));
        }

        $this->setPath($path);

        return $this;
    }


    /**
     * @param   bool $isDownloaded
     * @return  $this
     */
    protected function setIsDownloaded(bool $isDownloaded): self
    {
        $this->isDownloaded = $isDownloaded;

        return $this;
    }


    /**
     * @param   string $mimeType
     * @return  $this
     */
    protected function setMimeType(string $mimeType): self
    {
        $this->mimeType = $mimeType;

        return $this;
    }


    /**
     * @inheritdoc
     */
    public function setPath(string $path): AttachmentInterface
    {
        $this->path = $path;

        $this->setIsDownloaded(true);
        $this->setMimeType(mime_content_type($path));
        $this->setSize(filesize($path));

        return $this;
    }


    /**
     * @param   int $size
     * @return  $this
     */
    protected function setSize(int $size): self
    {
        $this->size = $size;

        return $this;
    }


    /**
     * @inheritdoc
     *
     * Will clear downloaded status.
     */
    public function setUrl(string $url): AttachmentInterface
    {
        $this->url = $url;

        $this->setIsDownloaded(false);

        return $this;
    }


    /**
     * @param   string $urlOrPath
     * @return  $this
     */
    protected function setUrlOrPath(string $urlOrPath): self
    {
        if ('http://' == strtolower(substr($urlOrPath, 0, 7)) ||
            'https://' == strtolower(substr($urlOrPath, 0, 8))
        ) {
            $this->setUrl($urlOrPath);
        } else {
            $this->setPath($urlOrPath);
        }

        return $this;
    }
}
