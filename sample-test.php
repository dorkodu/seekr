<?php
    require __DIR__ . '/Outkicker.php';
    require __DIR__ . '/Kickable.php';
    require __DIR__ . '/TestResult.php';
    require __DIR__ . '/Say.php';
    
    use Outkicker\Outkicker;
    use Outkicker\Say;

    /**
     * a simple Test suite with two tests
     **/
    class MyTest extends Outkicker
    {
        /**
         * This test is designed to fail
         **/
        public function testOne()
        {
            Say::equal( 2, 2 );
        }

        /**
         * This test is designed to succeed
         **/
        public function testTwo()
        {
            Say::equal( 1, 1 );
        }
    }

    // this is how to use it.
    $test = new MyTest();
    $test->runTests();