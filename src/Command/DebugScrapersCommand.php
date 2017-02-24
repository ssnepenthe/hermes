<?php

namespace SSNepenthe\Hermes\Command;

use SSNepenthe\Hermes\Scraper\ScraperFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DebugScrapersCommand extends Command
{
    public function configure()
    {
        $this->setName('debug:scrapers')
            ->setDescription(
                'Create a delegating scraper instance using all config files found'
                . ' in a given directory and dump it for manual inspection.'
            )
            ->addArgument(
                'directory',
                InputArgument::REQUIRED,
                'The directory to load scrapers from.'
            );
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        if (! $directory = realpath($input->getArgument('directory'))) {
            // @todo
            throw new \RuntimeException;
        }

        $directory = rtrim($directory, DIRECTORY_SEPARATOR);
        $extPattern = '{json,php,yaml,yml}';
        $pattern = $directory . DIRECTORY_SEPARATOR . '*.' . $extPattern;

        $scraper = ScraperFactory::fromGlob($pattern);

        dump($scraper);
    }
}
