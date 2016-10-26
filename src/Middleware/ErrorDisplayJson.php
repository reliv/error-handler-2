<?php

namespace RcmErrorHandler2\Middleware;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use RcmErrorHandler2\Config\ErrorResponseConfig;
use RcmErrorHandler2\Http\BasicErrorResponse;
use RcmErrorHandler2\Http\ErrorRequest;
use RcmErrorHandler2\Http\ErrorResponse;
use RcmErrorHandler2\Service\ErrorExceptionExtractor;
use Zend\Diactoros\Stream;

/**
 * Class ErrorDisplayJson
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
class ErrorDisplayJson extends ErrorDisplayAbstract implements ErrorDisplay
{
    /**
     * @var ErrorResponseConfig
     */
    protected $errorResponseConfig;

    /**
     * ErrorDisplayJson constructor.
     *
     * @param ErrorResponseConfig $errorResponseConfig
     */
    public function __construct(
        ErrorResponseConfig $errorResponseConfig
    ) {
        $this->errorResponseConfig = $errorResponseConfig;
    }

    /**
     * __invoke
     *
     * @param ErrorRequest  $request
     * @param ErrorResponse $response
     * @param callable|null $next
     *
     * @return ErrorResponse
     */
    public function __invoke(RequestInterface $request, ResponseInterface $response, $next = null)
    {
        $errorException = $request->getError();

        if (!$request->hasHeader('accept')) {
            return $next($request, $response);
        }

        $headerValues = $request->getHeader('accept');

        if (!in_array('application/json', $headerValues)) {
            return $next($request, $response);
        }

        // @todo Could move this to a formatter
        $localMessage = 'An unhandled exception has occurred in: ' . self::class . ' from: ';
        $result = ErrorExceptionExtractor::extractArray($errorException);
        $result['message'] = $localMessage . $result['message'];
        $content = json_encode($result, JSON_PRETTY_PRINT);

        $body = $response->getBody();
        $body->write($content);

        // only our error please
        $response = new BasicErrorResponse(
            $body,
            $this->errorResponseConfig->get('status'),
            ['content-type', ['application/json']], false
        );

        return $response;
    }
}