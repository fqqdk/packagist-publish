<?php

namespace Fqqdk\Packagist;


use Composer\Command\BaseCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Command extends BaseCommand
{
    protected function configure()
    {
        $this->setName('publish');
        $this
            ->addArgument(
                'username',
                InputArgument::REQUIRED,
                'Packagist user name.'
            )
            ->addArgument(
                'api_token',
                InputArgument::REQUIRED,
                'Packagist api token.'
            )
            ->addOption(
                'api_base_url',
                'b',
                InputOption::VALUE_REQUIRED,
                'The base URL for the packagist registry, defaults to https://packagist.org/',
                'https://packagist.org/'
            );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $packageName = $this->getComposer()->getPackage()->getName();
        $this->createPublisher($input)->updatePackage($packageName);
        $output->write("Successfully published package {$packageName}", true);

        return 0;
    }

    private function createPublisher(InputInterface $input)
    {
        $arguments = $input->getArguments();
        $vendorDir = $this->getComposer()->getConfig()->get('vendor-dir');
        $packagistUrl = $input->getOption('api_base_url');
        $userName = $arguments['username'];
        $apiToken = $arguments['api_token'];
        return Publisher::create($vendorDir, $packagistUrl, $userName, $apiToken);
    }
}
