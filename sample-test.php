<?php
    require __DIR__ . '/source/Seekr.php';
    require __DIR__ . '/source/Contradiction.php';
    require __DIR__ . '/source/TestResult.php';
    require __DIR__ . '/source/Timer.php';
    require __DIR__ . '/source/Premise.php';
    require __DIR__ . '/source/Say.php';
    
    use Seekr\Seekr;
    use Seekr\Say;

    /**
     * a simple Test suite with two tests
     **/
    class SeekrTest extends Seekr
    {
      /**
       * This test is designed to succeed
       */
      public function testOne()
      {
        Say::equal( 2, 2 );
      }

      /**
       * This test is designed to fail
       */
      public function testTwo()
      {
        Say::equal( 1, 2 );
      }

      /**
       * This test is designed to succeed
       */
      public function testComplicated()
      {
        $stack = [];
        
        for ($i = 0; $i < 10000000; $i++) {
            array_push($stack, $i);
        }

        Say::count( 10000000, $stack);
      }
    }

    // this is how to use it.
    $test = new SeekrTest();
    $test->runTests();
    $test->seeTestResults();