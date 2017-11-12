<?php

namespace Noq\DevlinkBundle\Command;

use Noq\DevlinkBundle\Service\ConfigFileManager;
use Noq\DevlinkBundle\Service\DirectoryValidator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RemoveCommand extends Command
{
    private $directoryValidator;

    private $configFileManager;

    public function __construct(DirectoryValidator $directoryValidator, ConfigFileManager $configFileManager)
    {
        $this->directoryValidator = $directoryValidator;
        $this->configFileManager = $configFileManager;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('devlink:remove')
            ->setDescription('Removes a project directory from the config')
            ->setHelp('This command unregisters a directory currently configured.')
            ->addArgument('dirname', InputArgument::REQUIRED, 'The path to remove, can be absolute or relative.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dirname = $this->directoryValidator->validateDir($input->getArgument('dirname'));

        $this->configFileManager->removeFromconfig($dirname);

        $output->writeln(sprintf('Path "%s" removed from json.', $dirname));

    }
}