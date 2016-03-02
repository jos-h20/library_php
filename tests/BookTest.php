<?php
    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "src/Book.php";
    require_once "src/Author.php";
    // require_once "src/Copy.php";
    // require_once "src/Patron.php";

    $server = 'mysql:host=localhost;dbname=library_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class BookTest extends PHPUnit_Framework_TestCase
    {
        protected function tearDown()
        {
            Book::deleteAll();
            Author::deleteAll();
            // Copy::deleteAll();
            // Patron::deleteAll();
        }

        function test_save()
        {
            //Arrange
            $title = "Harry Potters Last Stand";
            $id = null;
            $test_book = new Book($title, $id);
            $test_book->save();

            //Act
            $result = Book::getAll();

            //Assert

            $this->assertEquals($test_book, $result[0]);

        }

        function test_getAll()
        {
            //Arrange
            $title = "Harry Potters Last Stand";
            $id = 1;
            $test_book = new Book($title, $id);
            $test_book->save();

            $title2 = "Paolo";
            $id2 = 2;
            $test_book2 = new Book($title2, $id2);
            $test_book2->save();

            //Act
            $result = Book::getAll();

            //Assert
            $this->assertEquals([$test_book, $test_book2], $result);
        }

        function test_deleteAll()
        {
            //Arrange
            $title = "Harry Potters Last Stand";
            $test_book = new Book($title);
            $test_book->save();

            $title2 = "Paolo";
            $test_book2 = new Book($title2);
            $test_book2->save();


            //Act
            Book::deleteAll();
            $result = Book::getAll();

            //Assert
            $this->assertEquals([], $result);
        }

        function test_findBook()
        {
            //Arrange
            $title = "Harry Potters Last Stand";
            $test_book = new Book($title);
            $test_book->save();

            $title2 = "The Alchemists Revenge";
            $test_book2 = new Book($title2);
            $test_book2->save();
            //Act
            $result = Book::findBook($test_book->getId());
            //Assert
            $this->assertEquals($test_book, $result);
        }
    }
?>
