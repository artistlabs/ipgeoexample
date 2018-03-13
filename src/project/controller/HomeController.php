<?php
/**
 * Created by PhpStorm.
 * User: vladimir
 * Date: 12.03.2018
 * Time: 23:20
 */

namespace controller;


use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class HomeController
{
    public function index(RequestInterface $request, ResponseInterface $response, array $args) {
        return $response->withStatus(200)->getBody()->write('IpGeoBase example');
    }
}