<?php

namespace Tworzenieweb\Zf3Check\Model;

use Symfony\Component\Console\Exception\LogicException;

class Exception extends LogicException
{
    /**
     * @param FactoryClass $class
     * @return Exception
     */
    public static function fileAlreadyMigrated(FactoryClass $class)
    {
        return new self(sprintf('Provided factory class %s was already migrated', $class->getClass()));
    }
}