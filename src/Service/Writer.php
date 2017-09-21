<?php

namespace Tworzenieweb\Zf3Check\Service;

use Gnugat\Redaktilo\Editor;
use Tworzenieweb\Zf3Check\Model\FactoryClass;
use Tworzenieweb\Zf3Check\Service\Writer\WriterStrategy;

class Writer
{
    /** @var Editor */
    private $editor;

    /**
     * Writer constructor.
     * @param Editor $editor
     */
    public function __construct(Editor $editor)
    {
        $this->editor = $editor;
    }

    /**
     * @param FactoryClass $factoryClass
     * @param WriterStrategy $strategy
     */
    public function writeIntoClass(FactoryClass $factoryClass, WriterStrategy $strategy)
    {
        $strategy->writeIntoClass($factoryClass, $this->editor);
    }
}
