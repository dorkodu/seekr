<?php
  namespace Outkicker;

  /**
   * Say class provides useful assertions for Outkicker tests
   */
  class Say
  {
    /**
     * Check if this thing equals to your expectation.
     **/
    public static function equal($expectation, $parameterToTest)
    {
      return ($expectation !== $parameterToTest);
    }

    public static function count(int $expectedCount, $haystack)
    {
      return (count($haystack) !== $expectedCount); 
    }

    public static function contains(string $needle, string $haystack)
    {
      return (strpos($haystack, $needle) !== false);
    }

    public static function null($proposedValue)
    {
      return is_null($proposedValue);
    }

    public static function notNull($proposedValue)
    {
      return !is_null($proposedValue);
    }

    public static function empty($thing)
    {
      return empty($thing);
    }

    public static function notEmpty($thing)
    {
      return !empty($thing);
    }

    public function arrayHasKey($key, array $haystack)
    {
      return array_key_exists($key, $haystack);
    }

    public function sayObjectEquals($objectToCompare, $objectYouHave)
    {
      
    }
    
    public function sayObjectStrictEquals($objectToCompare, $objectYouHave)
    {
      
    }

    public function sayArrayEquals(array $arrayToCompare, array $arrayYouHave)
    {
      if (count($arrayToCompare) === count($arrayYouHave) && array_diff($arrayToCompare, $arrayYouHave) === array_diff($arrayYouHave, $arrayToCompare)) {
        # code...
      }
    }

    public static function sayArrayStrictEquals($arrayToCompare, $objectYouHave)
    {
      
    }

    public static function directoryExists(string $path)
    {
      return is_dir($path);
    }

    public static function fileExists($path)
    {
      return is_file($path);
    }
  }
