<?php

error_reporting(E_ERROR);

/**
 * We used Loom dependency utility for autoloading, 
 * but you probably use Composer. Doesn't matter :)
 */
require "loot/loom-weaver.php";

# this is the test file
require_once "SampleTest.php";

use Dorkodu\Seekr\Seekr;

# You can add a test case class by giving an instance of it.
Seekr::testCase(new SampleTest());

/**
 * You can also write functional tests 
 * by giving a description and a callback 
 */
Seekr::test("a failing test callback.", function () {
  echo "This is the output of a failed test.";
  throw new Exception("This is an exception from a failed test.");
});

Seekr::test("a passing test callback.", function () {
  echo "This test will pass :)";
});

# Run Seekr
Seekr::run();
