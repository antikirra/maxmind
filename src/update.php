#!/usr/bin/env php
<?php

require __DIR__ . '/../vendor/autoload.php';

(new \antikirra\maxmind\Updater())->update();