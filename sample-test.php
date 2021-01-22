<?php
    require __DIR__ . '/source/Outkicker.php';
    require __DIR__ . '/source/Contradiction.php';
    require __DIR__ . '/source/TestResult.php';
    require __DIR__ . '/source/Timer.php';
    require __DIR__ . '/source/Say.php';
    
    use Outkicker\Outkicker;
    use Outkicker\Say;

    /**
     * a simple Test suite with two tests
     **/
    class UITest extends Outkicker
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
       * This test is designed to fail
       */
      public function testComplicated()
      {
        Say::equal( 1, 2 );
        
        $stack = [];
        
        for ($i = 0; $i < 10000000; $i++) {
            array_push($stack, $i);
        }

        Say::count( 10000000, $stack);
      }
    }

    // this is how to use it.
    $test = new UITest();
    $test->runTests();
    $test->seeTestResults();