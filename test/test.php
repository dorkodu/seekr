<?php

error_reporting(E_ERROR);

require_once "loot/loom-weaver.php";
require_once "SampleTest.php";

use Dorkodu\Seekr\Seekr;

Seekr::addTestCase(new SampleTest());
Seekr::run();
