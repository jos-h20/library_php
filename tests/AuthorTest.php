<?php
    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */
    require_once "src/Author.php";
    // require_once "src/Book.php";
    // require_once "src/Copy.php";
    // require_once "src/Patron.php";

    $server = 'mysql:host=localhost;dbname=library_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class AuthorTest extends PHPUnit_Framework_TestCase
    {
        protected function tearDown()
        {
            Author::deleteAll();
            // Book::deleteAll();
            // Copy::deleteAll();
            // Patron::deleteAll();
        }

        function test_save()
        {
            //Arrange
            $first_name = "J.K.";
            $last_name = "Rowling";
            $test_author = new Author($first_name, $last_name);
            $test_author->save();

            //Act
            $result = Author::getAll();

            //Assert

            $this->assertEquals($test_author, $result[0]);

        }

        function test_getAll()
        {
            //Arrange
            $first_name = "J.K.";
            $last_name = "Rowling";
            $test_author = new Author($first_name, $last_name);
            $test_author->save();

            $first_name2 = "Paolo";
            $last_name2 = "Coehlo";
            $test_author2 = new Author($first_name2, $last_name2);
            $test_author2->save();

            //Act
            $result = Author::getAll();

            //Assert
            $this->assertEquals([$test_author, $test_author2], $result);
        }

        function test_deleteAll()
        {
            //Arrange
            $first_name = "J.K.";
            $last_name = "Rowling";
            $test_author = new Author($first_name, $last_name);
            $test_author->save();

            $first_name2 = "Paolo";
            $last_name2 = "Coehlo";
            $test_author2 = new Author($first_name2, $last_name2);
            $test_author2->save();


            //Act
            Author::deleteAll();
            $result = Author::getAll();

            //Assert
            $this->assertEquals([], $result);
        }

        function test_findAuthor()
        {
            //Arrange
            $first_name = "J.K.";
            $last_name = "Rowling";
            $test_author = new Author($first_name, $last_name);
            $test_author->save();

            $first_name2 = "Paolo";
            $last_name2 = "Coehlo";
            $test_author2 = new Author($first_name2, $last_name2);
            $test_author2->save();
            //Act
            $result = Author::findAuthor($test_author->getId());
            //Assert
            $this->assertEquals($test_author, $result);
        }

        // function test_getBooks()
        // {
        //     //Arrange
        //
        //     $first_name = "J.K.";
        //     $last_name = "Rowling";
        //     $test_author = new Author($first_name, $last_name);
        //     $test_author->save();
        //
        //     $title = "Harry Potter and the Prisoner of Azkaban";
        //     $test_book = new Book($title);
        //     $test_book->save();
        //
        //     $title = "Harry Potter and the Order of the Phoenix";
        //     $test_book2 = new Book($title);
        //     $test_book2->save();
        //
        //     //Act
        //     $test_course->addStudent($test_book->getId());
        //     $test_course->addStudent($test_book2->getId());
        //     $result = $test_author->getBooks();
        //
        //     //Assert
        //     $this->assertEquals([$test_book, $test_book2], $result);
        // }
        //
        // function test_addBook()
        // {
        //     //Arrange
        //     $first_name = "J.K.";
        //     $last_name = "Rowling";
        //     $test_author = new Author($first_name, $last_name);
        //     $test_author->save();
        //
        //     $title = "Harry Potter and the Prisoner of Azkaban";
        //     $test_book = new Book($title);
        //     $test_book->save();
        //
        //     //Act
        //     $test_author->addBook($test_book->getId());
        //
        //     //Assert
        //     $this->assertEquals($test_author->getBooks(), [$test_book]);
        // }

    }

?>
