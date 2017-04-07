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
    return $app['twig']->render('adminlogin.html.twig');
  });
  $app->get('/adminback', function() use ($app){
    return $app['twig']->render('admin.html.twig');
  });
  $app->get('/guest', function() use ($app){
    return $app['twig']->render('guest.html.twig');
  });
  $app->get('/guestloggedin', function() use ($app){
    return $app['twig']->render('guestloggedin.html.twig');
  });
  $app->get('/allbooks', function() use ($app){
    $result = Books::getAll();
    return $app['twig']->render('allbooks.html.twig', array('susans' => $result));
  });
  $app->get('/allbooksadmin', function() use ($app){
    return $app['twig']->render('allbooksadmin.html.twig', array('steve' => Books::getAll()));
  });
  $app->get('/allauthors', function() use ($app){
    return $app['twig']->render('allauthors.html.twig', array('steve' => Author::getAll()));
  });
  $app->get('/allauthorsadmin', function() use ($app){
    return $app['twig']->render('allauthorsadmin.html.twig', array('steve' => Author::getAll()));
  });
  $app->post('/addbook', function() use ($app){
    $newBook = new Books ($_POST['title'], $_POST['loc']);
    $newBook->save();
    $quanity = $_POST['quanity'];
    $newBook->addQuanity($quanity);
    $newBook->addTotalQuanity($quanity);
    $authors_first = Author::getAuthorFirstArray();
    $authors_last = Author::getAuthorLastArray();
    if((in_array($_POST['last'], $authors_last)) && (in_array($_POST['first'], $authors_first))){
      $OldAuthor = Author::findAuthorByName($_POST['last']);
      $newBook->addAuthor($OldAuthor);
      return $app['twig']->render('bookConfirm.html.twig', array('book'=> $newBook, 'author' => $OldAuthor, 'quanity' => $quanity));
    }else{
    $newAuthor = new Author ($_POST['first'], $_POST['last']);
    $newAuthor->save();
    $author=$newBook->findAuthors();
    $newBook->addAuthor($newAuthor);
    return $app['twig']->render('bookConfirm.html.twig', array('book'=> $newBook, 'author' => $newAuthor, 'quanity' => $quanity));
  }
  });
  $app->get('/book{id}', function($id) use ($app){
    $book = Books::findBookbyId($id);
    return $app['twig']->render('book.html.twig', array('book' => $book));
  });
  $app->get('/bookadmin{id}', function($id) use ($app){
    $book = Books::findBookbyId($id);
    return $app['twig']->render('bookadmin.html.twig', array('book' => $book));
  });
  $app->get('/author{id}', function($id) use ($app){
    $authors = Author::findAuthor($id);
    $books = $authors->findBooks();
    return $app['twig']->render('author.html.twig', array('author' => $authors, 'books' => $books));
  });
  $app->post('/createuser', function() use ($app){
    $member = new Member($_POST['newFirst'], $_POST['newLast'], $_POST['newLogin'], $_POST['newPassword']);
    $member->save();
    return $app['twig']->render('guestloggedin.html.twig');
  });
  $app->post('/login', function() use ($app){
    $check = Member::login($_POST['login'], $_POST['password']);
    if($check == true){
      return $app['twig']->render('guestloggedin.html.twig');
    }else{
      return $app['twig']->render('denied.html.twig');
    }
  });
  $app->post('/addlogin', function() use ($app){
    $check = Member::login($_POST['login'], $_POST['password']);
    if($check == true){
      return $app['twig']->render('admin.html.twig');
    }else{
      return $app['twig']->render('denied.html.twig');
    }
  });
  $app->post('/search', function() use ($app){
    $title = $_POST['searchTitle'];
    $book = Books::findbookByTitle($title);
     if($book != ""){
       return $app['twig']->render('book.html.twig', array('book' => $book));
     }else{
       $last = $_POST['searchLast'];
       $author = Author::findAuthorByName($last);
       $booksFromAuthor = $author->findBooks();
       return $app['twig']->render('author.html.twig', array('author'=>$author, 'books'=> $booksFromAuthor));
     }
  });
  return $app;
 ?>
