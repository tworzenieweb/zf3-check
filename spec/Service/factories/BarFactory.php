<?php

// this is not a real class only for a purpose of testing

namespace Foo;


class BarFactory implements FactoryInterface {



    public function createService(ServiceContainerInterface $container)
    {
        return new Bar();
    }
}