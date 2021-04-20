<?php

namespace Dorkodu\Seekr\Test;

use Closure;
use Exception;
use ReflectionClass;
use ReflectionMethod;
use Dorkodu\Utils\Color;
use Dorkodu\Seekr\Contradiction;
use ReflectionFunction;

/**
 * Provides a loggable entity with information on a test and how it executed
 **/
final class TestResult
{
  /**
   * @var boolean
   */
  private $isSuccess = false;

  /**
   * @var string
   */
  private $output = '';

  /**
   * @var TestFunction|ReflectionMethod
   */
  private $test = null;

  /**
   * @var TestCase
   */
  private $testableInstance = null;

  /**
   * @var Exception
   */
  private $exception = null;

  private $executionTime = null;
  private $peakMemoryUsage = null;

  private function __construct()
  {
  }


  public function isSuccess()
  {
    return $this->isSuccess;
  }

  public function getOutput()
  {
    return $this->output;
  }

  public function setOutput(string $value)
  {
    $this->output = $value;
  }

  public function setExecutionTime(float $value)
  {
    $this->executionTime = $value;
  }

  public function setPeakMemoryUsage($value)
  {
    $this->peakMemoryUsage = $value;
  }

  public function getPeakMemoryUsage()
  {
    return $this->peakMemoryUsage;
  }

  public function getExecutionTime()
  {
    return $this->executionTime;
  }

  public function getTest()
  {
    return $this->test;
  }

  public function getName()
  {
    return $this->test->getName();
  }

  public function getException()
  {
    return $this->exception;
  }

  public function getTestableInstance()
  {
    return $this->testableInstance;
  }

  /** 
   * Creates a failed test result for TestCase 
   * @param ReflectionMethod $test 
   * @param Exception $exception 
   * @param TestCase|null $testCase 
   */
  public static function caseFailed(TestCase $testCase, ReflectionMethod $test, Exception $exception)
  {
    $result = new self();
    $result->isSuccess = false;
    $result->testableInstance = $testCase;
    $result->test = $test;
    $result->exception = $exception;

    return $result;
  }

  /**
   * Creates a successful test result for TestCase
   *
   * @param TestCase $testCase
   * @param ReflectionMethod $test
   */
  public static function caseSucceed(TestCase $testCase, ReflectionMethod $test)
  {
    $result = new self();
    $result->isSuccess = true;
    $result->testableInstance = $testCase;
    $result->test = $test;

    return $result;
  }

  /**
   * @return boolean
   */
  public function isFunctionTest()
  {
    return $this->test instanceof TestFunction;
  }

  /**
   * Creates a failed test result for TestFunction
   *
   * @param TestFunction $test
   * @param Exception $exception
   */
  public static function functionFailed(TestFunction $test, Exception $exception)
  {
    $result = new self();
    $result->isSuccess = false;
    $result->test = $test;
    $result->exception = $exception;

    return $result;
  }

  /**
   * Creates a successful test result for TestFunction
   *
   * @param TestFunction $testCase
   */
  public static function functionSucceed(TestFunction $test)
  {
    $result = new self();
    $result->isSuccess = true;
    $result->test = $test;

    return $result;
  }
}
