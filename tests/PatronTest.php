<?php
    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */
    require_once "src/Patron.php";
    require_once "src/Copy.php";
    require_once "src/Book.php";
    // require_once "src/Author.php";

    $server = 'mysql:host=localhost;dbname=library_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class PatronTest extends PHPUnit_Framework_TestCase
    {
        protected function tearDown()
        {
            Patron::deleteAll();
            Copy::deleteAll();
            // Copy::deleteAll();
            // Patron::deleteAll();
        }

        function test_save()
        {
            //Arrange
            $name = "J.K.";
            $email_address = "Rowling";
            $test_patron = new Patron($name, $email_address);
            $test_patron->save();

            //Act
            $result = Patron::getAll();

            //Assert

            $this->assertEquals($test_patron, $result[0]);

        }

        function test_getAll()
        {
            //Arrange
            $name = "J.K.";
            $email_address = "Rowling";
            $test_patron = new Patron($name, $email_address);
            $test_patron->save();

            $name2 = "Paolo";
            $email_address2 = "Coehlo";
            $test_patron2 = new Patron($name2, $email_address2);
            $test_patron2->save();

            //Act
            $result = Patron::getAll();

            //Assert
            $this->assertEquals([$test_patron, $test_patron2], $result);
        }

        function test_deleteAll()
        {
            //Arrange
            $name = "J.K.";
            $email_address = "Rowling";
            $test_patron = new Patron($name, $email_address);
            $test_patron->save();

            $name2 = "Paolo";
            $email_address2 = "Coehlo";
            $test_patron2 = new Patron($name2, $email_address2);
            $test_patron2->save();


            //Act
            Patron::deleteAll();
            $result = Patron::getAll();

            //Assert
            $this->assertEquals([], $result);
        }

        function test_findPatron()
        {
            //Arrange
            $name = "J.K.";
            $email_address = "Rowling";
            $test_patron = new Patron($name, $email_address);
            $test_patron->save();

            $name2 = "Paolo";
            $email_address2 = "Coehlo";
            $test_patron2 = new Patron($name2, $email_address2);
            $test_patron2->save();
            //Act
            $result = Patron::findPatron($test_patron->getId());
            //Assert
            $this->assertEquals($test_patron, $result);
        }

        function test_getCopies()
        {
            //Arrange

            $name = "J.K.";
            $email_address = "Rowling";
            $test_patron = new Patron($name, $email_address);
            $test_patron->save();

            $book_id = 1;
            $checked_out = 1;
            $test_copy = new Copy($book_id, $checked_out);
            $test_copy->save();

            $book_id2 = 1;
            $checked_out2 = 0;
            $test_copy2 = new Copy($book_id2, $checked_out2);
            $test_copy2->save();

            //Act
            $test_patron->addCopies($test_copy->getId());
            $test_patron->addCopies($test_copy2->getId());
            $result = $test_patron->getCopies();

            //Assert
            $this->assertEquals([$test_copy, $test_copy2], $result);
        }

        function test_addCopies()
        {
            //Arrange
            $name = "J.K.";
            $email_address = "Rowling";
            $test_patron = new Patron($name, $email_address);
            $test_patron->save();

            $book_id = 1;
            $checked_out = 1;
            $test_copy = new Copy($book_id, $checked_out);
            $test_copy->save();

            //Act
            $test_patron->addCopies($test_copy->getId());

            //Assert
            $this->assertEquals($test_patron->getCopies(), [$test_copy]);
        }

    }

?>