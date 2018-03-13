<?php
/**
 * Created by PhpStorm.
 * User: vladimir
 * Date: 13.03.2018
 * Time: 10:52
 */

namespace tools;


use Slim\Http\Request;
use Slim\Http\Response;
use Symfony\Component\Cache\Simple\FilesystemCache;

final class CacheRestRouteMiddleware
{
    private $time;
    private $cache;

    public function __construct($cacheDir, int $time = 1800)
    {
        $this->time = $time;
        $this->cache = new FilesystemCache('route', $time, $cacheDir);
    }

    public function __invoke(Request $request, Response $response, $next)
    {
        if ($request->getUri()->getPath() !== '/ip2geo') {
            return $next($request, $response);
        }

        $key = md5($request->getRequestTarget());
        $value = null;
        if ($this->cache->has($key)) {
            $value = $this->cache->get($key);
            $response = $response
                ->withHeader('Content-Type', 'application/json;charset=utf-8')
                ->withHeader('X-Cache', 'HIT')
                ->write($value);

            return $response;
        }
        $response = $next($request, $response);
        $response = $response->withHeader('X-Cache', 'MISS');
        $this->cache->set($key, (string)$response->getBody());
        return $response;
    }
}