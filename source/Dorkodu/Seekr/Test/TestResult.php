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
}
