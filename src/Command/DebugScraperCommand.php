<?php

namespace SSNepenthe\Hermes\Command;

use SSNepenthe\Hermes\Scraper\ScraperFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DebugScraperCommand extends Command
{
    protected function configure()
    {
        $this->setName('debug:scraper')
            ->setDescription(
                'Create a scraper instance from a given config file and dump it for'
                . ' manual inspection.'
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
            throw new \RuntimeException;
        }

        dump(ScraperFactory::fromConfigFile($file));
    }
}
