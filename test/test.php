<?php

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

# You can also write functional tests by giving a description and a callback 
Seekr::test("a simple test callback.", function () {
  throw new Exception("This is an exception by");
});

# Run Seekr
Seekr::run();
