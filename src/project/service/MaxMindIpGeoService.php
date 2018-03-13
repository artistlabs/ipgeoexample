<?php
/**
 * Created by PhpStorm.
 * User: vladimir
 * Date: 13.03.2018
 * Time: 10:22
 */

namespace service;


use GeoIp2\Database\Reader;
use GeoIp2\Exception\AddressNotFoundException;
use object\IpGeoInfo;

class MaxMindIpGeoService implements IGeoIpInfo
{
    private $reader;

    public function __construct(String $pathToBase)
    {
        $this->reader = new Reader($pathToBase);
    }

    public function getIpInfo(String $ip): ?IpGeoInfo
    {
        try {
            $record = $this->reader->city($ip);
        } catch (AddressNotFoundException $exception) {
            return null;
        }

        return new IpGeoInfo(
            $ip,
            $record->city->name,
            $record->mostSpecificSubdivision->name,
            $record->country->name,
            $record->location->longitude,
            $record->location->latitude,
            $record->postal->code
        );
    }
}