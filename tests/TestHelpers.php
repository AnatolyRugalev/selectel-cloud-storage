<?php

use ArgentCrusade\Selectel\CloudStorage\Api\ApiClient;
use GuzzleHttp\Message\Response;

class TestHelpers
{
    public static function mockApi($methods = [], $callback = null)
    {
        if (is_callable($methods)) {
            $callback = $methods;
            $methods = [];
        }

        if (empty($methods)) {
            $methods = ['request'];
        }

        $methods[] = 'storageUrl';

        $api = Mockery::mock(ApiClient::class.'['.implode(',', $methods).']', ['username', 'password']);
        $api->shouldReceive('storageUrl')->atLeast()->once()->andReturn('http://xxx.selcdn.ru');

        if (is_callable($callback)) {
            $callback($api);
        }

        return $api;
    }

    public static function toResponse($body, $status = 200, array $headers = [])
    {
        $stream = new \GuzzleHttp\Stream\BufferStream();
        $stream->write((!is_string($body) ? json_encode($body) : $body));
        return new Response($status, $headers, $stream);
    }
}
