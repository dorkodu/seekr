<?php

namespace Dorkodu\Seekr\Test;

use Dorkodu\Seekr\Exceptions\ShouldNotHappen;
use Dorkodu\Seekr\Test\TestResult;
use Dorkodu\Seekr\PerformanceProfiler;
use ReflectionClass;
use ReflectionException;

/**
 *  Seekr provides a test case for classes interface for a class
 */
abstract class TestCase
{
  /**
   * HOOKS
   * -------------------------
   * Seekr provides some lifecycle hooks that you can use to catch up with specific moments, 
   * then perform actions that you may want/need
   */

  /**
   * This hook is called before starting to run tests in this test class
   *
   * @return void
   */
  public function setUp()
  {
  }

  /**
   * This hook is called after all tests in this test class have run
   *
   * @return void
   */
  public function finish()
  {
  }

  /**
   * This hook is called before each test of this test class is run
   *
   * @return void
   */
  public function mountedTest()
  {
  }

  /**
   * This hook is called after each test of this test class is run
   *
   * @return void
   */
  public function unmountedTest()
  {
  }
}
