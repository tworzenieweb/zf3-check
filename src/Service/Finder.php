<?php


namespace Tworzenieweb\Zf3Check\Service;


use Symfony\Component\Finder\Finder as SymfonyFinder;

/**
 * Class Finder
 *
 * @package Tworzenieweb\Zf3Check\Service
 */
class Finder
{
    const SERVICE_FILENAME_PATTERN = '*Factory.php';
    const SERVICE_WITH_MISSING_INVOKE = 'public function __invoke(ContainerInterface $container';

    /** @var SymfonyFinder */
    private $finderComponent;



    /**
     * Finder constructor.
     *
     * @param SymfonyFinder $finderComponent
     */
    public function __construct(SymfonyFinder $finderComponent)
    {
        $this->finderComponent = $finderComponent;
    }

    /**
     * @param string $location
     * @return \IteratorAggregate
     */
    public function findServices($location)
    {
        return $this->finderComponent->files()
            ->in($location)
            ->ignoreDotFiles(true)
            ->name(self::SERVICE_FILENAME_PATTERN)
            ->contains('/[\s,]+((Delegator)?FactoryInterface)|AbstractActionControllerFactory/')
            ->contains('/^\s*public function [createService|createDelegatorWithName]/')
            ->notContains(self::SERVICE_WITH_MISSING_INVOKE)
            ->notContains('abstract class');
    }
}
