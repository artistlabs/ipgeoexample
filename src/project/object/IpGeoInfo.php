<?php
/**
 * Created by PhpStorm.
 * User: vladimir
 * Date: 12.03.2018
 * Time: 22:50
 */

namespace object;


use Webmozart\Assert\Assert;

class IpGeoInfo implements \JsonSerializable
{
    private $ip;
    private $cityIndex;
    private $cityName;
    private $region;
    private $country;
    private $longitude;
    private $latitude;

    public function __construct($ip, String $cityName, String $region, String $country, float $longitude, float $latitude, ?String $cityCode)
    {
        $this->ip = $ip;
        $this->cityIndex = $cityCode;
        $this->cityName = $cityName;
        $this->region = $region;
        $this->country = $country;
        $this->longitude = $longitude;
        $this->latitude = $latitude;
    }

    /**
     * @return mixed
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @return mixed
     */
    public function getCityIndex()
    {
        return $this->cityIndex;
    }

    /**
     * @return mixed
     */
    public function getCityName()
    {
        return $this->cityName;
    }

    /**
     * @return mixed
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @return int
     */
    public function getLongitude(): String
    {
        return $this->longitude;
    }

    /**
     * @return int
     */
    public function getLatitude(): String
    {
        return $this->latitude;
    }

    public function jsonSerialize()
    {
        return [
            'city'      => $this->getCityName(),
            'region'    => $this->getRegion(),
            'country'   => $this->getCountry(),
            'latitude'  => $this->getLatitude(),
            'longitude' => $this->getLongitude()
        ];
    }


    /**
     * IpGeoBase ru
     *
     * @param $info
     * @return IpGeoInfo
     */
    public static function createFromGeoIpArray($info): IpGeoInfo {
        Assert::keyExists($info, 'index');
        Assert::numeric($info['index']);
        Assert::keyExists($info, 'city');
        Assert::keyExists($info, 'region');
        Assert::keyExists($info, 'ip');
        Assert::keyExists($info, 'lng');
        Assert::keyExists($info, 'lat');

        return new self($info['ip'], $info['city'], $info['region'], $info['cc'], $info['lng'], floatval($info['lat']), floatval($info['index']));
    }
}