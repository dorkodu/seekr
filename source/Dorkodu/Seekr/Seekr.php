<?php

namespace Dorkodu\Seekr;

use Dorkodu\Seekr\Test\{
  TestCase,
  TestResult,
  TestFunction,
  TestRepository
};

use Dorkodu\Seekr\Utils\PerformanceProfiler;

use Dorkodu\Utils\Color;
use Dorkodu\Utils\Console;

use Closure;
use Dorkodu\Utils\TerminalUI;
use ReflectionClass;
use Exception;

/**
 * Seekr - a minimalist PHP testing library for writing better tests easily and wisely.
 * 
 * @author     Doruk Eray (@dorkodu) <doruk@dorkodu.com>
 * @copyright  (c) 2021, Doruk Eray
 * @link       <https://github.com/dorkodu/seekr>
 * @license    The MIT License (MIT)
 */
final class Seekr
{
  /**
   * Current test repository to be run
   * @var TestRepository|null
   */
  private static $repo = null;

  /**
   * Holds the TestResult logs.
   */
  private static $log = array(
    'success' => array(),
    'failure' => array()
  );

  private static $successCount = 0;
  private static $failureCount = 0;

  public static $showOnlyFailures = false;

  /**
   * Seekr will only show failures, if you call this.
   *
   * @param boolean $value
   * @return void
   */
  public static function showOnlyFailures(bool $value = true)
  {
    self::$showOnlyFailures = $value;
  }

  public static function successCount()
  {
    return static::$successCount;
  }

  public static function failureCount()
  {
    return static::$failureCount;
  }

  private static function newRepositoryIfEmpty()
  {
    if (!(static::$repo instanceof TestRepository) || is_null(static::$repo)) {
      static::$repo = new TestRepository();
    }
  }

  /**
   * Resets Seekr's all settings and test repository
   *
   * @return void
   */
  public static function reset()
  {
    static::$repo = new TestRepository();

    static::$log = array(
      'success' => array(),
      'failure' => array()
    );

    static::$successCount = 0;
    static::$failureCount = 0;

    static::$showOnlyFailures = false;
  }

  /**
   * Add a TestFunction to be run
   *
   * @param TestFunction $test
   */
  public static function addTestFunction(TestFunction $test)
  {
    static::newRepositoryIfEmpty();
    static::$repo->addFunction($test);
  }

  /**
   * Add a TestCase to be run
   *
   * @param TestCase $test
   */
  public static function addTestCase(TestCase $test)
  {
    static::newRepositoryIfEmpty();
    static::$repo->addCase($test);
  }
}
