<?php
    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "src/Book.php";
    // require_once "src/Author.php";
    require_once "src/Copy.php";
    // require_once "src/Patron.php";

    $server = 'mysql:host=localhost;dbname=library_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class CopyTest extends PHPUnit_Framework_TestCase
    {
        protected function tearDown()
        {
            Book::deleteAll();
            // Author::deleteAll();
            Copy::deleteAll();
            // Patron::deleteAll();
        }

        function test_save()
        {
            //Arrange
            $title = "Windup Bird Chronicle";
            $test_book = new Book($title);
            $test_book->save();

            $book_id = $test_book->getId();
            $checked_out = 1;
            $test_copy = new Copy($book_id, $checked_out);
            $test_copy->save();

            //Act
            $result = Copy::getAll();

            //Assert

            $this->assertEquals($test_copy, $result[0]);

        }

        function test_getAll()
        {
            //Arrange
            $title = "Windup Bird Chronicle";
            $test_book = new Book($title);
            $test_book->save();

            $book_id = $test_book->getId();
            $checked_out = 1;
            $test_copy = new Copy($book_id, $checked_out);
            $test_copy->save();

            $book_id2 = $test_book->getId();
            $checked_out2 = 0;
            $test_copy2 = new Copy($book_id2, $checked_out2);
            $test_copy2->save();

            //Act
            $result = Copy::getAll();

            //Assert
            $this->assertEquals([$test_copy, $test_copy2], $result);
        }

        function test_deleteAll()
        {
            //Arrange
            $title = "Windup Bird Chronicle";
            $test_book = new Book($title);
            $test_book->save();

            $book_id = $test_book->getId();
            $checked_out = 1;
            $test_copy = new Copy($book_id, $checked_out);
            $test_copy->save();

            $book_id2 = $test_book->getId();
            $checked_out2 = 0;
            $test_copy2 = new Copy($book_id2, $checked_out2);
            $test_copy2->save();


            //Act
            Copy::deleteAll();
            $result = Copy::getAll();

            //Assert
            $this->assertEquals([], $result);
        }

    }

?>
