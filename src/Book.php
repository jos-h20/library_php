<?php
    class Book
    {
        private $title;
        private $id;

        function __construct($title, $id = null)
        {
            $this->title = $title;
            $this->id = $id;
        }
        function setTitle($new_title)
        {
            $this->title = $new_title;
        }
        function getTitle()
        {
            return $this->title;
        }
        function getId()
        {
            return $this->id;
        }
        function save()
        {
            $GLOBALS['DB']->exec(
            "INSERT INTO books (title) VALUES ('{$this->getTitle()}');");
            $this->id = $GLOBALS['DB']->lastInsertId();
        }

        static function getAll()
        {
            $returned_books = $GLOBALS['DB']->query("SELECT * FROM books;");
            $books = array();

            foreach($returned_books as $book) {
                $title = $book['title'];
                $id = $book['id'];
                $new_book = new Book($title, $id);
                array_push($books, $new_book);
            }
            // var_dump($books);
            return $books;
        }

        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM books");
        }

        static function findBook($search_id)
        {
            $found_book = null;
            $books = Book::getAll();
            foreach($books as $book) {
                $book_id = $book->getId();
                if ($book_id == $search_id) {
                  $found_book = $book;
                }
            }
            return $found_book;
        }
        static function findBookTitle($search_title)
        {
            $found_book = null;
            $books = Book::getAll();
            foreach($books as $book) {
                $book_id = $book->getId();
                if ($book_id == $search_id) {
                  $found_book = $book;
                }
            }
            return $found_book;
        }

        function getAuthors()
        {
            $query = $GLOBALS['DB']->query(
                "SELECT authors.*
                FROM books
                JOIN books_authors ON (books.id = books_authors.book_id)
                JOIN authors ON (books_authors.author_id = authors.id)
                WHERE books.id = {$this->getId()};"
            );

            $authors = array();
            foreach($query as $author){
                $first_name = $author['first_name'];
                $last_name = $author['last_name'];
                $id = $author['id'];
                $new_author = new Author($first_name, $last_name, $id);

                array_push($authors, $new_author);
            }
            return $authors;
        }

        function addAuthor($author_id)
        {
            $GLOBALS['DB']->exec("INSERT INTO books_authors (book_id, author_id) VALUES ({$this->getId()}, {$author_id});");
        }
        function delete()
        {
            $GLOBALS['DB']->exec("DELETE FROM books WHERE id = {$this->getId()};");
            $GLOBALS['DB']->exec("DELETE FROM books_authors WHERE book_id = {$this->getId()};");
        }
        function update($new_title)
        {
            $GLOBALS['DB']->exec("UPDATE books SET title = '{$new_title}' WHERE id = {$this->getId()};");
            $this->setTitle($new_title);
        }
        function addCopy()
        {
            $GLOBALS['DB']->exec("INSERT INTO copies (book_id, checked_out) VALUES ({$this->getId()}, 0);");
        }



        function getNumberOfCopies()
        {
            $query = $GLOBALS['DB']->query("SELECT * FROM copies WHERE book_id = {$this->getId()};");
            $copies = $query->fetchAll(PDO::FETCH_ASSOC);
            $count = 0;
            foreach ($copies as $copy) {
                $count++;
            }  return $count;
        }




    }



?>
