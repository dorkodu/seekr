<?php
  namespace Outkicker;

  class Say
  {
    public static function areEqual()
    {
      # code...
    }
    
    public static function areStrictEqual()
    {
      # code...
    }

    /**
     * Check if this thing equals to your expectation.
     **/
    public function sayAreEqual($expectation, $parameterToTest)
    {
      if ($expectation !== $parameterToTest) 
        return false; 
      else 
        return true;
    }

    public function sayCount(int $expectedCount, $haystack)
    {
      if (count($haystack) !== $expectedCount)
        return false; 
      else
        return true;
    }

    public function sayContains(string $needle, string $haystack)
    {
      if (strstr($haystack, $needle) === false) 
        return false;
      else
        return true;
    }

    public function sayIsNull($proposedValue)
    {
      if (is_null($proposedValue))
        return true;
      else 
        return false;
    }

    public function sayNotNull($proposedValue)
    {
      if (!is_null($proposedValue))
        return true;
      else
        return false;
    }

    public function sayArrayHasKey($key, array $haystack)
    {
      if (array_key_exists($key, $haystack))
        return true;
      else
        return false;
    }

    public function sayEmpty($thing)
    {
      if(empty($thing))
        return true;
      else
        return false;
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

    public function sayArrayStrictEquals($arrayToCompare, $objectYouHave)
    {
      
    }

    public static function sayDirectoryExists(string $path)
    {
      if (is_dir($path))
        return true;
      else
        return false;
    }

    public static function sayFileExists($path)
    {
      if (is_file($path))
        return true;
      else
        return false;
    }
  }
