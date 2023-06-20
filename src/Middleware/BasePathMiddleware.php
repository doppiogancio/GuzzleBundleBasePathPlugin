<?php

namespace Doppiogancio\Bundle\GuzzleBundleBasePathPlugin\Middleware;

use Psr\Http\Message\RequestInterface;

class BasePathMiddleware
{
    /**
     * @var string
     */
    private $basePath;

    public function __construct(string $basePath)
    {
        $this->basePath = $basePath;
    }

    /**
     * @param callable $handler
     *
     * @return \Closure
     */
    public function __invoke(callable $handler) : \Closure
    {
        return function (
            RequestInterface $request,
            array $options
        ) use ($handler) {
            $uri = $request->getUri();
            $uri = $uri->withPath(sprintf('%s%s', $this->basePath, $uri->getPath()));

            $request = $request->withUri($uri);

            // Continue the handler chain.
            return $handler($request, $options);
        };
    }
}