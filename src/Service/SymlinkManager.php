<?php

namespace Noq\DevlinkBundle\Service;

use InvalidArgumentException;
use Noq\DevlinkBundle\Entity\LocalProject;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class SymlinkManager
{

    const VENDOR_DIR = 'vendor';

    public function replaceVendorDitWithSymlink(string $dependency, LocalProject $localProject)
    {
        $dependencyDir = $this->getDependencyDirectory($dependency);

        try {
            $this->deleteDirRecursive($dependencyDir);
        } catch (\Exception $e) {

        }

        $this->createSymlinkIfNotExists($dependencyDir, $localProject->getProjectPath());
    }

    public function removeSymlink(string $dependency, LocalProject $localProject)
    {
        $dependencyDir = $this->getDependencyDirectory($dependency);
        if (is_link($dependencyDir) && readlink($dependencyDir) === $localProject->getProjectPath()) {
            unlink($dependencyDir);
        }
    }

    private function createSymlinkIfNotExists($dependencyDir, $targetDir)
    {
        if (is_link($dependencyDir) && readlink($dependencyDir) === $targetDir) {
            echo "notice: Symlink $dependencyDir already exists.\n";
        } else {
            symlink ($targetDir, $dependencyDir);
        }
    }

    private function deleteDirRecursive($dir)
    {
        if (empty($dir) || !is_dir($dir)) {
            throw new InvalidArgumentException("$dir must be a directory");
        }

        $it = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
        $files = new RecursiveIteratorIterator($it,RecursiveIteratorIterator::CHILD_FIRST);
        foreach($files as $file) {
            if ($file->isDir()){
                rmdir($file->getRealPath());
            } else {
                unlink($file->getRealPath());
            }
        }
        rmdir($dir);
    }

    private function getDependencyDirectory(string $dependency)
    {
        return getcwd() . DIRECTORY_SEPARATOR . self::VENDOR_DIR . DIRECTORY_SEPARATOR . $dependency;
    }

}