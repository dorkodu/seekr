<?php

namespace SeekrTests;

use Exception;
use Dorkodu\Seekr\Say;
use InvalidArgumentException;
use Dorkodu\Seekr\Test\TestCase;

/**
 * A simple Test class with a few tests
 */
class SampleTest extends TestCase
{
  /**
   * This test will pass
   */
  public function testOne()
  {
  }

  /**
   * This test will fail
   */
  public function testTwo()
  {
    echo "This is the output from a failed test";
    throw new Exception("This is an exception from a failed test.");
  }

  /**
   * This test will fail
   */
  public function testThree()
  {
    echo "This is the output from a failed test";
    throw new Exception("This is an exception from a failed test.");
  }
}
