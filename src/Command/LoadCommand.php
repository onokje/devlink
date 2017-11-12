<?php

namespace Noq\DevlinkBundle\Command;

use Noq\DevlinkBundle\Service\ComposerFileLocator;
use Noq\DevlinkBundle\Service\ConfigFileManager;
use Noq\DevlinkBundle\Service\DirectoryValidator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LoadCommand extends Command
{
    private $directoryValidator;

    private $configFileManager;

    private $composerFileLocator;

    public function __construct(
        DirectoryValidator $directoryValidator,
        ConfigFileManager $configFileManager,
        ComposerFileLocator $composerFileLocator
    )
    {
        $this->directoryValidator = $directoryValidator;
        $this->configFileManager = $configFileManager;
        $this->composerFileLocator = $composerFileLocator;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('devlink:load')
            ->setDescription('Loads a project directory')
            ->setHelp('This command registers a directory where your project is located.')
            ->addArgument('dirname', InputArgument::REQUIRED, 'The path to add, can be absolute or relative.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dirname = $this->directoryValidator->validateDir($input->getArgument('dirname'));

        $this->configFileManager->addToconfig($dirname);

        $output->writeln(sprintf('Path "%s" added to json.', $dirname));

    }
}