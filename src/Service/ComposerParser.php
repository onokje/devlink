<?php

namespace Noq\DevlinkBundle\Service;

class ComposerParser
{
    public function getProjectNameFromComposerFile($filePath)
    {
        $composerConfig = $this->loadComposerConfigFromComposerFile($filePath);

        return $composerConfig['name'] ?? null;
    }

    public function getDependenciesFromComposerFile($filePath): array
    {
        $composerConfig = $this->loadComposerConfigFromComposerFile($filePath);

        $dependencies = array_keys($composerConfig['require'] ?? []);
        return $this->filterDependencies($dependencies);
    }

    private function filterDependencies(array $dependencies)
    {
        foreach ($dependencies as $key => $dependency) {
            if (strpos($dependency, "/") === false) {
                unset($dependencies[$key]);
            }
        }
        return array_values($dependencies);
    }

    private function loadComposerConfigFromComposerFile($filePath)
    {
        return json_decode(file_get_contents($filePath), true);
    }
}
