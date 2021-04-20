<?php

error_reporting(E_ERROR);

require "loot/loom-weaver.php";
require_once "SampleTest.php";
# require_once "../source/Dorkodu/Seekr/Seekr.php";

use Dorkodu\Seekr\Seekr;
// use SeekrTest\Samples\Joke\SampleTest;

Seekr::testCase(new SampleTest());

Seekr::test("it has nothing.", function () {
  throw new Exception("Bir Error.");
});

Seekr::run();
