<?php

namespace antikirra\maxmind;

class Reader
{
    /**
     * @var \GeoIp2\Database\Reader
     */
    private $cityReader;

    /**
     * @var Reader
     */
    private $countryReader;

    private $baseDirectory;

    public function __construct()
    {
        $this->baseDirectory = realpath(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
    }

    /**
     * @param string $address
     * @return \GeoIp2\Record\Country
     * @throws \GeoIp2\Exception\AddressNotFoundException
     * @throws \MaxMind\Db\Reader\InvalidDatabaseException
     */
    public function getCountry(string $address)
    {
        if ($this->countryReader === null) {
            $this->countryReader = new \GeoIp2\Database\Reader($this->baseDirectory . 'GeoLite2-Country.mmdb');
        }

        return $this->countryReader->country($address)->country;
    }

    /**
     * @param string $address
     * @return \GeoIp2\Record\City
     * @throws \GeoIp2\Exception\AddressNotFoundException
     * @throws \MaxMind\Db\Reader\InvalidDatabaseException
     */
    public function getCity(string $address)
    {
        if ($this->cityReader === null) {
            $this->cityReader = new \GeoIp2\Database\Reader($this->baseDirectory . 'GeoLite2-City.mmdb');
        }

        return $this->cityReader->city($address)->city;
    }
}