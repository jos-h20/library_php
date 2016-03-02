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

    $app->get("/books", function() use ($app) {
        return $app['twig']->render('books.html.twig', array('books' => Book::getAll()));
    });

    $app->get("/patrons", function() use ($app) {
        return $app['twig']->render('patrons.html.twig');
    });

    //////LIBRARIAN/////////
    $app->post("/add_book", function() use ($app) {
        $title = $_POST['title'];
        $id = null;
        $new_book = new Book($title, $id);
        $new_book->save();
        return $app['twig']->render('books.html.twig', array('books' => Book::getAll()));
    });
    $app->post("/delete_all_books", function() use ($app) {
        Book::deleteAll();
        return $app['twig']->render('books.html.twig', array('books' => Book::getAll()));
    });
    $app->get("/book/{id}", function($id) use($app) {
        $book = Book::findBook($id);
        return $app['twig']->render('book.html.twig', array('book' => $book, 'authors' => $book->getAuthors(), 'all_authors' => Author::getAll()));
    });
    $app->post("/book_add_author", function() use ($app) {
        $author = Author::findAuthor($_POST['author_id']);
        $book = Book::findBook($_POST['book_id']);
        $book->addAuthor($author);
        return $app['twig']->render('book.html.twig', array('book' => $book, 'authors' => $book->getAuthors(), 'all_authors' => Author::getAll()));
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


    return $app;
?>
