<?php

namespace spec\Tworzenieweb\Zf3Check\Service;

use Tworzenieweb\Zf3Check\Service\Finder;
use Tworzenieweb\Zf3Check\Service\Migrator;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Tworzenieweb\Zf3Check\Service\Writer;

/**
 * Class MigratorSpec
 *
 * @package spec\Tworzenieweb\Zf3Check\Service
 * @mixin Migrator
 */
class MigratorSpec extends ObjectBehavior
{
    function let(Writer $writer, Writer\WriterStrategy $writerStrategy)
    {
        $finder = new Finder(new \Symfony\Component\Finder\Finder());
        $this->beConstructedWith($finder, $writer);
        $this->addWritingStrategy($writerStrategy);

        $writerStrategy->canHandle(Argument::which('getClass', 'BarFactory'))->willReturn(true);
        $writerStrategy->canHandle(Argument::which('getClass', 'DelegatorBarFactory'))->willReturn(true);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Migrator::class);
    }

    function it_should_migrate(Writer $writer, Writer\WriterStrategy $writerStrategy)
    {
        $this->migrate(__DIR__ . '/factories');
        $writer->writeIntoClass(Argument::which('getClass', 'BarFactory'), $writerStrategy)->shouldBeCalled();
        $writer->writeIntoClass(Argument::which('getClass', 'DelegatorBarFactory'), $writerStrategy)->shouldBeCalled();
        $writer->writeIntoClass(Argument::which('getClass', 'DontTouchThis'), $writerStrategy)->shouldNotBeCalled();
    }
}
