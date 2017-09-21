<?php

namespace Tworzenieweb\Zf3Check\Model;

class FactoryClass
{
    private $filename;

    private $content;
    private $class;
    private $namespace;
    private $isDelegator;



    public function __construct($filename)
    {
        $this->isDelegator = false;
        if (!file_exists($filename)) {
            throw new \RuntimeException(sprintf('Provided file %s not found', $filename));
        }

        $this->filename = $filename;
        $this->content = file_get_contents($filename);

        preg_match('/\s*namespace ([^;]+);/', $this->content, $namespace);
        preg_match('/\s*class ([\w\d_]+Factory)/', $this->content, $class);
        $this->isDelegator = strstr($this->content, 'DelegatorFactoryInterface');

        $this->class = $class[1];
        $this->namespace = $namespace[1];
    }

    /**
     * @return mixed
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @return mixed
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * @return mixed
     */
    public function getFilename()
    {
        return $this->filename;
    }



    public function isDelegator()
    {
        return $this->isDelegator;
    }
}
