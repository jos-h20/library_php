<?php
    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/Author.php";
    require_once __DIR__."/../src/Book.php";
    require_once __DIR__."/../src/Copy.php";
    require_once __DIR__."/../src/Patron.php";

    $server = 'mysql:host=localhost;dbname=library';
	$username = 'root';
	$password = 'root';
	$DB = new PDO($server, $username, $password);

    $app = new Silex\Application();

    $app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views'
    ));

    use Symfony\Component\HttpFoundation\Request;
    Request::enableHttpMethodParameterOverride();

    $app->get("/", function() use ($app) {
        return $app['twig']->render('index.html.twig');
    });

    $app->get("/librarians", function() use ($app) {
        return $app['twig']->render('books.html.twig', array('books' => Book::getAll()));
    });

    $app->get("/patrons", function() use ($app) {
        return $app['twig']->render('patrons.html.twig', array('patrons' => Patron::getAll()));
    });

    //////LIBRARIAN/////////
    // $app->post("/add_book", function() use ($app) {
    //     $title = $_POST['title'];
    //     $id = null;
    //     $new_book = new Book($title, $id);
    //     $new_book->save();
    //     return $app['twig']->render('books.html.twig', array('books' => Book::getAll()));
    // });
    $app->post("/delete_all_books", function() use ($app) {
        Book::deleteAll();
        return $app['twig']->render('books.html.twig', array('books' => Book::getAll()));
    });
    $app->get("/book/{id}", function($id) use($app) {
        $book = Book::findBook($id);
        return $app['twig']->render('book.html.twig', array('book' => $book, 'authors' => $book->getAuthors(), 'all_authors' => Author::getAll()));
    });
    // $app->post("/book_add_author", function() use ($app) {
    //     $author = Author::findAuthor($_POST['author_id']);
    //     $book = Book::findBook($_POST['book_id']);
    //     $book->addAuthor($author);
    //     return $app['twig']->render('book.html.twig', array('book' => $book, 'authors' => $book->getAuthors(), 'all_authors' => Author::getAll()));
    // });


    $app->post("/books/add_book", function() use ($app) {
        $title = $_POST['title'];
        $new_book = new Book($title);
        $new_book->save();
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $new_author = new Author($first_name, $last_name);
        $new_author->save();
        $new_book->addAuthor($new_author->getId());
        return $app['twig']->render('books.html.twig', array('books' => Book::getAll()));
    });


    $app->patch("/book/{id}/update", function($id) use ($app) {
        $book = Book::findBook($id);
        $new_title = $_POST['new_title'];
        $book->update($new_title);
        return $app['twig']->render('book.html.twig', array('book' => $book, 'authors' => $book->getAuthors(), 'all_authors' => Author::getAll()));
    });
    $app->delete("/book/{id}/delete", function($id) use ($app) {
        $book = Book::findBook($id);
        $book->delete();
        return $app['twig']->render("books.html.twig", array('books' => Book::getAll()));
    });
////// LIBRARIANS PATRONS PAGE///////
    // $app->post("/add_patron", function() use ($app) {
    //     $name = $_POST['name'];
    //     $email_address = $_POST['email_address'];
    //     $id = null;
    //     $new_patron = new Patron($name, $email_address, $id);
    //     $new_patron->save();
    //     return $app['twig']->render('patrons.html.twig', array('patrons' => Patron::getAll()));
    // });
    // $app->post("/delete_all_patrons", function() use ($app) {
    //     Patron::deleteAll();
    //     return $app['twig']->render('patrons.html.twig', array('patrons' => Patron::getAll()));
    // });
    // $app->get("/patron/{id}", function($id) use($app) {
    //     $patron = Patron::findPatron($id);
    //     return $app['twig']->render('patron.html.twig', array('patron' => $patron, 'copies' => $patron->getCopies(), 'all_copies' => Copy::getAll()));
    // });
    // $app->post("/patron_add_copy", function() use ($app) {
    //     $copy = Copy::findCopy($_POST['copy_id']);
    //     $patron = Patron::findPatron($_POST['patron_id']);
    //     $patron->addCopy($copy);
    //     return $app['twig']->render('patron.html.twig', array('patron' => $patron, 'copies' => $patron->getCopies(), 'all_copies' => Copy::getAll()));
    // });
    // $app->patch("/patron/{id}/update", function($id) use ($app) {
    //     $patron = Patron::findPatron($id);
    //     $new_name = $_POST['new_name'];
    //     $new_email_address = $_POST['new_email_address'];
    //     $patron->update($new_name, $new_email_address);
    //     return $app['twig']->render('patron.html.twig', array('patron' => $patron, 'copies' => $patron->getCopies(), 'all_copies' => Copy::getAll()));
    // });
    // $app->delete("/patron/{id}/delete", function($id) use ($app) {
    //     $patron = Patron::findPatron($id);
    //     $patron->delete();
    //     return $app['twig']->render("patrons.html.twig", array('patrons' => Patron::getAll()));
    // });


    return $app;
?>
