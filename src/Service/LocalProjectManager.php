<?php

namespace Noq\DevlinkBundle\Service;

use Noq\DevlinkBundle\Entity\LocalProject;

class LocalProjectManager
{
    /**
     * @var ComposerFileLocator
     */
    private $composerFileLocator;

    /**
     * @var ComposerParser
     */
    private $composerParser;

    /**
     * @var ConfigFileManager
     */
    private $configFileManager;

    /**
     * @var LocalProject[]|null
     */
    private $localProjects = null;

    public function __construct(
        ComposerFileLocator $composerFileLocator,
        ComposerParser $composerParser,
        ConfigFileManager $configFileManager
    )
    {
        $this->composerFileLocator = $composerFileLocator;
        $this->composerParser = $composerParser;
        $this->configFileManager = $configFileManager;
    }

    public function findProjectByName(string $name)
    {
        if (is_null($this->localProjects)) {
            $this->localProjects = $this->findLocalProjects();
        }

        foreach ($this->localProjects as $localProject) {
            if ($localProject->getName() === $name) {
                return $localProject;
            }
        }

        return false;
    }

    private function findLocalProjects(): array
    {
        $paths = $this->configFileManager->getPathsFromConfig();

        $projects = [];
        foreach ($paths as $path) {
            $projects = array_merge(
                $projects,
                $this->findProjectsInPath($path)
            );
        }

        return $projects;
    }

    private function findProjectsInPath($path): array
    {
        $projects = [];
        $composerFiles = $this->composerFileLocator->findComposerFilesRecursive($path);
        foreach ($composerFiles as $composerFile) {
            $name = $this->composerParser->getProjectNameFromComposerFile($composerFile);
            $projects[] = new LocalProject($composerFile, $name);
        }
        return $projects;
    }

}