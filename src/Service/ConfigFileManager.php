<?php

namespace Noq\DevlinkBundle\Service;

class ConfigFileManager
{
    const FILENAME = 'devlink.json';

    public function addToconfig($dir)
    {
        $config = $this->loadConfig();

        if (isset($config['directories'])) {
            foreach ($config['directories'] as $directory) {
                if ($directory === $dir) {
                    throw new \Exception(sprintf("%s was already added", $dir));
                }
            }
        }

        $config['directories'][] = $dir;

        $this->writeConfig($config);

    }

    public function removeFromconfig($dir)
    {
        $config = $this->loadConfig();

        if (isset($config['directories'])) {

            $key = array_search($dir, $config['directories']);
            if ($key !== false) {
                array_splice($config['directories'],$key,1);
                $this->writeConfig($config);
                return;
            }
        }
        throw new \Exception(
            sprintf("Path %s was not found in configfile located at %s", $dir, $this->getTargetPath())
        );
    }

    public function getPathsFromConfig(): array
    {
        $config = $this->loadConfig();
        return $config['directories'] ?? [];
    }

    private function loadConfig()
    {
        if (is_file($this->getTargetPath())) {
            return json_decode(file_get_contents($this->getTargetPath()), true);
        } else {
            return [];
        }
    }

    private function writeConfig(array $config)
    {
        $contents = json_encode($config, JSON_PRETTY_PRINT);

        file_put_contents($this->getTargetPath(), $contents);
    }

    private function getTargetPath()
    {
        return getcwd() . DIRECTORY_SEPARATOR . self::FILENAME;
    }
}