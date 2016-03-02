<?php
    class Patron
    {
        private $name;
        private $email_address;
        private $id;

        function __construct($name, $email_address, $id = null)
        {
            $this->name = $name;
            $this->email_address = $email_address;
            $this->id = $id;
        }

        function setName($new_name)
        {
            $this->name = $new_name;
        }

        function getName()
        {
            return $this->name;
        }

        function setEmailAddress($new_email_address)
        {
            $this->email_address = $new_email_address;
        }

        function getEmailAddress()
        {
            return $this->email_address;
        }

        function getId()
        {
            return $this->id;
        }

        function save()
        {
            $GLOBALS['DB']->exec(
            "INSERT INTO patrons (name, email_address)
            VALUES ('{$this->getName()}', '{$this->getEmailAddress()}')");
            $this->id = $GLOBALS['DB']->lastInsertId();
        }

        static function getAll()
        {
            $returned_patrons = $GLOBALS['DB']->query("SELECT * FROM patrons;");
            $patrons = array();
            foreach($returned_patrons as $patron) {
                $name = $patron['name'];
                $email_address = $patron['email_address'];
                $id = $patron['id'];
                $new_patron = new Patron($name, $email_address, $id);
                array_push($patrons, $new_patron);
            }
            return $patrons;
        }

        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM patrons");
        }

        static function findPatron($search_id)
        {
            $found_patron = null;
            $patrons = Patron::getAll();
            foreach($patrons as $patron) {
                $patron_id = $patron->getId();
                if ($patron_id == $search_id) {
                  $found_patron = $patron;
                }
            }
            return $found_patron;
        }

        function getCopies()
        {
            $query = $GLOBALS['DB']->query(
                "SELECT copies.*
                FROM patrons
                JOIN checkouts ON (patrons.id = checkouts.patrons_id)
                JOIN copies ON (checkouts.copies_id = copies.id)
                WHERE patrons.id = {$this->getId()};"
            );

            $copies = array();
            foreach($query as $copy){
                $book_id = $copy['book_id'];
                $checked_out = $copy['checked_out'];
                $due_date = $copy['due_date'];
                $id = $copy['id'];
                $new_copy = new Copy($book_id, $checked_out, $due_date, $id);

                array_push($copies, $new_copy);
            }
            return $copies;
        }

        function addPatronCopies($copies_id)
        {
            $GLOBALS['DB']->exec("INSERT INTO checkouts (patrons_id, copies_id) VALUES ({$this->getId()}, {$copies_id});");
        }
    }
?>
