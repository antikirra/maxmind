<?php

namespace antikirra\maxmind;

use PharData;
use Exception;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class Updater
{
    private $baseDirectory;
    private $tempDirectory;

    public function __construct()
    {
        $this->baseDirectory = realpath(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR);
        $this->tempDirectory = realpath($this->baseDirectory . DIRECTORY_SEPARATOR . 'temp');
    }

    public function updateCityMMDB()
    {
        $this->updateMMDB('City');
    }

    public function updateCountryMMDB()
    {
        $this->updateMMDB('Country');
    }

    public function update()
    {
        $this->updateCityMMDB();
        $this->updateCountryMMDB();
    }

    private function updateMMDB(string $baseName)
    {
        $baseName = "GeoLite2-{$baseName}";
        $url = "https://geolite.maxmind.com/download/geoip/database/{$baseName}.tar.gz";

        $archive = $this->tempDirectory . DIRECTORY_SEPARATOR . $baseName . '.tar.gz';
        $this->downloadFile($url, $archive);

        $mmdb = $this->baseDirectory . DIRECTORY_SEPARATOR . "{$baseName}.mmdb";

        if (is_file($mmdb)) {
            unlink($mmdb);
        }

        $this->extractMMDB($archive, $mmdb);
    }

    private function rmDirectory(string $dir)
    {
        $files = array_diff(scandir($dir), ['.', '..']);

        foreach ($files as $file) {
            $path = $dir . DIRECTORY_SEPARATOR . $file;
            is_dir($path) ? $this->rmDirectory($path) : unlink($path);
        }

        @rmdir($dir);
    }

    private function downloadFile(string $src, string $dst)
    {
        $fp = fopen($dst, 'w+');
        $ch = curl_init($src);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_exec($ch);
        $success = !curl_errno($ch);
        curl_close($ch);
        fclose($fp);

        return $success;
    }

    private function extractMMDB(string $src, string $dst)
    {
        $temporaryDir = $this->tempDirectory . DIRECTORY_SEPARATOR . time();

        try {
            $phar = new PharData($src);
            $phar->extractTo($temporaryDir);
        } catch (Exception $e) {
            return false;
        } finally {
            unlink($src);
        }

        $directory = new RecursiveDirectoryIterator($temporaryDir);
        $iterator = new RecursiveIteratorIterator($directory);

        foreach ($iterator as $info) {
            $path = $info->getPathname();
            if (preg_match('~\.mmdb$~', $path)) {
                if (is_file($dst)) {
                    unlink($dst);
                }
                copy($path, $dst);
                $this->rmDirectory($temporaryDir);
                return true;
            }
        }

        return false;
    }
}