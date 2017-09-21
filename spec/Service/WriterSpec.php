<?php

namespace spec\Tworzenieweb\Zf3Check\Service;

use Gnugat\Redaktilo\EditorFactory;
use Tworzenieweb\Zf3Check\Model\FactoryClass;
use Tworzenieweb\Zf3Check\Service\Writer;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class WriterSpec
 *
 * @package spec\Tworzenieweb\Zf3Check\Service
 * @mixin Writer
 */
class WriterSpec extends ObjectBehavior
{
    const FIXTURES_PATH = __DIR__ . DIRECTORY_SEPARATOR . '/fixtures/';

    private $tests = [
        'BarFactory.php',
        'BarWithCommentFactory.php',
        'DelegatorBarFactory.php',
    ];

    private $fixturesData = [];



    function let()
    {
        foreach ($this->tests as $filename) {
            $tempFile = tempnam(__DIR__, 'writer_');
            file_put_contents($tempFile, file_get_contents(self::FIXTURES_PATH . $filename));
            $expectedFileContent = file_get_contents(self::FIXTURES_PATH . $filename . '.expected');

            $this->fixturesData[$filename] = [
                'tempFile' => $tempFile,
                'expectedContent' => $expectedFileContent,
            ];
        }

        $editor = EditorFactory::createEditor();
        $this->beConstructedWith($editor);
    }



    function letGo()
    {
        foreach ($this->fixturesData as $node) {
            unlink($node['tempFile']);
        }
    }



    function it_is_initializable()
    {
        $this->shouldHaveType(Writer::class);
    }



    function it_should_add_zf3_factory_method(FactoryClass $factoryClass)
    {
        $filename = 'BarFactory.php';
        $factoryClass->getFilename()->willReturn($this->fixturesData[$filename]['tempFile']);
        $this->writeIntoClass($factoryClass, new Writer\FactoryUpgrade());
        expect(file_get_contents($this->fixturesData[$filename]['tempFile']))->shouldBeEqualTo(
            $this->fixturesData[$filename]['expectedContent']
        );
    }



    function it_should_add_zf3_factory_method_for_above_comment_block(FactoryClass $factoryClass)
    {
        $filename = 'BarWithCommentFactory.php';
        $factoryClass->getFilename()->willReturn($this->fixturesData[$filename]['tempFile']);
        $this->writeIntoClass($factoryClass, new Writer\FactoryUpgrade());
        expect(file_get_contents($this->fixturesData[$filename]['tempFile']))->shouldBeEqualTo(
            $this->fixturesData[$filename]['expectedContent']
        );
    }



    function it_should_add_zf3_delegator_method(FactoryClass $factoryClass)
    {
        $filename = 'DelegatorBarFactory.php';
        $factoryClass->getFilename()->willReturn($this->fixturesData[$filename]['tempFile']);
        $this->writeIntoClass($factoryClass, new Writer\DelegatorFactoryUpgrade());
        expect(file_get_contents($this->fixturesData[$filename]['tempFile']))->shouldBeEqualTo(
            $this->fixturesData[$filename]['expectedContent']
        );
    }
}
