<?php
    class Copy
    {
        private $book_id;
        private $checked_out;
        private $due_date;
        private $id;

        function __construct($book_id, $checked_out, $due_date, $id = null)
        {
            $this->book_id = $book_id;
            $this->checked_out = $checked_out;
            $this->due_date = $due_date;
            $this->id = $id;
        }

        function getBookId()
        {
            return $this->book_id;
        }

        function setCheckedOut($new_checked_out)
        {
            $this->checked_out = $new_checked_out;
        }

        function getCheckedOut()
        {
            return $this->checked_out;
        }

        function setDueDate($new_due_date)
        {
            $this->due_date = $new_due_date;
        }

        function getDueDate()
        {
            return $this->due_date;
        }

        function getId()
        {
            return $this->id;
        }

        function save()
        {
            $GLOBALS['DB']->exec(
            "INSERT INTO copies (book_id, checked_out, due_date)
            VALUES (
                {$this->getBookId()},
                {$this->getCheckedOut()},
                '{$this->getDueDate()}'
                )
            ");
            $this->id = $GLOBALS['DB']->lastInsertId();
        }

        static function getAll()
        {
            $returned_copies = $GLOBALS['DB']->query("SELECT * FROM copies;");
            $copies = array();
            foreach($returned_copies as $copy) {
                $book_id = $copy['book_id'];
                $checked_out = $copy['checked_out'];
                $due_date = $copy['due_date'];
                $id = $copy['id'];
                $new_copy = new Copy($book_id, $checked_out, $due_date, $id);
                array_push($copies, $new_copy);
            }
            return $copies;
        }

        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM copies");
        }

    }


?>
