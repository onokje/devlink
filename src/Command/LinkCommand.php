<?php

namespace Noq\DevlinkBundle\Command;

use Noq\DevlinkBundle\Service\ComposerFileLocator;
use Noq\DevlinkBundle\Service\ComposerParser;
use Noq\DevlinkBundle\Service\LocalProjectManager;
use Noq\DevlinkBundle\Service\SymlinkManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LinkCommand extends Command
{
    /**
     * @var LocalProjectManager
     */
    private $localProjectManager;
    /**
     * @var ComposerParser
     */
    private $composerParser;
    /**
     * @var ComposerFileLocator
     */
    private $composerFileLocator;
    /**
     * @var SymlinkManager
     */
    private $symlinkManager;

    public function __construct(
        LocalProjectManager $localProjectManager,
        ComposerParser $composerParser,
        ComposerFileLocator $composerFileLocator,
        SymlinkManager $symlinkManager
    )
    {
        parent::__construct();
        $this->localProjectManager = $localProjectManager;
        $this->composerParser = $composerParser;
        $this->composerFileLocator = $composerFileLocator;
        $this->symlinkManager = $symlinkManager;
    }

    protected function configure()
    {
        $this
            ->setName('devlink:link')
            ->setDescription('Creates symlinks in your vendor map for locally stored projects')
            ->setHelp('This command creates symlinks in your vendlor map for locally stored projects.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $depedencies = $this->composerParser->getDependenciesFromComposerFile(
            $this->composerFileLocator->locateComposerFileInCurrentDir()
        );

        foreach ($depedencies as $depedency) {
            if ($localProject = $this->localProjectManager->findProjectByName($depedency)) {
                $this->symlinkManager->replaceVendorDitWithSymlink($depedency, $localProject);
                $output->writeln(sprintf('[DevLink] Symlink created for "%s".', $localProject->getName()));
            }
        }

    }
}
