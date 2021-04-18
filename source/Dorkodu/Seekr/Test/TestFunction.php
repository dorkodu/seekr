<?php

namespace Dorkodu\Seekr\Test;

use Dorkodu\Seekr\TestResult;
use Dorkodu\Seekr\PerformanceProfiler;

use Closure;
use ReflectionClass;
use ReflectionException;

/**
 *  TestFuntion represents a single test closure
 */
final class TestFunction
{
  /**
   * Description/Name of the callback
   *
   * @var string
   */
  protected $description = "";

  /**
   * Holds the test callback
   *
   * @var Closure
   */
  protected $callback;

  /**
   * Constructs a new TestFuntion.
   */
  public function __construct(string $description, Closure $callback)
  {
    $this->description = $description;
    $this->callback = $callback;
  }

  public function isSuccess()
  {
    return $this->isSuccess;
  }

  /**
   * Returns a string representation of the test method.
   */
  public function description()
  {
    return $this->description;
  }

  private function isCallable()
  {
    return is_callable($this->callback);
  }

  public function callback()
  {
    return $this->callback;
  }
}
