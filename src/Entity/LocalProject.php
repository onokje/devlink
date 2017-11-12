<?php

namespace Noq\DevlinkBundle\Entity;

class LocalProject
{
    /**
     * @var string
     */
    private $composerFilePath;

    /**
     * @var string
     */
    private $name;

    public function __construct(string $composerFilePath, string $name)
    {
        $this->composerFilePath = $composerFilePath;
        $this->name = $name;
    }

    public function isValid(): bool
    {
        return !empty($name);
    }

    /**
     * @return string
     */
    public function getComposerFilePath(): string
    {
        return $this->composerFilePath;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getProjectPath(): string
    {
        $parts = pathinfo($this->composerFilePath);
        return $parts['dirname'];
    }
}
