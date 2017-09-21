<?php

namespace Tworzenieweb\Zf3Check\Service\Writer;

use Gnugat\Redaktilo\Editor;
use Tworzenieweb\Zf3Check\Model\Exception;
use Tworzenieweb\Zf3Check\Model\FactoryClass;

/**
 * Class DelegatorFactoryUpgrade
 *
 * @package Tworzenieweb\Zf3Check\Service\Writer
 */
class DelegatorFactoryUpgrade implements WriterStrategy
{
    const PATCH_TO_APPLY = <<<PHP
    /**
     * A factory that creates delegates of a given service
     *
     * @param  ContainerInterface \$container
     * @param  string \$name
     * @param  callable \$callback
     * @param  null|array \$options
     * @return object
     * @throws ServiceNotFoundException if unable to resolve the service.
     * @throws ServiceNotCreatedException if an exception is raised when
     *     creating a service.
     * @throws ContainerException if any other error occurs
     */
    public function __invoke(ContainerInterface \$container, \$name, callable \$callback, array \$options = null)
    {
        return \$this->createDelegatorWithName(\$container, \$name, \$name, \$callback);
    }



PHP;
    const NAMESPACE_TO_ADD = <<<PHP

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
PHP;
    const FACTORY_FILENAME = 'Factory.php';



    /**
     * @inheritdoc
     */
    public function writeIntoClass(FactoryClass $factoryClass, Editor $editor)
    {
        $classFile = $editor->open($factoryClass->getFilename());
        $classFile->setLineBreak("\n");
        $editor->jumpBelow($classFile, '/^namespace/');

        if (!$editor->hasBelow($classFile, '/Interop\Container\ContainerInterface/')) {
            $editor->insertBelow($classFile, self::NAMESPACE_TO_ADD);
            $editor->jumpBelow($classFile, '/^\s*public function createDelegatorWithName/');

            if ($editor->hasAbove($classFile, '/^\s*\*\//')) {
                $editor->jumpAbove($classFile, '/^\s*\/\*{1,}/');
            }

            $editor->insertAbove($classFile, self::PATCH_TO_APPLY);

            $editor->save($classFile);

            return $classFile;
        }

        throw Exception::fileAlreadyMigrated($factoryClass);
    }



    /**
     * @inheritdoc
     */
    public function canHandle(FactoryClass $factoryClass)
    {
        return strstr($factoryClass->getFilename(), self::FACTORY_FILENAME) !== false && $factoryClass->isDelegator();
    }
}