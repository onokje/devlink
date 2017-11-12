<?php

namespace Noq\DevlinkBundle\Service;

class DirectoryValidator
{

    public function validateDir(string $dir): string
    {
        if (is_dir(realpath($dir))) {
            return realpath($dir);
        }

        if (is_dir(realpath(getcwd() . $dir))) {
            return realpath(getcwd() . $dir);
        }

        throw new \Exception(sprintf("%s is not a valid directory", $dir));
    }
    
}