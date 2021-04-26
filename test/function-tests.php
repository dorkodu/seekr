<?php

error_reporting(E_ERROR);

/**
 * We used Loom dependency utility for autoloading, 
 * but you probably use Composer. Doesn't matter :)
 */
require "loot/loom-weaver.php";

use Dorkodu\Seekr\Seekr;

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
  # throw new Exception("Error Processing Request", 1);
});
