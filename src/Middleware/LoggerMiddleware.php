<?php

namespace App\Middleware;

use App\Factory\LoggerFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Routing\RouteContext;

/**
 * Logger middleware.
 */
final class LoggerMiddleware implements MiddlewareInterface
{

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param LoggerFactory $loggerFactory The loggerFactory
     */
    public function __construct(LoggerFactory $loggerFactory)
    {
        $this->logger = $loggerFactory
        ->addFileHandler('RouteLog.log')
        ->createLogger('RouteLogMiddleware');
    }
    /**
     * Invoke middleware.
     *
     * @param ServerRequestInterface $request The request
     * @param RequestHandlerInterface $handler The handler
     * 
     * @return ResponseInterface The response
     */
    public function process(
        ServerRequestInterface $request, 
        RequestHandlerInterface $handler
    ): ResponseInterface {
        $routeContext = RouteContext::fromRequest($request);
        $routingResults = $routeContext->getRoutingResults();
        $route = $routeContext->getRoute();

        $name = $routingResults->getUri();
        $methods = $route->getMethods();
        $body = $request->getBody()->getContents()?? "";
        $body = str_replace('    ', "",$body);

        $loggerString = $methods[0]." ".$name;
        $loggerString .= $body ? " body: " . $body : ""; 

        $this->logger->info($loggerString);
        $response = $handler->handle($request);
        return $response;
    }
}
