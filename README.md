GeoLite2 MMDB Updater
=================

Install
-------
```bash
composer require antikirra\maxmind
```

Download latest MMDB
--------------------
```php
<?php

require __DIR__ . '/vendor/autoload.php';

(new \antikirra\maxmind\Updater())->update();
```

Search country or city by IP address
---------------------------------
```php
<?php

require __DIR__ . '/vendor/autoload.php';

$reader = new \antikirra\maxmind\Reader();

$country = $reader->getCountry('104.25.118.102');

echo "Country ISO: " . $country->isoCode . PHP_EOL; // US
echo "Country Name: " . $country->name . PHP_EOL; // United States
echo "Local Country Name: " . $country->names['ru'] . PHP_EOL; //  США

echo PHP_EOL;

$city = $reader->getCity('198.199.112.197');

echo "City GEO ID: " . $city->geonameId . PHP_EOL; // 5391959
echo "City Name: " . $city->name . PHP_EOL; // San Francisco
echo "Local City Name: " . $city->names['ru'] . PHP_EOL; // Сан-Франциско

echo PHP_EOL;
```