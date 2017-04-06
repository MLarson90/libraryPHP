<?php
  require_once __DIR__."/../vendor/autoload.php";
  require_once __DIR__."/../src/Books.php";
  require_once __DIR__.'/../src/Author.php';
  require_once __DIR__.'/../src/Member.php';
  use Symfony\Component\Debug\Debug;
  Debug::enable();

  $app = new Silex\Application();
  $DB = new PDO('mysql:host=localhost:8889;dbname=library', 'root', 'root');
  $app['debug'] = true;
  $app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views'
  ));

  $app->get("/", function() use ($app) {
    return $app['twig']->render('index.html.twig');
  });
  $app->get('/admin', function() use ($app){
    return $app['twig']->render('admin.html.twig');
  });
  $app->get('/allbooks', function() use ($app){
    return $app['twig']->render('allbooks.html.twig', array('steve' => Books::getAll()));
  });
  $app->post('/addbook', function() use ($app){
    $newBook = new Books ($_POST['title'], $_POST['loc']);
    $newBook->save();
    $newAuthor = new Author ($_POST['first'], $_POST['last']);
    $newAuthor->save();
    $quanity = $_POST['quanity'];
    $newBook->addAuthor($newAuthor);
    $newBook->addQuanity($quanity);
    $author=$newBook->findAuthors();
    var_dump($author);
    return $app['twig']->render('bookConfirm.html.twig', array('book'=> $newBook, 'author' => $newAuthor, 'quanity' => $quanity));
  });
  $app->get('/book{id}', function($id) use ($app){
    $book = Books::findBookbyId($id);
    return $app['twig']->render('book.html.twig', array('book' => $book));
  });
  return $app;
 ?>
