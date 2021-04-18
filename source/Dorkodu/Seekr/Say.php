<?php

namespace Dorkodu\Seekr;

use Exception;
use ArrayAccess;
use ReflectionClass;
use SplObjectStorage;
use Dorkodu\Utils\Str;
use ReflectionException;

use Dorkodu\Seekr\Constraint;

/**
 * Say class provides useful assertions for Seekr tests
 */
class Say
{
  /**
   * RULE :
   * - Write a statement that can be resolved into a boolean value
   * - Then propose it via Say::premise(statement, ...)
   * - IF something goes wrong, it will throw a Contradiction
   * - ELSE everything goes fine, nothing special happens and this means the premise is true
   */

  /**
   * Check if this thing equals to your expectation.
   */

  public static function count(int $expectedCount, $haystack)
  {
    $statement = Constraint::count($expectedCount, $haystack);
    Premise::propose($statement, "Count Does Not Match", "SAY::COUNT");
  }

  public static function notCount(int $expectedCount, $haystack)
  {
    $statement = !Constraint::count($expectedCount, $haystack);
    Premise::propose($statement, "Count Matches", "SAY::NOT_COUNT");
  }

  public static function contains($needle, iterable $haystack)
  {
    $statement =
      (function ($needle, $haystack) {
        if ($haystack instanceof SplObjectStorage) {
          return $haystack->contains($needle);
        }

        foreach ($haystack as $element) {
          if ($needle === $element) {
            return true;
          }
        }

        return false;
      })($needle, $haystack);

    Premise::propose($statement, "Not Contains", "SAY::CONTAINS");
  }

  public static function stringContains(string $needle, string $haystack)
  {
    $statement = Str::contains($haystack, $needle);
    Premise::propose($statement, "String Does Not Contains", "SAY::STRING_CONTAINS");
  }

  public static function null($proposedValue)
  {
    $statement = is_null($proposedValue);
    Premise::propose($statement, "Not Null", "SAY::NULL");
  }

  public static function notNull($proposedValue)
  {
    $statement = !is_null($proposedValue);
    Premise::propose($statement, "Is Null", "SAY::NOT_NULL");
  }

  public static function empty($thing)
  {
    $statement = empty($thing);
    Premise::propose($statement, "Not Empty", "SAY::EMPTY");
  }

  public static function notEmpty($thing)
  {
    $statement = !empty($thing);
    Premise::propose($statement, "Is Empty", "SAY::NOT_EMPTY");
  }

  public static function true($thing)
  {
    $statement = $thing === true;
    Premise::propose($statement, "Is Not True", "SAY::TRUE");
  }

  public static function false($thing)
  {
    $statement = $thing === false;
    Premise::propose($statement, "Is Not False", "SAY::FALSE");
  }

  # COMPARISONS

  public static function equal($expectation, $thing)
  {
    $statement = ($expectation === $thing);
    Premise::propose($statement, "Not Equal", "SAY::EQUAL");
  }

  public static function notEqual($expectation, $thing)
  {
    $statement = ($expectation !== $thing);
    Premise::propose($statement, "Is Equal", "SAY::NOT_EQUAL");
  }

  public static function lessThan($thing, $comparedTo)
  {
    $statement = $thing < $comparedTo;
    Premise::propose($statement, "Is Not Less Than", "SAY::LESS_THAN");
  }

  public static function lessThanOrEqual($thing, $comparedTo)
  {
    $statement = $thing <= $comparedTo;
    Premise::propose($statement, "Is Not Less Than Or Equal", "SAY::LESS_THAN_OR_EQUAL");
  }

  public static function greaterThan($thing, $comparedTo)
  {
    $statement = $thing > $comparedTo;
    Premise::propose($statement, "Is Not Greater Than", "SAY::GREATER_THAN");
  }

  public static function greaterThanOrEqual($thing, $comparedTo)
  {
    $statement = $thing >= $comparedTo;
    Premise::propose($statement, "Is Not Greater Than Or Equal", "SAY::GREATER_THAN_OR_EQUAL");
  }

  # TYPE CONSTRAINTS

  public static function bool($thing)
  {
    $statement = is_bool($thing);
    Premise::propose($statement, "Is Not Bool", "SAY::BOOL");
  }

  public static function int($thing)
  {
    $statement = is_int($thing);
    Premise::propose($statement, "Is Not Integer", "SAY::INTEGER");
  }

  public static function float($thing)
  {
    $statement = is_float($thing);
    Premise::propose($statement, "Is Not Float", "SAY::FLOAT");
  }

  public static function string($thing)
  {
    $statement = is_string($thing);
    Premise::propose($statement, "Is Not String", "SAY::STRING");
  }

  public static function object($thing)
  {
    $statement = is_object($thing);
    Premise::propose($statement, "Is Not Object", "SAY::OBJECT");
  }

  public static function callable($thing)
  {
    $statement = is_callable($thing);
    Premise::propose($statement, "Is Not Callable", "SAY::CALLABLE");
  }

  public static function scalar($thing)
  {
    $statement = is_scalar($thing);
    Premise::propose($statement, "Is Not Scalar", "SAY::SCALAR");
  }

  public static function NaN($thing)
  {
    $statement = is_nan($thing);
    Premise::propose($statement, "Is Not NaN", "SAY::NaN");
  }

  public static function numeric($thing)
  {
    $statement = is_numeric($thing);
    Premise::propose($statement, "Is Not Numeric", "SAY::NUMERIC");
  }

  public static function resource($thing)
  {
    $statement = is_resource($thing);
    Premise::propose($statement, "Is Not Resource", "SAY::RESOURCE");
  }

  public static function iterable($thing)
  {
    $statement = is_iterable($thing);
    Premise::propose($statement, "Is Not Iterable", "SAY::ITERABLE");
  }

  public static function infinite($thing)
  {
    $statement = is_infinite($thing);
    Premise::propose($statement, "Is Not Infinite", "SAY::INFINITE");
  }

  public static function finite($thing)
  {
    $statement = is_finite($thing);
    Premise::propose($statement, "Is Not Finite", "SAY::FINITE");
  }

  # RELATIONS

  public static function instanceOf($object, $className)
  {
    static::object($object);

    $statement = $object instanceof $className;
    Premise::propose($statement, "Is Not Instance Of" . $className, "SAY::INSTANCE_OF");
  }

  public static function hasProperty($object, string $propertyName)
  {
    $statement =
      (function ($o, $n) {
        try {
          return (new ReflectionClass($o))->hasProperty($n);
        } catch (ReflectionException $e) {
          throw new Exception(
            $e->getMessage(),
            (int) $e->getCode(),
            $e
          );
        }
      })($object, $propertyName);

    Premise::propose($statement, "Does Not Have Property", "SAY::HAS_PROPERTY");
  }

  public static function hasMethod($object, string $propertyName)
  {
    $statement = Constraint::hasMethod($object, $propertyName);
    Premise::propose($statement, "Does Not Have Method", "SAY::HAS_METHOD");
  }

  public static function hasNotMethod($object, string $propertyName)
  {
    $statement = Constraint::hasMethod($object, $propertyName);
    Premise::propose($statement, "Does Not Have Method", "SAY::HAS_METHOD");
  }

  public static function hasKey($haystack, $key)
  {
    $statement = Constraint::hasKey($haystack, $key);
    Premise::propose($statement, "Does Not Have Key", "SAY::HAS_KEY");
  }

  public static function notHasKey($haystack, $key)
  {
    $statement = !Constraint::hasKey($haystack, $key);
    Premise::propose($statement, "Has Key", "SAY::HAS_NOT_KEY");
  }

  /*

  public static function ($)
  {
    $statement =
      (function ($o, $n) {
      })($haystack, $key);

    Premise::propose($statement, "", "SAY::");
  }

  */
}
