<?php

namespace Dorkodu\Seekr;

use Exception;
use ArrayAccess;
use ReflectionClass;
use SplObjectStorage;
use Dorkodu\Utils\Str;
use ReflectionException;

/**
 * Constraint class provides useful conditions for easy to use.
 */
class Constraint
{
  /**
   * RULE :
   * - Take parameters and decide if they satisfy the requirements of the constraint
   */

  /**
   * Check if this thing equals to your expectation.
   */

  public static function count(int $expectedCount, $haystack)
  {
    return (count($haystack) === $expectedCount);
  }

  public static function contains($needle, iterable $haystack)
  {
    if ($haystack instanceof SplObjectStorage) {
      return $haystack->contains($needle);
    }

    foreach ($haystack as $element) {
      if ($needle === $element) {
        return true;
      }
    }

    return false;
  }

  public static function stringContains(string $needle, string $haystack)
  {
    return Str::contains($haystack, $needle);
  }

  public static function null($proposedValue)
  {
    return is_null($proposedValue);
  }

  public static function notNull($proposedValue)
  {
    return !static::null($proposedValue);
  }

  public static function empty($thing)
  {
    return empty($thing);
  }

  public static function notEmpty($thing)
  {
    return !static::empty($thing);
  }

  public static function true($thing)
  {
    return $thing === true;
  }

  public static function false($thing)
  {
    return $thing === false;
  }

  # COMPARISONS

  public static function equal($expectation, $thing)
  {
    return ($expectation === $thing);
  }

  public static function notEqual($expectation, $thing)
  {
    return ($expectation !== $thing);
  }

  public static function lessThan($thing, $comparedTo)
  {
    return $thing < $comparedTo;
  }

  public static function lessThanOrEqual($thing, $comparedTo)
  {
    return $thing <= $comparedTo;
  }

  public static function greaterThan($thing, $comparedTo)
  {
    return $thing > $comparedTo;
  }

  public static function greaterThanOrEqual($thing, $comparedTo)
  {
    return $thing >= $comparedTo;
  }

  # TYPE CONSTRAINTS

  public static function bool($thing)
  {
    return is_bool($thing);
  }

  public static function int($thing)
  {
    return is_int($thing);
  }

  public static function float($thing)
  {
    return is_float($thing);
  }

  public static function string($thing)
  {
    return is_string($thing);
  }

  public static function object($thing)
  {
    return is_object($thing);
  }

  public static function callable($thing)
  {
    return is_callable($thing);
  }

  public static function scalar($thing)
  {
    return is_scalar($thing);
  }

  public static function NaN($thing)
  {
    return is_nan($thing);
  }

  public static function numeric($thing)
  {
    return is_numeric($thing);
  }

  public static function resource($thing)
  {
    return is_resource($thing);
  }

  public static function iterable($thing)
  {
    return is_iterable($thing);
  }

  public static function infinite($thing)
  {
    return is_infinite($thing);
  }

  public static function finite($thing)
  {
    return is_finite($thing);
  }

  # STRING

  public static function matchesRegularExpression(string $pattern): RegularExpression
  {
    return new RegularExpression($pattern);
  }

  public static function matches(string $string): StringMatchesFormatDescription
  {
    return new StringMatchesFormatDescription($string);
  }

  public static function stringStartsWith($prefix): StringStartsWith
  {
    return new StringStartsWith($prefix);
  }

  public static function stringContains(string $string, bool $case = true): StringContains
  {
    return new StringContains($string, $case);
  }

  public static function stringEndsWith(string $suffix): StringEndsWith
  {
    return new StringEndsWith($suffix);
  }

  public static function countOf(int $count): Count
  {
    return new Count($count);
  }

  public static function objectEquals(object $object, string $method = 'equals'): ObjectEquals
  {
    return new ObjectEquals($object, $method);
  }

  # RELATIONS

  public static function instanceOf($object, $className)
  {
    return ($object instanceof $className) && static::object($object);
  }

  public static function hasProperty($object, string $propertyName)
  {
    try {
      return (new ReflectionClass($object))->hasProperty($propertyName);
    } catch (ReflectionException $e) {

      throw new Exception(
        $e->getMessage(),
        (int) $e->getCode(),
        $e
      );
    }
  }

  public static function hasMethod($object, string $propertyName)
  {
    try {
      return (new ReflectionClass($object))->hasMethod($propertyName);
    } catch (ReflectionException $e) {
      throw new Exception(
        $e->getMessage(),
        (int) $e->getCode(),
        $e
      );
    }
  }

  public static function hasKey($haystack, $key)
  {
    if (is_array($haystack)) {
      return array_key_exists($haystack, $key);
    }

    if ($haystack instanceof ArrayAccess) {
      return $haystack->offsetExists($key);
    }

    return false;
  }

  public static function hasValue($haystack, $value)
  {
    return self::contains($value, $haystack);
  }

  private static function isValidObjectAttributeName(string $attributeName): bool
  {
    return (bool) preg_match('/[^\x00-\x1f\x7f-\x9f]+/', $attributeName);
  }

  private static function isValidClassAttributeName(string $attributeName): bool
  {
    return (bool) preg_match('/[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*/', $attributeName);
  }
}
