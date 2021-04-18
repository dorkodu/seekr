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
}
