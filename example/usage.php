#!/usr/bin/env php
<?php

require __DIR__ . '/../vendor/autoload.php';

$reader = new \antikirra\maxmind\Reader();

######################

$country = $reader->getCountry('104.25.118.102');

echo "Country ISO: " . $country->isoCode . PHP_EOL; // US
echo "Country Name: " . $country->name . PHP_EOL; // United States
echo "Local Country Name: " . $country->names['ru'] . PHP_EOL; //  США

echo PHP_EOL;

######################

$city = $reader->getCity('198.199.112.197');

echo "City GEO ID: " . $city->geonameId . PHP_EOL; // 5391959
echo "City Name: " . $city->name . PHP_EOL; // San Francisco
echo "Local City Name: " . $city->names['ru'] . PHP_EOL; // Сан-Франциско

echo PHP_EOL;

######################

// !!! IMPORTANT (destroy MMDB file descriptors)
unset($reader);

######################

$updater = new \antikirra\maxmind\Updater();

$updater->update(); # update Country and City bases

//$updater->updateCountryMMDB(); # update only Country base
//$updater->updateCityMMDB(); # update only City base

