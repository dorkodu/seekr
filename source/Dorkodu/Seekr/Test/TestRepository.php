<?php

declare(strict_types=1);

namespace Dorkodu\Seekr\Test;

use Dorkodu\Seekr\Test\TestCase;
use Dorkodu\Seekr\Test\TestFunction;

final class TestRepository
{
  /**
   * Holds the test case instances.
   *
   * @var TestCase[]
   */
  private $_testCases = array();

  /**
   * Holds the single test methods
   *
   * @var TestFunction[]
   */
  private $_testFunctions = array();

  public function case(TestCase $testCase)
  {
    array_push($this->_testCases, $testCase);
  }

  public function function(TestFunction $test)
  {
    array_push($this->_testFunctions, $test);
  }

  public function isEmpty()
  {
    return empty($this->_testCases) &&
      empty($this->testMethods);
  }

  public function hasAnyTestCases()
  {
    return !empty($this->_testCases);
  }

  public function hasAnyTestMethods()
  {
    return !empty($this->testMethods);
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
