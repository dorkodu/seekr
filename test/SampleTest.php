<?php

use Dorkodu\Seekr\Say;
use Dorkodu\Seekr\Test\TestCase;
use Exception;

/**
 * A simple Test class with a few tests
 */
class SampleTest extends TestCase
{
  /**
   * This test is empty
   */
  public function testEmpty()
  {
  }

  /**
   * This test is designed to succeed
   */
  public function testOne()
  {
    echo "hello";
  }

  /**
   * This test is designed to fail
   */
  public function testTwo()
  {
    throw new Exception("bug :D", 1);
  }

  /**
   * This test is designed to fail
   */
  public function testThree()
  {
    throw new InvalidArgumentException("Invalid argument ERROR!");
  }

  /**
   * This test is designed to fail
   */
  public function testFour()
  {
    throw new Exception("bug :D", 1);
  }
}
