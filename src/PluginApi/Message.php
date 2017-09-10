<?php

namespace Fwolf\Tools\TtSync\PluginApi;

/**
 * @copyright   Copyright 2017 Fwolf
 * @license     https://opensource.org/licenses/MIT MIT
 */
class Message implements MessageInterface
{
    /**
     * Keys when exported to json
     */
    const KEY_ID = 'id';

    const KEY_CONTENT = 'content';

    const KEY_UPDATE_TIME = 'updateTime';

    const KEY_ATTACHMENTS = 'attachments';


    /**
     * @var AttachmentInterface[]
     */
    protected $attachments = [];

    /**
     * @var string
     */
    protected $content = '';

    /**
     * @var string
     */
    protected $id = '';

    /**
     * @var string
     */
    protected $updateTime = '';


    /**
     * @inheritdoc
     */
    public function addAttachment(
        AttachmentInterface $attachment
    ): MessageInterface {
        $this->attachments[] = $attachment;

        return $this;
    }


    /**
     * @inheritdoc
     */
    public function dump(string $dir): MessageInterface
    {
        $path = $dir . $this->getDumpPath();
        $parentDir = dirname($path);
        if (!file_exists($parentDir)) {
            mkdir($parentDir, 0755, true);
        }

        /** @var AttachmentInterface $attachment */
        foreach ($this->getAttachments() as $i => $attachment) {
            $attachmentPath =
                $this->getAttachmentDumpPath($i + 1, $attachment, $path);
            $attachment->save($attachmentPath);
        }

        // Save json after attachment, as attachment may download/move during
        // save, cause its filename change.
        $jsonStr = $this->toJson(true);
        file_put_contents($path, $jsonStr);

        return $this;
    }


    /**
     * @inheritDoc
     */
    public function fromJson(
        string $jsonStr,
        bool $withAttachments = true
    ): MessageInterface {
        $jsonAr = json_decode($jsonStr, true);
        $this->setId($jsonAr[static::KEY_ID]);
        $this->setContent($jsonAr[static::KEY_CONTENT]);
        $this->setUpdateTime($jsonAr[static::KEY_UPDATE_TIME]);

        $this->setAttachments([]);
        if ($withAttachments) {
            foreach ($jsonAr[static::KEY_ATTACHMENTS] as $attachmentPath) {
                $this->addAttachment(new Attachment($attachmentPath));
            }
        }

        return $this;
    }


    /**
     * Get attachment dump path based on json dump path
     *
     * If json dump file is 'foo.json', then attachments will be same directory
     * with name 'foo-1.png', 'foo-2.jpg', the ext name keep same with original
     * attachment file.
     *
     * @param   int                 $seq  Seq of attachment, start from 1.
     * @param   AttachmentInterface $attachment
     * @param   string              $path Json dump path.
     * @return  string
     */
    protected function getAttachmentDumpPath(
        int $seq,
        AttachmentInterface $attachment,
        string $path
    ): string {
        $path = substr($path, 0, strlen($path) - 5);

        $path .= '-' . trim(strval($seq));

        $attachmentPath = $attachment->getPath();
        $pos = strrpos($attachmentPath, '.');
        if (false === $pos) {
            // Attachment have no ext, return original
            return $path;
        } else {
            return $path . '.' . substr($attachmentPath, $pos + 1);
        }
    }


    /**
     * @inheritdoc
     */
    public function getAttachments(): array
    {
        return $this->attachments;
    }


    /**
     * @inheritdoc
     */
    public function getContent(): string
    {
        return $this->content;
    }


    /**
     * Get dump file path, relative from storage dir
     *
     * @return  string
     */
    protected function getDumpPath(): string
    {
        $year = date('Y', strtotime($this->getUpdateTime()));
        $id = $this->getId();

        $path = "{$year}/{$id}.json";

        return $path;
    }


    /**
     * @inheritdoc
     */
    public function getId(): string
    {
        return $this->id;
    }


    /**
     * @inheritDoc
     */
    public function getUpdateTime(): string
    {
        return $this->updateTime;
    }


    /**
     * @inheritDoc
     */
    public function isEarlierThan(MessageInterface $message): bool
    {
        $selfUpdateTime = $this->getUpdateTime();
        $messageUpdateTime = $message->getUpdateTime();

        if ($selfUpdateTime == $messageUpdateTime) {
            return $this->getId() < $message->getId();
        } else {
            return strtotime($selfUpdateTime) < strtotime($messageUpdateTime);
        }
    }


    /**
     * @inheritDoc
     */
    public function load(string $dumpFile): MessageInterface
    {
        $jsonStr = file_get_contents($dumpFile);

        $this->fromJson($jsonStr, true);

        return $this;
    }


    /**
     * @inheritdoc
     */
    public function setAttachments(array $attachments): MessageInterface
    {
        $this->attachments = $attachments;

        return $this;
    }


    /**
     * @inheritdoc
     */
    public function setContent(string $content): MessageInterface
    {
        $this->content = $content;

        return $this;
    }


    /**
     * @inheritdoc
     */
    public function setId(string $id): MessageInterface
    {
        $this->id = $id;

        return $this;
    }


    /**
     * @inheritDoc
     */
    public function setUpdateTime(string $updateTime): MessageInterface
    {
        $this->updateTime = $updateTime;

        return $this;
    }


    /**
     * @inheritDoc
     */
    public function toJson(bool $withAttachments = true): string
    {
        $jsonAr = [
            static::KEY_ID          => $this->getId(),
            static::KEY_CONTENT     => $this->getContent(),
            static::KEY_UPDATE_TIME => $this->getUpdateTime(),
        ];

        if ($withAttachments) {
            $jsonAr[static::KEY_ATTACHMENTS] = [];
            /** @var AttachmentInterface $attachment */
            foreach ($this->getAttachments() as $attachment) {
                $jsonAr[static::KEY_ATTACHMENTS][] = $attachment->getPath();
            }
        }

        return json_encode($jsonAr, JSON_UNESCAPED_UNICODE);
    }
}
