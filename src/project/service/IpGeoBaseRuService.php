<?php
/**
 * Created by PhpStorm.
 * User: vladimir
 * Date: 12.03.2018
 * Time: 22:52
 */

namespace service;


use object\IpGeoInfo;
use tools\IpGeoBase;

class IpGeoBaseRuService implements IGeoIpInfo
{
    private $geoBase;

    public function __construct(String $citiesFilePath, String $cidrFilePath)
    {
        $this->geoBase = new IpGeoBase($cidrFilePath, $citiesFilePath);
    }

    public function getIpInfo(String $ip): ?IpGeoInfo
    {
        try {
            $info = $this->geoBase->getRecord($ip);
            $info['ip'] = $ip;
            return IpGeoInfo::createFromGeoIpArray($info);
        } catch (\InvalidArgumentException $exception) {
            return null;
        }
    }
}