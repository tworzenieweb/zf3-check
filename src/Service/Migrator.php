<?php

namespace Tworzenieweb\Zf3Check\Service;

use Symfony\Component\Finder\SplFileInfo;
use Tworzenieweb\Zf3Check\Model\Exception;
use Tworzenieweb\Zf3Check\Model\FactoryClass;
use Tworzenieweb\Zf3Check\Service\Writer\WriterStrategy;

class Migrator
{
    /** @var Finder */
    private $finder;

    /** @var WriterStrategy[] */
    private $strategy;

    /** @var Writer */
    private $writer;

    /** @var string[] */
    private $currentlyMigratedClasses;



    /**
     * Migrator constructor.
     *
     * @param Finder $finder
     * @param Writer $writer
     */
    public function __construct(Finder $finder, Writer $writer)
    {
        $this->finder = $finder;
        $this->writer = $writer;
        $this->strategy = [];
        $this->alreadyMigrated = [];
        $this->currentlyMigratedClasses = [];
    }



    public function migrate($workingDirectory)
    {
        $this->migrateFactories($workingDirectory);
    }



    /**
     * @return string[]
     */
    public function getCurrentlyMigratedClasses()
    {
        return $this->currentlyMigratedClasses;
    }



    /**
     * @param string $workingDirectory
     */
    private function migrateFactories($workingDirectory)
    {
        /** @var SplFileInfo[] $filesIterator */
        $filesIterator = $this->finder->findServices($workingDirectory);

        foreach ($filesIterator as $file) {
            foreach ($this->strategy as $strategy) {
                $factoryClass = new FactoryClass($file->getPathname());

                if ($strategy->canHandle($factoryClass)) {
                    $this->migrateFactory($factoryClass, $strategy);
                }
            }
        }
    }



    /**
     * @param WriterStrategy $strategy
     */
    public function addWritingStrategy(WriterStrategy $strategy)
    {
        $this->strategy[] = $strategy;
    }



    /**
     * @param FactoryClass $factoryClass
     * @param WriterStrategy $strategy
     */
    private function migrateFactory(FactoryClass $factoryClass, WriterStrategy $strategy)
    {
        try {
            $this->writer->writeIntoClass($factoryClass, $strategy);
            $this->currentlyMigratedClasses[] = [$factoryClass->getNamespace() . '/' . $factoryClass->getClass(), 'migrated'];
        } catch (Exception $alreadyMigratedException) {
            $this->currentlyMigratedClasses[] = [$factoryClass->getNamespace() . '/' . $factoryClass->getClass(), 'skipped'];
        }
    }
}
