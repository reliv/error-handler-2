<?php

namespace RcmErrorHandler2\Factory;

use Interop\Container\ContainerInterface;
use RcmErrorHandler2\Config\ErrorDisplayMiddlewareConfig;
use RcmErrorHandler2\Config\ErrorResponseConfig;
use RcmErrorHandler2\Config\ObserverConfig;
use RcmErrorHandler2\Handler\BasicThrowable;
use RcmErrorHandler2\ErrorDisplay\ErrorDisplayFinal;
use Zend\Diactoros\Response\EmitterInterface;

/**
 * Class HandlerThrowableFactory
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
class HandlerThrowableFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface $container
     *
     * @return BasicThrowable
     */
    public function __invoke($container)
    {
        /** @var ObserverConfig $observerConfig */
        $observerConfig = $container->get(ObserverConfig::class);
        /** @var ErrorDisplayMiddlewareConfig $errorDisplayMiddleware */
        $errorDisplayMiddleware = $container->get(ErrorDisplayMiddlewareConfig::class);
        /** @var ErrorDisplayFinal $errorDisplayFinal */
        $errorDisplayFinal = $container->get(\RcmErrorHandler2\ErrorDisplay\ErrorDisplayFinal::class);
        /** @var ErrorResponseConfig $errorResponseConfig */
        $errorResponseConfig = $container->get(ErrorResponseConfig::class);
        /** @var EmitterInterface $emitter */
        $emitter = $container->get('RcmErrorHandler2\Http\Emitter');

        return new BasicThrowable(
            $container,
            $observerConfig->toArray(),
            $errorDisplayMiddleware->toArray(),
            $errorDisplayFinal,
            $errorResponseConfig,
            $emitter
        );
    }
}
