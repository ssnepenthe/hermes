<?php

namespace SSNepenthe\Hermes\Command;

use RuntimeException;
use Symfony\Component\Config\FileLocator;
use SSNepenthe\Hermes\Loader\PhpFileLoader;
use SSNepenthe\Hermes\Loader\JsonFileLoader;
use SSNepenthe\Hermes\Loader\YamlFileLoader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Config\Loader\LoaderResolver;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Config\Loader\DelegatingLoader;
use Symfony\Component\Console\Output\OutputInterface;
use SSNepenthe\Hermes\Definition\ScraperConfiguration;

class DebugConfigCommand extends Command
{
    protected function configure()
    {
        $this->setName('debug:config')
            ->setDescription(
                'Run a scraper file through the symfony/config processor and dump'
                . ' the result for manual inspection.'
            )
            ->addArgument(
                'file',
                InputArgument::REQUIRED,
                'Path to the scraper file you want to check.'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (! $file = realpath($input->getArgument('file'))) {
            // @todo
            throw new RuntimeException;
        }

        dump($this->processConfigFile($file));
    }

    protected function makeLoader()
    {
        $locator = new FileLocator;

        return new DelegatingLoader(
            new LoaderResolver([
                new PhpFileLoader($locator),
                new YamlFileLoader($locator),
                new JsonFileLoader($locator),
            ])
        );
    }

    protected function processConfigFile(string $file)
    {
        $processor = new Processor;
        $configuration = new ScraperConfiguration;

        return $processor->processConfiguration(
            $configuration,
            $this->makeLoader()->load($file)
        );
    }
}
