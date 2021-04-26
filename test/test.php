<?php

error_reporting(E_ERROR);

/**
 * We used Loom dependency utility for autoloading, 
 * but you probably use Composer. Doesn't matter :)
 */
require "loot/loom-weaver.php";

# this is the test file
require_once "SampleTest.php";
require_once "AnotherTest.php";

# we moved functional tests to another file
require_once "function-tests.php";

use Dorkodu\Seekr\Seekr;
use SeekrTests\SampleTest;
use SeekrTests\AnotherTest;

# You can add a test case class by giving an instance of it.
Seekr::testCase(new SampleTest());
Seekr::testCase(new AnotherTest());

# Run Seekr
Seekr::run([
  'hideResults' => true
]);
