<?php

namespace Dorkodu\Seekr;

use ReflectionClass;
use Dorkodu\Utils\Color;
use Dorkodu\Utils\Console;
use Dorkodu\Seekr\Test\TestResult;

/**
 * The CLI UI output for Seekr
 */
class SeekrUI
{
  private static function resultBadge(bool $isPassed)
  {
    return $isPassed
      ? Color::colorize("bold, fg-white, bg-green", ' PASS ')
      : Color::colorize("bold, fg-white, bg-red", ' FAIL ');
  }
}
