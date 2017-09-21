<?php

// this is not a real class only for a purpose of testing

namespace Foo;


class DelegatorBarFactory implements DelegatorFactoryInterface {



    /**
     * A factory that creates delegates of a given service
     *
     * @param ServiceLocatorInterface $serviceLocator the service locator which requested the service
     * @param string $name the normalized service name
     * @param string $requestedName the requested service name
     * @param callable $callback the callback that is responsible for creating the service
     *
     * @return mixed
     */
    public function createDelegatorWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName, $callback)
    {
        /** @var RouteStackInterface $router */
        $router = $callback();

        if ($router instanceof TranslatorAwareTreeRouteStack) {
            /** @var Translator $translator */
            $translator = $serviceLocator->get('MvcTranslator');

            if ($translator->getLocale() === Locale::ACH_UG) {
                $translator = new Translator();
            }

            $router->setTranslator($translator);
        }

        return $router;
    }
}