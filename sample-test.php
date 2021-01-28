<?php
  require __DIR__ . '/source/Seekr.php';
  require __DIR__ . '/source/Contradiction.php';
  require __DIR__ . '/source/TestResult.php';
  require __DIR__ . '/source/Timer.php';
  require __DIR__ . '/source/Premise.php';
  require __DIR__ . '/source/PerformanceProfiler.php';
  require __DIR__ . '/source/Say.php';
  
  use Seekr\Seekr;
  use Seekr\Say;

  /**
   * A simple Test class with a few tests
   */
  class SampleTest extends Seekr
  {
    public function setUp()
    {
      echo "\nThis is setUp() hook!";
    }

    public function finish()
    {
      echo "\nThis is finish() hook!";
    }

    public function mountedTest()
    {
      echo "\nThis is mountedTest() hook!";
    }

    public function unmountedTest()
    {
      echo "\nThis is unmountedTest() hook!";
    }

    /**
     * This test is empty
     */
    public function testEmpty() { }
    
    /**
     * This test is designed to succeed
     */
    public function testOne()
    {
      Say::equal(1, 1);
    }

    /**
     * This test is designed to fail
     */
    public function testTwo()
    {
      Say::equal(1, 2);
    }

    /**
     * This test is designed to succeed
     */
    public function testComplicated()
    {
      $stack = [];
      
      for ($i = 0; $i < 8000000; $i++) {
          array_push($stack, $i);
      }

      Say::count( 8000000, $stack);
    }
  }

  // this is how to use it.
  $test = new SampleTest();
  $test->runTests();
  $test->seeTestResults();