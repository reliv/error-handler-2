<?php

namespace RcmErrorHandler2\Factory;

use Interop\Container\ContainerInterface;
use RcmErrorHandler2\Formatter\HtmlTraceFormatter;

/**
 * HtmlTraceFormatterFactory
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
class HtmlTraceFormatterFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface $container
     *
     * @return HtmlTraceFormatter
     */
    public function __invoke($container)
    {
        return new HtmlTraceFormatter();
    }
}
