<?php

namespace Noq\DevlinkBundle\Service;

class ComposerFileLocator
{
    const COMPOSER_FILENAME = 'composer.json';

    public function findComposerFilesRecursive($path)
    {
        $composerJSONLocations = [];

        $files = $this->filterArray(scandir($path));
        foreach ($files as $filename) {
            $filePath = $path . DIRECTORY_SEPARATOR . $filename;
            if (is_dir($filePath)) {
                $composerJSONLocations = array_merge($composerJSONLocations, $this->findComposerFilesRecursive($filePath));
            }
            if ($filename === self::COMPOSER_FILENAME) {
                $composerJSONLocations[] = $filePath;
            }
        }

        return $composerJSONLocations;
    }

    public function locateComposerFileInCurrentDir()
    {
        $filePath =  getcwd() . DIRECTORY_SEPARATOR . self::COMPOSER_FILENAME;

        if (is_file($filePath)) {
            return $filePath;
        }
        throw new \Exception (sprintf('No %s found in current directory', self::COMPOSER_FILENAME));
    }

    private function filterArray($files)
    {
        return array_diff($files, [
            ".",
            "..",
            "",
            "System Volume Information",
            "RECYCLER",
            '$RECYCLE.BIN',
            'lost+found',
            'vendor'
        ]);
    }

}