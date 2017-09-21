<?php


namespace Tworzenieweb\Zf3Check;

use RuntimeException;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

/**
 * @author Luke Adamczewski
 * @package Tworzenieweb\SqlProvisioner
 */
class Application extends \Symfony\Component\Console\Application
{
    const NAME = 'Zend Framework 3 Migration check';
    const VERSION = '0.0.1';

    /** @var ContainerBuilder */
    private $container;

    /**
     * @param string $name
     * @param string $version
     */
    public function __construct($name = 'UNKNOWN', $version = 'UNKNOWN')
    {
        parent::__construct(self::NAME, self::VERSION);
        $this->boot();
    }

    protected function registerCommands()
    {
        foreach ($this->container->findTaggedServiceIds('console.command') as $commandId => $command) {
            $commandService = $this->getCommandForId($commandId);

            if (null === $commandService) {
                throw new RuntimeException(sprintf("Couldn't fetch service %s from container.", $commandId));
            }

            $this->add($commandService);
        }
    }

    /**
     * @param string $commandId
     * @return Command|Object
     */
    protected function getCommandForId($commandId)
    {
        if (!$this->container->has($commandId)) {
            throw new RuntimeException(sprintf('There is no command class for id %s', $commandId));
        }

        return $this->container->get($commandId);
    }

    private function boot()
    {
        $this->container = new ContainerBuilder();

        $loader = new XmlFileLoader($this->container, new FileLocator($this->getConfigPath()));
        $loader->load('services.xml');
        $this->registerCommands();
        $this->registerChecks();
        $this->container->compile();
    }

    /**
     * @return string
     */
    private function getConfigPath()
    {
        return __DIR__ . '/../config';
    }

    /**
     * @return string
     */
    private function getRootPath()
    {
        return __DIR__ . '/..';
    }

    private function registerChecks()
    {
       $migrator = $this->container->get('service.migrator');

       foreach ($this->container->findTaggedServiceIds('writer_strategy') as $serviceId => $tagAttributes) {
           $writerStrategy = $this->container->get($serviceId);

           if (empty($writerStrategy)) {
               throw new RuntimeException(sprintf('Service %s not found', $serviceId));
           }


           $migrator->addWritingStrategy($writerStrategy);
       }
    }
}
