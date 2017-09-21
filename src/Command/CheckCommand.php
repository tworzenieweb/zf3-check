<?php

namespace Tworzenieweb\Zf3Check\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Tworzenieweb\Zf3Check\Service\Migrator;

/**
 * Class CheckCommand
 *
 * @package Tworzenieweb\Zf3Check\Command
 */
class CheckCommand extends Command
{
    /** @var Migrator */
    private $migrator;

    /** @var SymfonyStyle */
    private $io;



    /**
     * @param Migrator $migrator
     */
    public function setMigrator(Migrator $migrator)
    {
        $this->migrator = $migrator;
    }



    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $path = $input->getArgument('path');

        $this->io = new SymfonyStyle($input, $output);
        $this->io->title('Zend Framework 3 Migrator');
        $this->io->block(sprintf('Migration started at %s', date('Y-m-d H:i:s')));

        $this->io->text(sprintf('<info>Processing %s</info>', $path));

        $this->migrator->migrate($path);

        $this->io->table(
            ['class', 'status'],
            $this->migrator->getCurrentlyMigratedClasses()
        );

        $this->io->block(sprintf('Migration ended at %s', date('Y-m-d H:i:s')));

        return 0;
    }



    protected function configure()
    {
        $this->setDescription('Look for potential files to upgrade');
        $this->addArgument('path', InputArgument::REQUIRED, 'Path where look for files to upgrade');
    }
}
