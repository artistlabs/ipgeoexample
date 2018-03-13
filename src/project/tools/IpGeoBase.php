<?php
/**
 * Created by PhpStorm.
 * User: vladimir
 * Date: 18.01.2018
 * Time: 13:18
 */

namespace tools;


class IpGeoBase
{
    private $fhandleCIDR, $fhandleCities, $fSizeCIDR, $fsizeCities, $outputEncoding;

    const DEFAULT_FILE_ENCODING = 'windows-1251';
    const DEFAULT_OUTPUT_ENCODING = 'UTF-8';

    public function __construct($CIDRFile = null, $CitiesFile = null, $outputEncoding = self::DEFAULT_OUTPUT_ENCODING)
    {
        $CIDRFile = $CIDRFile ?: dirname(__FILE__) . '/cidr_optim.txt';
        $CitiesFile = $CitiesFile ?: dirname(__FILE__) . '/cities.txt';

        $this->outputEncoding = $outputEncoding;

        if (!file_exists($CIDRFile) ||
            !($this->fhandleCIDR = fopen($CIDRFile, 'r'))) {
            throw new \RuntimeException("Cannot access CIDR file: $CIDRFile");
        }
        if (!file_exists($CitiesFile) ||
            !($this->fhandleCities = fopen($CitiesFile, 'r'))) {
            throw new \RuntimeException("Cannot access cities file: $CitiesFile");
        }

        $this->fSizeCIDR = filesize($CIDRFile);
        $this->fsizeCities = filesize($CitiesFile);
    }

    private function getCityByIdx($idx)
    {
        rewind($this->fhandleCities);

        while (!feof($this->fhandleCities)) {
            $str = fgets($this->fhandleCities);
            $arRecord = explode("\t", trim($str));
            if ($arRecord[0] == $idx) {
                $outputConverter = function ($string) {
                    return iconv(self::DEFAULT_FILE_ENCODING, $this->outputEncoding, $string);
                };

                return array_map($outputConverter, array(
                        'city' => $arRecord[1],
                        'region' => $arRecord[2],
                        'district' => $arRecord[3]
                    )) + array(
                        'lat' => $arRecord[4],
                        'lng' => $arRecord[5]
                    );
            }
        }

        return false;
    }

    public function getRecord($ip)
    {
        $ip = sprintf('%u', ip2long($ip));

        rewind($this->fhandleCIDR);
        $rad = floor($this->fSizeCIDR / 2);
        $pos = $rad;
        while (fseek($this->fhandleCIDR, $pos, SEEK_SET) != -1) {

            if ($rad) {
                fgets($this->fhandleCIDR);
            } else {
                rewind($this->fhandleCIDR);
            }

            $str = fgets($this->fhandleCIDR);

            if (!$str) {
                return false;
            }

            $arRecord = explode("\t", trim($str));

            $rad = floor($rad / 2);
            if (!$rad && ($ip < $arRecord[0] || $ip > $arRecord[1])) {
                return false;
            }

            if ($ip < $arRecord[0]) {
                $pos -= $rad;
            } elseif ($ip > $arRecord[1]) {
                $pos += $rad;
            } else {
                $result = array('range' => $arRecord[2], 'cc' => $arRecord[3], 'index' => $arRecord[4]);

                if ($arRecord[4] != '-' && $cityResult = $this->getCityByIdx($arRecord[4])) {
                    $result += $cityResult;
                }

                return $result;
            }
        }
        return false;
    }
}