<?php
    class Author
    {
        private $first_name;
        private $last_name;
        private $id;

        function __construct($first_name, $last_name, $id = null)
        {
            $this->first_name = $first_name;
            $this->last_name = $last_name;
            $this->id = $id;
        }

        function setFirstName($new_first_name)
        {
            $this->first_name = $new_first_name;
        }

        function getFirstName()
        {
            return $this->first_name;
        }

        function setLastName($new_last_name)
        {
            $this->last_name = $new_last_name;
        }

        function getLastName()
        {
            return $this->last_name;
        }

        function getId()
        {
            return $this->id;
        }

        function save()
        {
            $GLOBALS['DB']->exec(
            "INSERT INTO authors (first_name, last_name)
            VALUES ('{$this->getFirstName()}', '{$this->getLastName()}')");
            $this->id = $GLOBALS['DB']->lastInsertId();
        }

        static function getAll()
        {
            $returned_authors = $GLOBALS['DB']->query("SELECT * FROM authors;");
            $authors = array();
            foreach($returned_authors as $author) {
                $first_name = $author['first_name'];
                $last_name = $author['last_name'];
                $id = $author['id'];
                $new_author = new Author($first_name, $last_name, $id);
                array_push($authors, $new_author);
            }
            return $authors;
        }

        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM authors");
        }

        static function findAuthor($search_id)
        {
            $found_author = null;
            $authors = Author::getAll();
            foreach($authors as $author) {
                $author_id = $author->getId();
                if ($author_id == $search_id) {
                  $found_author = $author;
                }
            }
            return $found_author;
        }

        function getBooks()
        {
            $query = $GLOBALS['DB']->query(
                "SELECT books.*
                FROM authors
                JOIN books_authors ON (authors.id = books_authors.author_id)
                JOIN books ON (books_authors.book_id = books.id)
                WHERE authors.id = {$this->getId()};"
            );

            $books = array();
            foreach($query as $book){
                $title = $book['title'];
                $id = $book['id'];
                $new_book = new Book($title, $id);

                array_push($books, $new_book);
            }
            return $books;
        }

        function addBook($book_id)
        {
            $GLOBALS['DB']->exec("INSERT INTO books_authors (author_id, book_id) VALUES ({$this->getId()}, {$book_id});");
        }
    }
?>
