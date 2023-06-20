<?php

namespace Doppiogancio\Bundle\GuzzleBundleBasePathPlugin\Tests\Middleware;

use Doppiogancio\Bundle\GuzzleBundleBasePathPlugin\Middleware\BasePathMiddleware;
use DoppioGancio\MockedClient\HandlerBuilder;
use DoppioGancio\MockedClient\MockedGuzzleClientBuilder;
use DoppioGancio\MockedClient\Route\RouteBuilder;
use GuzzleHttp\Client;
use Http\Discovery\Psr17FactoryDiscovery;
use PHPUnit\Framework\TestCase;

class BasePathMiddlewareTest extends TestCase
{
    public function testRequestWithMiddleware(): void
    {
        $res = $this->getClient()->get('/countries/it');
        $country = json_decode($res->getBody()->getContents(), true);
        $this->assertEquals('Italy', $country['name']);
    }

    private function getClient(): Client
    {
        $handler = new HandlerBuilder(
            Psr17FactoryDiscovery::findServerRequestFactory()
        );

        $route = new RouteBuilder(
            Psr17FactoryDiscovery::findResponseFactory(),
            Psr17FactoryDiscovery::findStreamFactory()
        );

        $handler->addRoute(
            $route->new()
                ->withMethod('GET')
                ->withPath('/api/v3/countries/it')
                ->withStringResponse('{"name": "Italy"}')
                ->build()
        );

        $client = new MockedGuzzleClientBuilder($handler);
        $client->addMiddleware(new BasePathMiddleware('/api/v3'));

        return $client->build();
    }
}