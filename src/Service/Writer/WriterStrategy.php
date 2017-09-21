<?php


namespace Tworzenieweb\Zf3Check\Service\Writer;


use Gnugat\Redaktilo\Editor;
use Tworzenieweb\Zf3Check\Model\FactoryClass;

interface WriterStrategy
{
    /**
     * @param FactoryClass $factoryClass
     * @param Editor $editor
     * @return mixed
     */
    public function writeIntoClass(FactoryClass $factoryClass, Editor $editor);



    /**
     * @param FactoryClass $factoryClass
     * @return bool
     */
    public function canHandle(FactoryClass $factoryClass);
}