<?php
/**
 * Created by PhpStorm.
 * User: vladimir
 * Date: 12.03.2018
 * Time: 22:47
 */

namespace service;


use object\IpGeoInfo;

interface IGeoIpInfo
{
    public function getIpInfo(String $ip): ?IpGeoInfo;
}