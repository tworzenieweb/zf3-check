<?php

namespace spec\Tworzenieweb\Zf3Check\Model;

use Tworzenieweb\Zf3Check\Model\FactoryClass;
use PhpSpec\ObjectBehavior;

class FactoryClassSpec extends ObjectBehavior
{
    const CLASS_CONTENT = 'SOME DUMMY CLASS CONTENT';

    function let()
    {
        $this->beConstructedWith(__DIR__ . DIRECTORY_SEPARATOR . 'BarFactory.php');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(FactoryClass::class);
    }

    function it_should_get_namespace_and_class_from_file()
    {
        $this->getClass()->shouldBe('BarFactory');
        $this->getNamespace()->shouldBe('Foo');
    }
}
