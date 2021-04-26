<?php

namespace SeekrTests;

use Exception;
use Dorkodu\Seekr\Say;
use InvalidArgumentException;
use Dorkodu\Seekr\Test\TestCase;

/**
 * Another Test class with a few tests
 */
class AnotherTest extends TestCase
{
  /**
   * This test will pass
   */
  public function testWillPass()
  {
    echo "This will pass.";
  }

  /**
   * This test will fail
   */
  public function testWillFail()
  {
    echo "This is the output from a failed test";
  }
}
