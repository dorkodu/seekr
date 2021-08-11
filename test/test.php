<?php

error_reporting(E_ERROR);

/**
 * We used Loom dependency utility for autoloading, 
 * but you probably use Composer. Doesn't matter :)
 */
require "loot/loom-weaver.php";

// this is the test file
require_once "SampleTest.php";
require_once "AnotherTest.php";

use Dorkodu\Seekr\Seekr;
use Dorkodu\Seekr\Test\TestFunction;
use Dorkodu\Seekr\Test\TestRepository;

/**
 * You can write functional tests by giving a description and a callback 
 */

use SeekrTests\SampleTest;
use SeekrTests\AnotherTest;

// initialize the test repository
$repo = new TestRepository();

// add test cases
$repo->case(new SampleTest());

/**
 * You can also write functional tests 
 * by giving a description and a callback 
 */
$repo->function(
  "a failing test callback.",
  function () {
    echo "This is the output of a failed test.";
    throw new Exception("This is an exception from a failed test.");
  }
);

$repo->function(
  "a passing test callback.",
  function () {
    echo "This test will pass :)";
  }
);

Seekr::setRepository($repo);

// You can add a test case class by giving an instance of it.
Seekr::testCase(new SampleTest());
Seekr::testCase(new AnotherTest());

// Run Seekr
Seekr::run([
  'detailed' => 1
]);
