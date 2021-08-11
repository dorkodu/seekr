<?php

declare(strict_types=1);

namespace Dorkodu\Seekr\Test;

use Closure;
use Dorkodu\Seekr\Test\TestCase;
use Dorkodu\Seekr\Test\TestFunction;

class TestRepository
{
  /**
   * Holds the test case instances.
   *
   * @var TestCase[]
   */
  private $_testCases = [];

  /**
   * Holds the single test methods
   *
   * @var TestFunction[]
   */
  private $_testFunctions = [];

  public function case(TestCase $testCase)
  {
    array_push($this->_testCases, $testCase);
  }

  public function function(string $description, Closure $callback)
  {
    $test = new TestFunction($description, $callback);
    array_push($this->_testFunctions, $test);
  }

  public function isEmpty()
  {
    return empty($this->_testCases) &&
      empty($this->_testFunctions);
  }

  public function hasAnyTestCases()
  {
    return !empty($this->_testCases);
  }

  public function hasAnyTestMethods()
  {
    return !empty($this->_testFunctions);
  }

  public function testCases()
  {
    return $this->_testCases;
  }


  public function testFunctions()
  {
    return $this->_testFunctions;
  }
}
