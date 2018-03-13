<?php
/**
 * Created by PhpStorm.
 * User: vladimir
 * Date: 12.03.2018
 * Time: 23:17
 */

namespace controller;


use FastRoute\BadRouteException;
use Psr\Container\ContainerInterface;
use service\IGeoIpInfo;
use Slim\Exception\NotFoundException;
use Slim\Http\Request;
use Slim\Http\Response;
use Webmozart\Assert\Assert;

class IpGeoController
{
    private $geoIpInfo;

    public function __construct(IGeoIpInfo $geoIpInfo)
    {
        $this->geoIpInfo = $geoIpInfo;
    }

    public function get(Request $request, Response $response, $args) {
        $ip = $request->getParam('ip');
        try {
            Assert::notNull($ip, 'ip address not found');
            Assert::integer(preg_match('/([0-9]{1,3}[\.]){3}[0-9]{1,3}/', $ip), 'invalid ip address');
        } catch (\InvalidArgumentException $exception) {
            return $response->withStatus(400)->withJson($exception->getMessage());
        }


        $ip_info = $this->geoIpInfo->getIpInfo($ip);
        if(!$ip_info) {
            return $response->withStatus(404)->withJson([]);
        }
        return $response->withJson($ip_info->jsonSerialize());
    }
}