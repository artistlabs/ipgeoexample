<?php
use service\IpGeoBaseRuService;

define("PROJECT_DIR", __DIR__.'/..');
define("VENDOR_DIR", PROJECT_DIR.'/../vendor');

require VENDOR_DIR.'/autoload.php';

$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;

$app = new \Slim\App([
    'settings' => $config,
]);

$container = $app->getContainer();
$container[\service\IGeoIpInfo::class] = function($container) {
    //todo uncomment for use ipgeobase.ru bases
    //return new IpGeoBaseRuService(VENDOR_DIR.'/ipgeobase/data/cities.txt', VENDOR_DIR.'/ipgeobase/data/cidr_optim.txt');
    return new \service\MaxMindIpGeoService(VENDOR_DIR.'/ipgeomaxmind/data/GeoLite2-City.mmdb');
};

$container[\controller\HomeController::class] = function () {
    return new \controller\HomeController();
};

$container[\controller\IpGeoController::class] = function (\Psr\Container\ContainerInterface $container) {
    return new \controller\IpGeoController($container->get(\service\IGeoIpInfo::class));
};

$app->add(new \tools\CacheRestRouteMiddleware(PROJECT_DIR.'/../data'));

$app->get('/', \controller\HomeController::class.':index');

$app->get('/ip2geo', \controller\IpGeoController::class.':get');

$app->run();
